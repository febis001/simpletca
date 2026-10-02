<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class CropColumnGenerator
{
    protected array $cropVariants = [];

    protected array $cropConfig = [
        'config' => [
            'cropVariants' => [

            ],
        ],
    ];

    public function forAllCropVariants(): static
    {
        $this->cropVariants = static::getCropVariants();
        return $this;
    }

    /**
     * Set Crop Variants for which aspect ratios will be disabled next
     * @param string[] $cropVariants
     */
    public function forCropVariants(...$cropVariants): static
    {
        $this->cropVariants = $cropVariants;
        return $this;
    }

    /**
     * disable aspect ratios for defined crop variants
     * @param string[] $disabledRatios
     */
    public function disableAspectRatios(...$disabledRatios): static
    {
        $cropVariants = &$this->cropConfig['config']['cropVariants'];

        foreach ($this->cropVariants as $variant) {
            $cropVariants[$variant] = [
                'allowedAspectRatios' => $this->generateDisabledConfig($disabledRatios),
            ];
        }

        return $this;
    }

    /**
     * disable every else aspect ratios for defined crop variants
     * @param string[] $enabledRatios
     */
    public function enableAspectRatios(...$enabledRatios): static
    {
        $disabledRatios = array_diff(static::getAvailableRatios(), $enabledRatios);
        return $this->disableAspectRatios(...$disabledRatios);
    }

    /**
     * Returns the crop column definition with disabled aspect ratios
     */
    public function build(): array
    {
        return $this->cropConfig;
    }

    protected function generateDisabledConfig(array $disabledRatios): array
    {
        return array_map(static fn () => [
            'disabled' => true,
        ], array_flip($disabledRatios));
    }

    protected static function getAvailableRatios(): array
    {
        $ratios = [];
        $variants =
            BackendUtility::getPagesTSconfig(1)['TCEFORM.']['sys_file_reference.']['crop.']['config.']['cropVariants.']
            ?? [];
        foreach ($variants as $variant) {
            foreach (array_keys($variant['allowedAspectRatios.']) as $aspectRatio) {
                $ratios[] = trim($aspectRatio, '.');
            }
        }

        $ratios = array_filter($ratios); // remove null values
        return array_unique($ratios);
    }

    protected static function getCropVariants(): array
    {
        $variants =
            BackendUtility::getPagesTSconfig(1)['TCEFORM.']['sys_file_reference.']['crop.']['config.']['cropVariants.']
            ?? [];
        return array_map(static fn ($variant) => trim($variant, '.'), array_keys($variants));
    }
}
