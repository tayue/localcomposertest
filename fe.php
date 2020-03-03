<?php
//$a='314567860924694';
//$str = number_format($a, 0, '', '');
//$str="abc || ded";
//$carAttrGroups=explode("||",$str);
//print_r($carAttrGroups);
//$carAttrs='[
//            {
//                "attr_name_id":231208804,
//                "attr_name":"Country",
//                "attr_value":"United States & Canada",
//                "attr_value_id":6939507751
//            },
//            {
//                "attr_name":"Make",
//                "attr_name_id":231873779,
//                "attr_value":"Toyota",
//                "attr_value_id":100009339
//            }
//
//]';
//$carAttrs1='[
//
//            {
//                "attr_name_id":231208804,
//                "attr_name":"Country",
//                "attr_value":"United States & Canada",
//                "attr_value_id":6939507751
//            },
//          {
//                "attr_name":"Make",
//                "attr_name_id":231873779,
//                "attr_value":"Toyota",
//                "attr_value_id":100009339
//            }
//
//]';
//
//
//$carAttrs=json_decode($carAttrs,true);
//
//$carAttrs1=json_decode($carAttrs1,true);
//var_dump($carAttrs1==$carAttrs);
//
////print_r($carAttrs1);
////$res=array_merge($carAttrs,$carAttrs1);
////var_dump($res);
////$res1=getAdvtModelFormatData($res);
////
////print_r(json_decode($res1,true));
//
// function getAdvtModelFormatData($carApiParams){
//        if(isset($carApiParams) && is_array($carApiParams)){
//            foreach($carApiParams as $key=>$value){
//                if(isset($value['attr_name_id'])){
//                    $carApiParams[$key]['attrNameId'] = $value['attr_name_id'];
//                    unset($carApiParams[$key]['attr_name_id']);
//                }
//                if(isset($value['attr_value_id'])){
//                    $carApiParams[$key]['attrValueId'] = $value['attr_value_id'];
//                    unset($carApiParams[$key]['attr_value_id']);
//                }
//                if(isset($value['attr_name'])){
//                    $carApiParams[$key]['attrName'] = $value['attr_name'];
//                    unset($carApiParams[$key]['attr_name']);
//                }
//                if(isset($value['attr_value'])){
//                    $carApiParams[$key]['attrValue'] = $value['attr_value'];
//                    unset($carApiParams[$key]['attr_value']);
//                }
//            }
//            //$carApiParams = self::dealWithListStyleData($carApiParams['aeop_ae_product_property']);
//            $carApiParams = str_replace("\\/", "/", json_encode($carApiParams));
//        }else{
//            $carApiParams = '[]';
//        }
//        return $carApiParams;
//    }

//function retryApiErrorAdvt($errorMsg,$retry=5){
//        try {
//            if(stristr($errorMsg,"system is busing,please try again") || stristr($errorMsg,"property value not in Candidate List.")) {//重试调用
//                sleep(2);
//                echo "Api报错尝试重新调用api更新产品：===>{$retry}\r\n";
//                --$retry;
//                if($retry==3){
//                    throw new Exception("{$retry} Success");
//                }else{
//                    throw new Exception("{$retry} system is busing,please try again");
//                }
//
//            }else{
//                echo "$errorMsg ###############\r\n";
//            }
//        } catch (Exception $exception) {
//            if($retry){
//                 retryApiErrorAdvt($exception->getMessage(),$retry);
//            }
//        }
//   }
//
//retryApiErrorAdvt("property value not in Candidate List.");


function myfunction($value,$key)
{
echo "The key $key has the value $value<br>";
}
$a=array("a"=>"red","b"=>"green","c"=>"blue");
array_walk($a,"myfunction");


//$carAttrs = array_unique($res, SORT_REGULAR); //去重
//print_r($carAttrs);



