<?php


namespace App\Service;


use Framework\SwServer\Pool\DiPool;




class UserService implements UserInterface
{
    public function notify($orderData)
    {
        print_r($orderData);
        echo __CLASS__ . "/" . __METHOD__ . "\r\n";
    }

    public function findUser()
    {

        $userData = [];
         
        return $userData;
    }


    public function getList(int $uid, string $type){
        return [$uid,$type];
    }

    public function eat()
    {
        echo "eat..........\r\n";
    }
}