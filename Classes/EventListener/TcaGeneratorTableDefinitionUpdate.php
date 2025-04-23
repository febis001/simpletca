<?php

declare(strict_types=1);

namespace Febis\SimpleTca\EventListener;

use Febis\SimpleTca\TcaGenerator;
use TYPO3\CMS\Core\Database\Event\AlterTableDefinitionStatementsEvent;

/**
 * Class TcaGeneratorTableDefinitionUpdate
 * @package Febis\SimpleTca\EventListener
 */
class TcaGeneratorTableDefinitionUpdate
{
    public function __invoke(AlterTableDefinitionStatementsEvent $event)
    {
        $event->setSqlData(
            [
                ...TcaGenerator::getTcaDefinitionDataInstance()->getSqlArray(),
                ...$event->getSqlData(),
            ],
        );
    }
}
