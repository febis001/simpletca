<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Configuration;

use Febis\SimpleTca\Exception\InvalidConfigException;

trait ConfigApi
{
    public function get(string $key): mixed
    {
        return $this->cachedConfigs['file'][$this->current['fileId']]->{$key}
            ?? $this->cachedConfigs['extension'][$this->current['extensionId']]->{$key}
            ?? $this->cachedConfigs['system']->{$key}
            ?? null;
    }

    /**
     * @throws InvalidConfigException
     */
    public function set(string $key, mixed $value, string $level = ConfigInterface::LEVEL_FILE): void
    {
        match ($level) {
            ConfigInterface::LEVEL_SYSTEM => $this->cachedConfigs['system']->{$key} = $value,
            ConfigInterface::LEVEL_EXTENSION => $this->cachedConfigs['extension'][$this->current['extensionId']]->{$key} = $value,
            ConfigInterface::LEVEL_FILE => $this->cachedConfigs['file'][$this->current['fileId']]->{$key} = $value,
            default => throw new InvalidConfigException(
                sprintf("The config type '%s' does not exist.", $level),
                1729511178,
            ),
        };
    }

    public function ll(): string
    {
        return $this->get('llFullOverride') ??
            'LLL:EXT:' .
            $this->current['extensionId'] .
            '/Resources/Private/Language/' .
            ($this->get('llFile') ?? 'NO_FILE_SPECIFIED') .
            '.xlf:';
    }

    /**
     * @throws InvalidConfigException
     */
    public function setDefaultLocalizationFile(string $filename): void
    {
        $this->setLlFile($filename, ConfigInterface::LEVEL_SYSTEM);
    }

    /**
     * @throws InvalidConfigException
     */
    public function setLlFile(string $llFile, string $level = ConfigInterface::LEVEL_FILE): void
    {
        $this->set('llFile', $llFile, $level);
    }

    /**
     * @throws InvalidConfigException
     */
    public function setLlFullOverride(?string $llFullOverride, string $level = ConfigInterface::LEVEL_FILE): void
    {
        $this->set('llFullOverride', $llFullOverride, $level);
    }

    public function getTablename(): string
    {
        return $this->get('tablenameOverride') ?? $this->get('tablenameExtracted');
    }

    public function getExtkey(): string
    {
        return $this->get('extKey');
    }

    public function setTablename(?string $tablename): void
    {
        try {
            $this->set('tablenameOverride', $tablename);
        } catch (InvalidConfigException) {
            // Should never occur at this time, because default Level is used
        }
    }

    /**
     * @throws InvalidConfigException
     */
    public function setFceGroup(?string $fceGroup, string $level = ConfigInterface::LEVEL_EXTENSION): void
    {
        $this->set('tsConfigFceGroup', $fceGroup, $level);
    }
}
