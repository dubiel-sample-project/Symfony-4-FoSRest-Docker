<?php

namespace App\Tests\Service;

use Symfony\Contracts\Cache\ItemInterface;

/**
  * Class CacheItem
  * @package App\Tests\Service
  */
class CacheItem implements ItemInterface
{
    protected $key;
    protected $value;

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @return false
     */
    public function isHit(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @return ItemInterface
     */
    public function set($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return ItemInterface
     */
    public function expiresAt($expiration): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return ItemInterface
     */
    public function expiresAfter($time): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return ItemInterface
     */
    public function tag($tags): ItemInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return [];
    }
}