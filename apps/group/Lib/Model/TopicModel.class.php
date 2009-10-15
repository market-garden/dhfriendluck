<?php

class TopicModel extends BaseModel {
	var $tableName = 'group_topic';
	
	
	//获取帖子
	function getThread($tid,$field='*') {
		
		$thread = $this->where('id='.$tid.' AND is_del=0')->field($field)->find();
		
		if($thread) {
			$thread['content'] =  D('Post')->getField('content','istopic=1 AND tid='.$tid);
			$thread['pid'] =  D('Post')->getField('id','istopic=1 AND tid='.$tid);
		}
		return $thread;
	}
	
	
	//获取文件
	  /**
      * getGroupList 
    
     */
     public function getTopicList($html=1,$map = null,$fields=null,$order = null,$limit = null,$isDel=0) {
            //处理where条件
            if(!$isDel)$map[] = 'is_del=0';
            else $map[] = 'is_del=1';
            
   			$map = implode(' AND ',$map);
            //连贯查询.获得数据集
            $result         = $this->where( $map )->field( $fields )->order( $order )->findPage( $limit ) ;
          
            if($html) return $result;
            return $result['data'];

     }
     
     
     //搜索
     function getSearch($keywords,$gid){
     	
     	import("ORG.Util.Page");
     
     	$sqlCount = 'SELECT count(*) as count FROM '.C('DB_PREFIX').'group_topic AS t Left Join '.C('DB_PREFIX').'group_post as p'.
     			" ON t.id=p.tid WHERE t.is_del=0 AND t.gid=$gid AND p.istopic = 1 AND (t.title like '%$keywords%' OR p.content like '%$keywords%')";
     	//echo $sqlCount;
     	$count = $this->query($sqlCount);  //显示分页总数
     
     	$p = new Page($count[0]['count'],10);
     	
     	$sql = 'SELECT * FROM '.C('DB_PREFIX').'group_topic AS t Left Join '.C('DB_PREFIX').'group_post as p'.
     			" ON t.id=p.tid WHERE t.is_del=0 AND t.gid=$gid AND p.gid = $gid AND p.istopic = 1 AND (t.title like '%$keywords%' OR p.content like '%$keywords%') LIMIT ".$p->firstRow.','.$p->listRows;
        $tList = $this->query($sql);
        
     	return array('html'=>$p->show(),'count'=>intval($count[0]['count']),'data'=>$tList);
     }
     
     //回收站
     function remove($id){
     	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
     	D('Topic')->setField('is_del',1,'id IN'.$id); //回收话题
     	D('Post')->setField('is_del',1,'tid IN'.$id); //回复
     }
     
     //删除
     function del($id) {
     	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
     	D('Topic')->where('id IN'.$id)->delete(); //删除话题
     	D('Post')->where('tid IN'.$id)->delete(); //删除回复
     }
     
     
     function recover($id){
     	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
     	D('Topic')->setField('is_del',0,'id IN'.$id); //回收话题
     	D('Post')->setField('is_del',0,'tid IN'.$id); //回复
     }
}

?>