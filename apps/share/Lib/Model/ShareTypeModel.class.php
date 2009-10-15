<?php
class ShareTypeModel extends Model
{
	var $tableName = 'share_type';
	
	function getDataTemp($share){
        $typeId = $share['typeId'];
        
		$cache = ts_cache('share_types');
		if(empty($cache)){
			$cache = type_cache();			
		}
		$temp = $cache[$typeId]['temp_list'];
		
		$data = unserialize($share['data']);
		$list_data = array_merge($share,$data);

		unset($list_data['data']);

        //dump($share);
		if($temp){
			foreach($list_data as $k=>$v){   //替换	
				$v = h($v);			
				$temp = str_replace('{'.$k.'}',$v,$temp);
			}
			$temp = str_replace('{WR}',SITE_URL,$temp);
			$temp = str_replace('{SITE_URL}',SITE_URL,$temp);
			return $temp;
		}else{
			return '';
		}
	}
}
?>