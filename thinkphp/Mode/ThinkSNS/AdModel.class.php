<?php
import('AdvModel');
class AdModel extends AdvModel
{
    /*
     * 获取所有广告
     *
     */
    function getAds() {
        $data = $this->where("`use` = 1")->field("place,ad")->findAll();
		if($data){
			foreach($data as $k=>$v){
				$ad[$v["place"]] = stripslashes($v["ad"]);
			}
		}
        return $ad;
    }
}
?>