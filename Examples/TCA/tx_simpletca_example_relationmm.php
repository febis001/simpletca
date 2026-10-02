<?php

declare(strict_types=1);

use Febis\SimpleTca\TcaGenerator;

defined('TYPO3') || die('Access denied.');

$tca = TcaGenerator::createTca();
$tca->columns->addColumn(TcaGenerator::createInput('header'));
return $tca->getTca();
