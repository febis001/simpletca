<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use Febis\SimpleTca\Data\Table;
use Febis\SimpleTca\Exception\NoIdentifierException;
use Febis\SimpleTca\TcaGenerator;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Currently attributes will only compared in first layer.
 * TODO: add recursively comparison for attributes to load default or overridden values
 */
abstract class AbstractShortcut implements TcaShortcutInterface
{
    abstract protected static function getType(): string;

    /** String[] */
    abstract protected static function getAllowedProperties(): array;

    abstract protected static function getDefaultProperties(): array;

    abstract protected static function getSqlDefinition(): Field;

    /** For Properties, that are not set by this shortcut, but allowed by TYPO3. Set by withAdditionalAttributes */
    protected ?array $additionalAttributes = null;

    protected array $unsetAttributes = [];

    public function __construct(
        protected ?string $identifier = null
    ) {
    }

    public function withIdentifier(?string $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }

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

    /**
     * @throws NoIdentifierException
     */
    public function build(?string $identifier = null): array
    {
        $identifier ??= $this->identifier;

        if (null === $identifier) {
            throw new NoIdentifierException();
        }

        $this->addFieldForDbGeneration($identifier);

        $tca = $this->buildContainer($identifier);
        $tca['config'] = $this->buildConfig();

        return $tca;
    }

    protected function buildContainer(?string $identifier): array
    {
        return [
            'label' => TcaGenerator::getConfig()->ll() . $identifier,
            'config' => []
        ];
    }

    protected function buildConfig(): array
    {
        $config = static::getDefaultProperties();
        $config['type'] = static::getType();

        $mergedProperties = array_unique(
            [
                ...static::toLowerCamelCase(static::getAllowedProperties()),
                ...array_keys($this->additionalAttributes ?? [])
            ]
        );

        foreach ($mergedProperties as $property) {
            if ($property === static::getType()) {
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
                if(isset($config[$property]) && is_array($config[$property])) {
                    ArrayUtility::mergeRecursiveWithOverrule($config[$property], $value);
                }
                else {
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

        if (
            isset($match[0], $match[1]) &&
            in_array(
                static::toLowerCamelCase($match[1]),
                static::toLowerCamelCase(static::getAllowedProperties()),
                true
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
        $identifierNoTable = explode('.', $identifier);
        $identifierNoTable = array_pop($identifierNoTable);
        TcaGenerator::getTcaDefinitionDataInstance()->addTable(
            new Table([
                $identifierNoTable => static::getSqlDefinition()
            ]),
            TcaGenerator::getTablename()
        );
    }

    protected static function toLowerCamelCase(array|string $str): array|string
    {
        $separators = ' _-';
        $separatorsRegex = '\s\-_';

        if (is_array($str)) {
            return array_map(static::class . '::toLowerCamelCase', $str);
        }

        return lcfirst(preg_replace("/[$separatorsRegex]+/", '', ucwords($str, $separators)));
    }

    protected static function getAllowedPropertyByLCC($lcc): ?string
    {
        $filtered = array_filter(
            static::getAllowedProperties(),
            static fn($property) => static::toLowerCamelCase($property) === $lcc
        );

        return false === empty($filtered) ? reset($filtered) : null;
    }
}
