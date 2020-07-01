<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';

use Framework\Tool\Encrypter;
use Framework\Tool\DES;
use Framework\Tool\AES;
use Framework\Tool\RSA;
use Framework\Tool\Tool;
$data = 'laravel demo';

//$ivlen = openssl_cipher_iv_length('DES-CBC');
//var_dump($ivlen);

$key = openssl_random_pseudo_bytes(32);
$encrypter = new Encrypter($key, 'AES-256-CBC');

$encryStr = $encrypter->encryptString($data);

print_r(json_decode(base64_decode($encryStr), true));
$res = $encrypter->decryptString($encryStr);
var_dump($res);



$aes_key = "E7ZBdJlS";

$encrypter = new DES($key);

$str1 = DES::encrypt($data,$aes_key);

$str = DES::decrypt($str1,$aes_key);

var_dump($str);


$aes_key = "E7ZBdJlA"; //AES 密钥


$aes_data = AES::encrypt($data, $aes_key);

$str = AES::decrypt($aes_data, $aes_key);

var_dump($str);



/**
 * 在传输重要信息时, 一般会采用对称加密和非对称加密相结合的方式, 而非使用单一加密方式. 一般先通过 AES 加密数据,
 * 然后通过 RSA 加密 AES 密钥, 然后将加密后的密钥和数据一起发送. 接收方接收到数据后, 先解密 AES 密钥,
 * 然后使用解密后的密钥解密数据.
 */

$rsaRes = RSA::createKeys();
if ($rsaRes) {
    print_r($rsaRes);
    $private_key = $rsaRes['private_key'];
    $public_key = $rsaRes['public_key'];
    $res=Tool::safeEncrypt($data,$aes_key,$public_key); //A方通过AES加密值,RSA公钥加密key
    if($res){
        $data = Tool::safeDecrypt($res['rsa_aes_key'], $res['aes_data'], $private_key); //B方通过RSA私钥解密key然后通过AES解密值
        var_dump($data);
    }

}






