<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use Febis\SimpleTca\Data\Table;
use Febis\SimpleTca\Exception\NoIdentifierException;
use Febis\SimpleTca\Exception\NoTablenameException;
use Febis\SimpleTca\TcaGenerator;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Currently attributes will only compared in first layer.
 * TODO: add recursively comparison for attributes to load default or overridden values
 * TODO: refactor split into separate classes to maintain responsibilities
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
abstract class AbstractShortcut implements TcaShortcutInterface, \ArrayAccess
{
    abstract protected static function getType(): string;

    /** String[] */
    abstract protected static function getAllowedProperties(): array;

    abstract protected static function getDefaultProperties(): array;

    abstract protected static function getSqlDefinition(): Field;

    /** For Properties, that are not set by this shortcut, but allowed by TYPO3. Set by withAdditionalAttributes */
    protected ?array $additionalAttributes = null;

    protected array $unsetAttributes = [];

    protected ?string $label = null;

    protected ?string $description = null;

    protected bool $exclude = true;

    protected ?string $onChange = null;

    protected string | array | null $displayCond = null;

    public function __construct(
        protected ?string $identifier = null,
        protected ?string $tablename = null,
        protected ?Field $overrideField = null,
    ) {
        if (!is_null($this->identifier)) {
            $this->withIdentifier($this->identifier);
        }
    }

    public function withIdentifier(?string $identifier): static
    {
        $this->identifier = $identifier;
        $this->label = TcaGenerator::translate($identifier);
        $this->description = TcaGenerator::translate($identifier . '.description');

        return $this;
    }

    /**
     * This method is marked internal as it normally shouldn't be called manually. The table is automatically fetched
     * by the TCA filename where the Shortcut is used. E.g. if the file is 'TCA/Overrides/tt_content_example.php',
     * then it tries to select table 'tt_content_example' and fallbacks (if not exists) to table 'tt_content'.
     * @internal
     */
    public function withTablename(?string $tablename): static
    {
        $this->tablename = $tablename;

        return $this;
    }

    public function withExclude(bool $exclude = true): static
    {
        $this->exclude = $exclude;

        return $this;
    }

    public function withDisplayCond(string | array | null $displayCond = null): static
    {
        $this->displayCond = $displayCond;

        return $this;
    }

    public function withOnChange(?string $onChange = 'reload'): static
    {
        $this->onChange = $onChange;

        return $this;
    }

    public function withReload(): static
    {
        return $this->withOnChange();
    }

    #[\Override]
    public function withArguments(?array $args): static
    {
        foreach ($args as $argName => $arg) {
            if (property_exists(static::class, $argName)) {
                $this->$argName = $arg;
            }
        }

        return $this;
    }

    public function withAdditionalAttributes(?array $additionalAttributes = null): static
    {
        $this->additionalAttributes = $additionalAttributes;

        return $this;
    }

    public function overrideSqlDefinition(?Field $overrideField): static
    {
        $this->overrideField = $overrideField;
        return $this;
    }

    /**
     * @throws NoIdentifierException
     * @throws NoTablenameException
     */
    #[\Override]
    public function build(): array
    {
        if (null === $this->identifier) {
            throw new NoIdentifierException();
        }

        if (null === $this->tablename) {
            throw new NoTablenameException();
        }

        $this->addFieldForDbGeneration($this->identifier);

        $tca = $this->buildContainer();
        $tca['config'] = $this->buildConfig();

        return $tca;
    }

    protected function buildContainer(): array
    {
        $container = [
            '_identifier' => $this->identifier,
            'label' => $this->label,
            'description' => $this->description,
            'exclude' => $this->exclude,
            'config' => [],
        ];

        if ($this->displayCond) {
            $container['displayCond'] = $this->displayCond;
        }

        if ($this->onChange) {
            $container['onChange'] = $this->onChange;
        }

        return $container;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function buildConfig(): array
    {
        $config = static::getDefaultProperties();
        $config['type'] = static::getType();

        $mergedProperties = array_unique(
            [
                ...static::toLowerCamelCase(static::getAllowedProperties()),
                ...array_keys($this->additionalAttributes ?? []),
            ],
        );

        foreach ($mergedProperties as $property) {
            if ($property === 'type') {
                continue;
            }

            $value = null;

            /** prior attributes that are explicitly defined in class */
            if (property_exists(static::class, $property)) {
                $value = $this->$property;
            }

            /** if attribute is not defined in class, check if the attribute exists in additionalAttributes */
            if (!property_exists(static::class, $property) && isset($this->additionalAttributes[$property])) {
                $value = $this->additionalAttributes[$property];
            }

            /** replace property name by the TCA one if exists **/
            $property = static::getAllowedPropertyByLCC($property) ?? $property;

            /** set the attribute, or delete it, if it was intentionally reset */
            if (null !== $value) {
                if (isset($config[$property]) && is_array($config[$property])) {
                    ArrayUtility::mergeRecursiveWithOverrule($config[$property], $value);
                } else {
                    $config[$property] = $value;
                }
            }

            if (($this->unsetAttributes[$property] ?? false)) {
                unset($config[$property]);
            }
        }

        return $config;
    }

    public function __call(string $name, array $arguments): static
    {
        /** match 'with...'-Calls like withEval or withSize, but not for withType */
        preg_match('/\Awith([A-Z][A-z0-9]+)\z/', $name, $match);

        if (false === isset($match[0], $match[1])) {
            return $this;
        }

        if (
            in_array(
                static::toLowerCamelCase($match[1]),
                static::toLowerCamelCase(static::getAllowedProperties()),
                true,
            )
        ) {
            $property = self::toLowerCamelCase($match[1]);

            if ($property === 'type') {
                return $this;
            }

            $this->unsetAttributes[$property] = empty($arguments[0]);

            if (property_exists(static::class, $property)) {
                $this->$property = $arguments[0];
            } else {
                $this->additionalAttributes[$property] = $arguments[0];
            }
        }

        return $this;
    }

    protected function addFieldForDbGeneration(string $identifier): void
    {
        TcaGenerator::getTcaDefinitionDataInstance()->addTable(
            new Table(
                [
                    $identifier => $this->overrideField ?? static::getSqlDefinition(),
                ],
            ),
            $this->tablename,
        );
    }

    /**
     * Important: Do NOT replace by GeneralUtility::underscoredToLowerCamelCase because that method doesn't respect
     * strings that are already in lower camel case format
     */
    protected static function toLowerCamelCase(array | string $str): array | string
    {
        $separators = ' _-';
        $separatorsRegex = '\s\-_';

        if (is_array($str)) {
            return array_map(static::toLowerCamelCase(...), $str);
        }

        return lcfirst((string)preg_replace(sprintf('/[%s]+/', $separatorsRegex), '', ucwords($str, $separators)));
    }

    protected static function getAllowedPropertyByLCC($lcc): ?string
    {
        $filtered = array_filter(
            static::getAllowedProperties(),
            static fn ($property) => static::toLowerCamelCase($property) === $lcc,
        );

        return $filtered !== [] ? reset($filtered) : null;
    }

    #[\Override]
    public function offsetExists(mixed $offset): bool
    {
        return property_exists(static::class, $offset);
    }

    #[\Override]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->offsetExists($offset) ? $this->$offset : null;
    }

    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->offsetExists($offset)) {
            $this->$offset = $value;
        }
    }

    #[\Override]
    public function offsetUnset(mixed $offset): void
    {
        if ($this->offsetExists($offset)) {
            $this->$offset = null;
        }
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
