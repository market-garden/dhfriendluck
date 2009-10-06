<?php
class AttachModel extends Model{
	
	//删除附件记录
	function removeAttach($attachIds,$delFile=false) {
		
		//解析ID成数组
		if(empty($attachIds)) return false;
		
		if(!is_array($attachIds)){
			$attachIds	=	explode(',',$attachIds);
		}
		
		$map['id']	=	array('in',$attachIds);
		
		//在应用中只能标记删除附件，需要在后台进行清理
		if($delFile){
			return false;
		}else{
			
			$save['isDel']	=	1;
			$result	=	D('Attach')->where($map)->save($save);
			
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
}
?>