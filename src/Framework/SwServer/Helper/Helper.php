<?php


namespace Framework\SwServer\Helper;

use Framework\SwServer\Consul\Agent;

class Helper
{
    public static function registerService(array $consulConfigs,string $serviceId,string $host,string $port)
    {
        foreach ($consulConfigs as $eachConsulConfig){
            $json = '{
                  "ID": "' . $serviceId . '",
                  "Name": "' . $serviceId . '",
                  "Address": "'.$host.'",
                  "Port": '.$port.',
                  "EnableTagOverride": false
               }';
            $serivce = json_decode($json, true);
            Agent::getInstance()->createConsul($eachConsulConfig)->registerService($serivce);
        }
        return $serviceId;
    }


    public static function removeService(string $serviceId)
    {
        $response = Agent::getInstance()->deregisterService($serviceId);
        return $response;
    }


}