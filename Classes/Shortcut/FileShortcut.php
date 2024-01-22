<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 */
class FileShortcut extends AbstractShortcut
{
    protected static function getType(): string
    {
        return "file";
    }

    protected static function getAllowedProperties(): array
    {
        return ['minitems', 'maxitems', 'overrideChildTca', 'allowed'];
    }

    protected static function getDefaultProperties(): array
    {
        return [
            'allowed' => 'common-image-types',
        ];
    }

    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'INT',
            0
        );
    }

    public function __construct(
        ?string $label = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
        protected ?string $allowed = null,
    ) {
        parent::__construct($label);
    }
}
