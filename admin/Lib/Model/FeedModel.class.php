<?php
import('AdvModel');
class FeedModel extends AdvModel
{
	var $table_name = 'feed';
	public function delFeed($id){
		$map['id'] = $id;
		if($this->where($map)->find()){
			return $this->where($map)->delete();
		}else{
			return false;
		}
	}
	
}
?>