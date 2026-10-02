<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data\Typoscript;

use Febis\SimpleTca\Utility\TypoScriptHelper;

class BaseFceItem
{
    public function __construct(
        protected string $extKey,
    ) {
    }

    public static function __set_state(array $data)
    {
        return new self(...$data);
    }

    public function getObjectName(): string
    {
        return sprintf('%s.%s', TyposcriptDataHandling::BASE_PREFIX, $this->extKey);
    }

    public function generateTyposcript(): string
    {
        $tsObject = [
            ['layoutRootPaths.10', sprintf('EXT:%s/Resources/Private/Layouts/Content/', $this->extKey)],
            ['templateRootPaths.10', sprintf('EXT:%s/Resources/Private/Templates/Content/', $this->extKey)],
            ['partialRootPaths.10', sprintf('EXT:%s/Resources/Private/Partials/Content/', $this->extKey)],
            ['templateName', 'Missing'],
        ];

        $typoscript = [];
        $typoscript[] = sprintf('%s < lib.contentElement', $this->getObjectName());
        $typoscript[] = TypoScriptHelper::objectToTextualRepresentation($this->getObjectName(), $tsObject);

        return implode("\n", $typoscript);
    }
}
