<?php
import('AdvModel');
class OptionModel extends AdvModel
{

	function getOpts4Edit() {

        $te_arr = array("allow_ips","deny_ips","verify","feed_privacy","gonggao","danxing","fuxing");

        $data = $this->where("appname='thinksns'")->findAll();
        foreach($data as $k=>$v){
            if($v["name"] != "privacy") {
               if(in_array($v["name"],$te_arr) ){
                   $opts[$v["name"]] = forDisIp($v["value"]);
               }else{
                   $opts[$v["name"]] = forTag($v["value"]);
               }

            }else{
               $opts[$v["name"]] = $v["value"];
            }
        }


		return $opts;

	}


	function updateCache() {

        $data = $this->where("appname='thinksns'")->findAll();
        foreach($data as $k=>$v){
            $opts[$v["name"]] = $v["value"];
        }
        //缓存起来
        ts_cache("site_options",$opts);


	}





}
?>
