<?php
/*** a simple array ***/
$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypus');

try {
    $object = new ArrayIterator($array);
    /*** check for the existence of the offset 2 ***/
    if ($object->offSetExists(2)) {
        /*** set the offset of 2 to a new value ***/
        $object->offSetSet(2, 'Goanna');
    }
    /*** unset the kiwi ***/
    foreach ($object as $key => $value) {
        /*** check the value of the key ***/
        if ($object->offSetGet($key) === 'kiwi') {
            /*** unset the current key ***/
            $object->offSetUnset($key);
        }
        echo '<li>' . $key . ' - ' . $value . '</li>' . "\n";
    }

    print_r((array)$object);
} catch (Exception $e) {
    echo $e->getMessage();
}


$array = array(
    array(
        array('name' => 'butch', 'sex' => 'm', 'breed' => 'boxer'),
        array('name' => 'fido', 'sex' => 'm', 'breed' => 'doberman'),
        array('name' => 'girly', 'sex' => 'f', 'breed' => 'poodle')
    ),
    array(
        array('name' => 'butch', 'sex' => 'm', 'breed' => 'boxer'),
        array('name' => 'fido', 'sex' => 'm', 'breed' => 'doberman'),
        array('name' => 'girly', 'sex' => 'f', 'breed' => 'poodle')
    )
);
foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
    echo $key . ' -- ' . $value . '<br />';
}