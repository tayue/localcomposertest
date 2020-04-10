<?php
require '../vendor/autoload.php';
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
echo "333333333333333\r\n";
use App\Helper\ComposerHelper;
$classLoader=ComposerHelper::getClassLoader();
$includedFiles = get_included_files();

print_r($includedFiles);