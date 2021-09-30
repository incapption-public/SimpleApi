<?php

namespace Incapption\SimpleApi\Models;

class ResourceParameter
{
    /**
     * @var string
     */
    private $placeholder;
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $value;

    public function __construct(string $key, string $value, string $placeholder)
    {
        $this->key         = $key;
        $this->value       = $value;
        $this->placeholder = $placeholder;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}