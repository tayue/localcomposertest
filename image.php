<?php
//require_once __DIR__.'/vendor/autoload.php';
ini_set("display_errors", "On");//打开错误提示
ini_set("error_reporting", E_ALL);//显示所有错误
$filename = './test1.jpg';
//$p = 5;
//// Get new sizes
//$fixPicHight=800;
//list($width, $height) = getimagesize($filename);
//
//echo "height:{$height}\r\n";
//$p=ceil($height/$fixPicHight);
//$newwidth = $width;
//$newheight=0;
//$last = $height % 800;
//
//// Load
//$source = imagecreatefromjpeg($filename);
//for( $i=0 ; $i< $p; $i++ ) {
//    $_p = $fixPicHight * $i;
//    if ($last && ($i + 1) == $p){
//        $newheight=$last;
//    }else{
//        $newheight=$fixPicHight;
//    }
//
//    $thumb = imagecreatetruecolor($newwidth, $newheight); //创建真彩画布
//    imagecopyresized($thumb, $source, 0, 0, 0, $_p, $newwidth, $height, $width, $height);
//    imagejpeg($thumb, "/home/wwwroot/default/localcomposertest/h{$i}.jpg", 100);
//    imagedestroy($thumb);
//}
//imagedestroy($source);


function getOssYCutImages($src_path = "https://dr.szecommerce.com/description/desc_2003240940018527.jpg?1585014003", $fixWidth = 800, $fixHeight = 800)
{
    $imgUrls = array();
    if (stristr($src_path, "?")) { //处理地址中?xx后缀参数
        $pos = strpos($src_path, "?");
        $src_path = substr($src_path, 0, $pos);
    }
    list($width, $height) = getimagesize($src_path);
    if (!$width || !$height) {
        return $imgUrls;
    }
    if ($width > $fixWidth) {
        return $imgUrls;
    }
    $p = ceil($height / $fixHeight);
    $cropParamsPre = "?x-oss-process=image/indexcrop,y_{$fixHeight},i_"; //阿里图片索引纵向剪切参数
    for ($i = 0; $i < $p; $i++) {
        $imgUrls[] = $src_path . $cropParamsPre . $i;
    }
    return $imgUrls;
}

$urls = getOssYCutImages();
var_dump($urls);


//function img_cut_square($src_path, $productId = 2222, $des_path = "/home/wwwroot/default/localcomposertest", $fixWidth = 800, $fixHeight = 800)
//{
//    list($width, $height) = getimagesize($src_path);
//    $p = ceil($height / $fixHeight);
//    $newwidth = $fixWidth;
//    $last = $height % $fixHeight;
//    $imgstream = file_get_contents($src_path);
//    $source = imagecreatefromstring($imgstream);
//    //$source = imagecreatefromjpeg($src_path);
//    for ($i = 0; $i < $p; $i++) {
//        $_p = $fixHeight * $i;
//        if ($last && ($i + 1) == $p) {
//            $newheight = $last;
//        } else {
//            $newheight = $fixHeight;
//        }
//        $thumb = imagecreatetruecolor($newwidth, $newheight); //创建真彩画布
//        imagecopyresized($thumb, $source, 0, 0, 0, $_p, $newwidth, $height, $width, $height);
//        $des_product_path = $des_path . "/" . $productId;
//        //检测目录是否存在不存在则创建
//        if (!is_dir($des_product_path)) {
//            mkdir($des_product_path);
//        }
//        $targetPath = $des_product_path . "/p" . $i . ".jpg";
//        imagejpeg($thumb, $targetPath, 100);
//        imagedestroy($thumb);
//    }
//    imagedestroy($source);
//}
//
////$filename = './test1.jpg';
//$filename="https://dr.szecommerce.com/description/desc_2003091315507416.jpg";
//$productId=333333;
//img_cut_square($filename,$productId);


//use Grafika\Grafika;
//$editor = Grafika::createEditor();
//
//$image=null;
//$src = '/home/wwwroot/default/localcomposertest/test.jpg';
//if(file_exists($src)){
//    echo "haha\r\n";
//}
//
////$editor->open( $image, $src );
////var_dump($image);
////var_dump($editor);
////$editor->crop( $image, 300, 200, 'top-left' );
////$editor->save( $image, 'result1.jpg' );
////$editor->free( $image );
//
//
//$editor->open( $image, $src );
//$editor->crop( $image, 800, 800, 'top-left' );
//$editor->save( $image, 'yanying-smart.jpg' );
//
//$editor->crop( $image, 800, 800, 'top-left',800,800 );
//$editor->save( $image, 'yanying-smart1.jpg' );


///*
// * 图片裁剪工具
// * 将指定文件裁剪成正方形
// * 以中心为起始向四周裁剪
// * @param $src_path string 源文件地址
// * @param $des_path string 保存文件地址
// * @param $des_w double 目标图片宽度
// * */
//function img_cut_square($src_path,$des_path,$des_w=100){
//    $img_info = getimagesize($src_path);//获取原图像尺寸信息
//    $img_width = $img_info[0];//原图宽度
//    $img_height = $img_info[1];//原图高度
//    $img_type = $img_info[2];//图片类型 1 为 GIF 格式、 2 为 JPEG/JPG 格式、3 为 PNG 格式
//    if($img_type != 2 && $img_type != 3) return ;
//
//    /*计算缩放尺寸*/
//    if($img_height > $img_width){
//        $scale_width = $des_w;//缩放宽度
//        $scale_height = round($des_w / $img_width * $img_height);//缩放高度
//        $src_y = round(($scale_height - $des_w)/2);
//        $src_x = 0;
//    }else{
//        $scale_height = $des_w;
//        $scale_width = round($des_w / $img_height * $img_width);
//
//        $src_y = 0;
//        $src_x = round(($scale_width - $des_w)/2);
//    }
//
//    $dst_ims = imagecreatetruecolor($scale_width, $scale_height);//创建真彩画布
//    $white = imagecolorallocate($dst_ims, 255, 255, 255);
//    imagefill($dst_ims, 0, 0, $white);
//    if($img_type == 2){
//        $src_im = @imagecreatefromjpeg($src_path);//读取原图像
//    }else if($img_type == 3){
//        $src_im = @imagecreatefrompng($src_path);//读取原图像
//    }
//
//    imagecopyresized($dst_ims, $src_im, 0, 0 ,0, 0 , $scale_width , $scale_height , $img_width,$img_height);//缩放图片到指定尺寸
//
//
//    $dst_im = imagecreatetruecolor($des_w, $des_w);
////  $white = imagecolorallocate($dst_im, 255, 255, 255);
////  imagefill($dst_im, 0, 0, $white);
//    imagecopy($dst_im, $dst_ims, 0, 0, $src_x, $src_y, $des_w, $des_w);//开始裁剪图片为正方形
//// imagecopyresampled($dst_im, $src_im, $src_x, $src_y, 0, 0, $real_width, $real_width,$img_width,$img_height);
//    if($img_type == 2) {
//        imagejpeg($dst_im, $des_path);//保存到文件
//    }else if($img_type == 3){
//        imagepng($dst_im,$des_path);
//    }
////  imagejpeg($dst_im);//输出到浏览器
//    imagedestroy($dst_im);
//    imagedestroy($dst_ims);
//    imagedestroy($src_im);
//}




