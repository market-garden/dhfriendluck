<?php
class OptionLwModel extends LW_Model
{

	function get() {

        $opts = ts_cache("site_options");

        if(!$opts){
			$data = $this->where("appname='thinksns'")->findAll();

			foreach($data as $k=>$v){
				$opts[$v["name"]] = $v["value"];
			}
            //缓存起来
            ts_cache("site_options",$opts);
        }

		return $opts;

	}

}
?>