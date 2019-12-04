<?php

/**
 * A class that can be used like an array
 */
class Article implements ArrayAccess, IteratorAggregate,Countable
{

    public $title;

    public $author;

    public $category;

    function __construct($title, $author, $category)
    {
        $this->title = $title;
        $this->author = $author;
        $this->category = $category;
    }

    /**
     * Defined by ArrayAccess interface
     * Set a value given it's key e.g. $A['title'] = 'foo';
     * @param mixed key (string or integer)
     * @param mixed value
     * @return void
     */
    function offsetSet($key, $value)
    {
        if (array_key_exists($key, get_object_vars($this))) {
            $this->{$key} = $value;
        }
    }

    /**
     * Defined by ArrayAccess interface
     * Return a value given it's key e.g. echo $A['title'];
     * @param mixed key (string or integer)
     * @return mixed value
     */
    function offsetGet($key)
    {
        if (array_key_exists($key, get_object_vars($this))) {
            return $this->{$key};
        }
    }

    /**
     * Defined by ArrayAccess interface
     * Unset a value by it's key e.g. unset($A['title']);
     * @param mixed key (string or integer)
     * @return void
     */
    function offsetUnset($key)
    {
        if (array_key_exists($key, get_object_vars($this))) {
            unset($this->{$key});
        }
    }

    /**
     * Defined by ArrayAccess interface
     * Check value exists, given it's key e.g. isset($A['title'])
     * @param mixed key (string or integer)
     * @return boolean
     */
    function offsetExists($offset)
    {
        return array_key_exists($offset, get_object_vars($this));
    }

    /** IteratorAggregate 中 getIterator() 返回一个实例本对象的迭代器对象
     * Defined by IteratorAggregate interface
     * Returns an iterator for for this object, for use with foreach
     * @return ArrayIterator
     */
    function getIterator() {
        return new ArrayIterator($this);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count(get_object_vars($this));
    }
}


//使用方法如下：

// Create the object
$A = new Article('SPL Rocks', 'Joe Bloggs', 'PHP');

// Check what it looks like
echo 'Initial State:<div>';
print_r($A);
echo '</div>';

// Change the title using array syntax
$A['title'] = 'SPL _really_ rocks';

// Try setting a non existent property (ignored)
$A['not found'] = 1;

// Unset the author field
unset($A['author']);

// Check what it looks like again
echo 'Final State:<div>';
print_r($A);
echo '</div>';


echo 'Looping with foreach:<div>';
foreach ( $A as $field => $value ) {
    echo "$field : $value<br>";
}
echo '</div>';

// Get the size of the iterator (see how many properties are left)
echo "Object has ".count($A)." elements";