<?php

class DirModel extends BaseModel {
	var $tableName = 'group_attachement';
	
	
	//删除文件
	function delfile($id) {
		$id = intval($id);
		$fileInfo = $this->where('id='.$id)->find();
		if(empty($fileInfo)) return false;
		@unlink($fileInfo['fileurl']);        //删除文件	
		return $this->where('id='.$id)->delete();
	}
	
	
	//获取文件
	/**
         * getGroupList 
    
         */
        public function getFileList($html=1,$map = null,$fields=null,$order = null,$limit = null,$isDel=0) {
            //处理where条件
            if(!$isDel)$map[] = 'is_del=0';
            else $map[] = 'is_del=1';
            
   			$map = implode(' AND ',$map);
            //连贯查询.获得数据集
            $result         = $this->where( $map )->field( $fields )->order( $order )->findPage( $limit) ;
          
            if($html) return $result;
            return $result['data'];

        }
        
      //回收站 文件，包括附件
      function remove($id) {
      	
      	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
      	
      	$result = D('Dir')->setField('is_del',1,'id IN'.$id);  //文件回收  
      	                                           
      	if($result){
      		$attachIds = array();
      		$files = $this->field('attachId')->where('id IN'.$id)->findAll();
      		foreach($files as $k=>$v){
      			$attachIds[] = $v['attachId'];
      		}
      		if($attachIds) D('Attach')->removeAttach($attachIds);
      		return true;
      	}
      	return false;
      }
      
      //删除文件
      function del($id) {
      	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
   		
      	return D('Dir')->where('id IN'.$id)->delete();  //文件回收  删除文件unlink 
      }
      
      //恢复文件
      function recover($id){
      	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
      	D('Dir')->setField('is_del',0,'id IN'.$id);
      }
}


?>