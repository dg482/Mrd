<?php

namespace Dg482\Mrd;

/**
 * Class LocalCache
 * @package Mrd
 */
class LocalCache
{
    /** @var bool */
    const ENABLE = true;

    /** @var array */
    private array $storage = [];

    /**
     * @param $key
     * @param $values
     */
    public function set($key, $values): void
    {
        $this->storage[$key] = $values;
    }

    /**
     * @param $key
     * @param  null  $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return $this->storage[$key] ?? $default;
    }

    /**
     * @param $key
     * @param $values
     * @param  string  $tags
     */
    public function setCache($key, $values, $tags = 'default'): void
    {
        if (self::ENABLE) {
            $this->set($tags.'-'.$key, $values);
        }
    }

    /**
     * @param $key
     * @param  null  $default
     * @param  string  $tags
     * @return array|mixed
     */
    public function getCache($key, $default = null, $tags = 'default')
    {
        return $this->get($tags.'-'.$key, $default);
    }

    /**
     * @param $key
     * @param  \Closure  $callback
     * @param  string  $tags
     * @return array|mixed
     */
    public function withCache($key, \Closure $callback, $tags = 'default')
    {
        $result = (self::ENABLE) ? $this->getCache($key, null, $tags) : null;

        if (null === $result) {
            $this->set($tags.'-'.$key, $callback());
        }

        return $result;
    }
}
