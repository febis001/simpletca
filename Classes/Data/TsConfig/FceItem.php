<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data\TsConfig;

use Febis\SimpleTca\Utility\TypoScriptHelper;

class FceItem
{
    public function __construct(
        protected string $identifier,
        protected string $groupIdentifier,
        protected string $iconIdentifier = 'default-not-found',
        protected ?string $title = null,
        protected ?string $description = null,
    ) {
    }

    public static function __set_state(array $data)
    {
        return new self(...$data);
    }

    public function generateTsConfig(): string
    {
        $objectIdentifier = sprintf('mod.wizards.newContentElement.wizardItems.%s', $this->groupIdentifier);
        $object = [
            [
                'elements',
                [
                    [
                        $this->identifier,
                        [
                            [
                                'iconIdentifier',
                                $this->iconIdentifier,
                            ],
                            [
                                'title',
                                $this->getTitle(),
                            ],
                            [
                                'description',
                                $this->getDescription(),
                            ],
                            [
                                'tt_content_defValues',
                                [
                                    [
                                        'CType',
                                        $this->identifier,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            sprintf('show := addToList(%s)', $this->identifier),
        ];
        return TypoScriptHelper::objectToTextualRepresentation($objectIdentifier, $object);
    }

    protected function getTitle(): string
    {
        return $this->title ?? $this->identifier;
    }

    protected function getDescription(): ?string
    {
        return $this->description;
    }
}
