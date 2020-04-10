<?php
//https://dr.szecommerce.com/description/desc_2003171707037014.jpg?x-oss-process=image/crop,w_800,g_nw/indexcrop,y_800,i_0

/**
 * 阿里oss纵向剪切图片
 * @param $src_path  oss图片地址路径
 * @param $fixWidth  图片固定宽度
 * @param $fixHeight 图片剪切高度
 * @return array
 */
function getOssResizeYCutImages($src_path, $fixWidth = 800, $fixHeight = 800)
{
    $imgUrls = array();
    if (!$src_path) {
        return $imgUrls;
    }
    if (stristr($src_path, "?")) { //处理地址中?xx后缀参数
        $pos = strpos($src_path, "?");
        $src_path = substr($src_path, 0, $pos);
    }
    if (!$src_path) {
        return $imgUrls;
    }
    //获取图片实际的宽度和高度
    list($width, $height) = getimagesize($src_path);
    if (!$width || !$height) {
        return $imgUrls;
    }
    $baseOperateImgUrl = $src_path . "?x-oss-process=image";
    $resizeOperateParams = "";
    if ($width > $fixWidth) { //如果实际图片宽度大于固定图片宽度那么就要进行缩略操作
        $resizeOperateParams = "/crop,w_{$fixWidth},g_nw";
        $resizeImgUrl = $baseOperateImgUrl . $resizeOperateParams;
        list($width, $height) = getimagesize($resizeImgUrl);
        if (!$width || !$height) {
            return $imgUrls;
        }
    }
    $p = ceil($height / $fixHeight);
    var_dump($p);
    $cropParamsPre = "?x-oss-process=image/indexcrop,y_{$fixHeight},i_"; //阿里图片索引纵向剪切参数
    for ($i = 0; $i < $p; $i++) {
        $imgUrls[] = $src_path . $cropParamsPre . $i;
    }
    return $imgUrls;
}

$src_path = "https://dr.szecommerce.com/description/desc_2003171707037014.jpg";
getOssResizeYCutImages($src_path);