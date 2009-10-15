<?php
import('AdvModel'); 
class OptionModel extends AdvModel
{

	function getOpts() {

        $opts = ts_cache("site_options");

        if(!$opts){
            $map['appname'] = "thinksns";
			$data = $this->where( $map )->findAll();
			foreach($data as $k=>$v){
				$opts[$v["name"]] = forTag($v["value"]);
			}
            //缓存起来
            ts_cache("site_options",$opts);
        }

		return $opts;

	}  

   
}
?>
