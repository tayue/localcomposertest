<?php
/**
 * 容器对象池
 */
namespace ServerFramework\Di;

use ServerFramework\Traits\SingletonTrait;

/**容器类
 * Class Container
 */
class Container extends BaseContainer implements \ArrayAccess
{
    use SingletonTrait;
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->injection($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->resolvedEntries[$offset]);
        unset($this->definitions[$offset]);
    }
}