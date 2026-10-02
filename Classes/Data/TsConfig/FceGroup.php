<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data\TsConfig;

use Febis\SimpleTca\Utility\TypoScriptHelper;

class FceGroup
{
    public function __construct(
        protected string $identifier = 'default',
        protected ?string $header = null,
        protected string $show = '*',
    ) {
    }

    public static function __set_state(array $data)
    {
        return new self(...$data);
    }

    public function generateTsConfig(): string
    {
        $objectIdentifier = sprintf('mod.wizards.newContentElement.wizardItems.%s', $this->identifier);
        $object = [
            ['header', $this->getHeader()],
            ['show', $this->show],
        ];
        return TypoScriptHelper::objectToTextualRepresentation($objectIdentifier, $object);
    }

    protected function getHeader(): string
    {
        return $this->header ?? $this->identifier;
    }
}
