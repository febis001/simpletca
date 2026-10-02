<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data\Typoscript;

class DataProcessorItem
{
    /**
     * @param DataProcessorItem[] $subDataProcessors
     */
    public function __construct(
        protected string $type,
        protected array $config,
        protected array $subDataProcessors = [],
    ) {
    }

    public static function __set_state(array $data)
    {
        return new self(...$data);
    }

    public function getProcessorClass(): string
    {
        return $this->type;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return DataProcessorItem[]
     */
    public function getSubDataProcessors(): array
    {
        return $this->subDataProcessors;
    }
}
