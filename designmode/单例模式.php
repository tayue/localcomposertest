<?php

//单例模式

class Instance
{

    public static $instance;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}