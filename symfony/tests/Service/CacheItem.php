<?php

namespace App\Tests\Service;

use Symfony\Contracts\Cache\ItemInterface;


class CacheItem implements ItemInterface
{
	protected $key;
    protected $value;

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function isHit(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function set($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function expiresAt($expiration): self
    {

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function expiresAfter($time): self
    {

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function tag($tags): ItemInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(): array
    {
        return [];
    }
}
