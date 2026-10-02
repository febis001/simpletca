<?php

declare(strict_types=1);

namespace Febis\SimpleTca\EventListener;

use Febis\SimpleTca\TcaGenerator;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Event\AfterTemplatesHaveBeenDeterminedEvent;

final class TypoScriptLoader
{
    public function __invoke(AfterTemplatesHaveBeenDeterminedEvent $event): void
    {
        $rows = $event->getTemplateRows();

        $rootLine = $event->getRootline();
        if ($rootLine === []) {
            return;
        }

        foreach ($rootLine as $pageRecord) {
            $rows[] = [
                'config' => TcaGenerator::getTyposcriptData()->getFullTyposcript(),
                'constants' => '',
                'static_file_mode' => 1,
                'clear' => 0,
                'include_static_file' => '',
                'basedOn' => '',
                'includeStaticAfterBasedOn' => false,
                'tstamp' => TcaGenerator::getTyposcriptData()->getTimestamp(),
                'uid' => (int)$pageRecord['uid'],
                'pid' => (int)$pageRecord['pid'],
                'title' => 'SimpleTCA for page ' . (int)$pageRecord['uid'],
                'root' => false,
            ];
        }

        $event->setTemplateRows($rows);
    }
}
