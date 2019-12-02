<?php
$json=array('service' => 'App/WebSocket/User/CheckService','operate'=>'tcp', 'params' => array('a'=>1,'b'=>2));
echo json_encode($json);