<?php
/**
 * 演示类
 */

namespace ServerFramework\Bean;


class Tool
{
    public $functions;
    public function __construct(Functions $functions)
    {
        $this->functions = $functions;
    }

    public function display()
    {
        echo $this->functions->test()."\r\n";
        return __CLASS__ . ':' . __FUNCTION__ . "\r\n";
    }

}