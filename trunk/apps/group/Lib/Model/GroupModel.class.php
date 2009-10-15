<?php
 Import( '@.Unit.Common' );
 
class GroupModel extends BaseModel {
	function  _initialize() {
		parent::_initialize();
		
	}
	function getapi() {
		
		//dump($this->api->app_getLeftNav());
		return $this->api;
	}
	
	
	function getMyGroup($uid,$perpage) {
		$myGroup = $this->field("id,name,logo")->where('uid='.$uid." AND is_del=0")->order('ctime desc')->findPage($perpage);
		
		
		if($myGroup['data']){
			foreach($myGroup['data'] as $k=>$v){
				$myGroup['data'][$k]['feed'] = $this->getGroupFeed($v['id']);
			}
		}
		return $myGroup;
	}
	
	
	
	//我管理的群组
	
	function mymanagegroup($mid,$html=0) {
		$gidarr = D('Member')->field('gid')->where('(level=1 OR level=2 AND status=1) AND uid='.$mid)->findAll();
	
		if($gidarr){
		    $in = 'id IN '.render_in($gidarr,'gid');
		    $groupList = D('Group')->field('id,name,type,membercount,logo,cid0,ctime')->where($in)->findPage();
		    if(!$html) return $groupList['data'];
		    return  $groupList;
		}
		return false; 
	}
	
	//我加入的群组
	function myjoingroup($mid,$html=0) {
		
		$gidarr = D('Member')->field('gid')->where('(level > 1 AND status=1)  AND uid='.$mid)->findAll();
		
		if($gidarr){
		    $in = 'id IN '.render_in($gidarr,'gid');
		    $groupList= D('Group')->field('id,name,type,membercount,logo,cid0,ctime')->where($in)->findPage();
		    if(!$html) return $groupList['data'];
		    return  $groupList;
		}
		return false; 
	}
	
	//获取我所有群组  加入审核通过的群组
	function getAllGroup($mid,$html=0){
		$gidarr = D('Member')->field('gid')->where('status=1  AND uid='.$mid)->findAll();
		
		if($gidarr){
		    $in = 'id IN '.render_in($gidarr,'gid');
		    $groupList = D('Group')->field('id,name,type,membercount,logo,cid0,ctime')->where($in)->findPage();
		    if(!$html) return $groupList['data'];
		    return  $groupList;
		}
		return false; 
	}
	
	//好友加入的群
	function friendjoingroup($mid) {
		
		import("ORG.Util.Page");
		
		$cond = '';
		$group = array();
		$friendlist = getfriendlist($mid);  //放入缓存当中
	
		if(!empty($friendlist) && is_array($friendlist)) {
			$in = 'uid IN '.render_in($friendlist,'fuid');
			
			$count = D('Member')->field('count(distinct(gid)) AS count')->where($in)->find();  //显示分页总数
			if($count['count'] == 0) return '';
			
			$p = new Page($count['count'],10);
			$friendgroup = D('Member')->field('gid')->where($in)->group('gid')->limit($p->firstRow.','.$p->listRows)->findAll();  //获取数据
			
			foreach($friendgroup as $k=>$v) {	
				$group[$v['gid']] = D('Member')->where($in." AND gid=".$v['gid'])->findAll();  //循环显示朋友
				$group[$v['gid']]['c'] = D('Member')->where($in." AND gid=".$v['gid'])->count();
			}
			return array($group,$p->show());
		}
		return false;
	}
	
	//某人加入某群组
	function joingroup($mid,$gid,$level,$incMemberCount=false,$reason='') {
		
		if(D('Member')->where("uid=$mid AND gid=$gid")->find()) exit('你已经加入过');
		
		$member['uid'] = $mid;
		$member['gid'] = $gid;
		$member['name'] = getUserName($mid);
		$member['level'] = $level;
		$member['ctime'] = time();
		$member['mtime'] = time();
		$member['reason'] = $reason;
		$ret = D('Member')->add($member);
		
	    
		
        
        if($incMemberCount)D('Group')->setInc('membercount','id='.$gid);  //不需要审批直接添加群组数量，审批就不用添加了。
        
		return $ret;

	}
	
	//最新话题
	function getnewtopic($uid) {
		$gidarr = D('Member')->field('gid')->where('uid='.$uid)->findAll();
		if($gidarr){
		    $in = 'gid IN '.render_in($gidarr,'gid');
		    return D('Topic')->where("is_del=0 AND ".$in)->order('replytime DESC')->findPage();
		}
		return false; 
	}
	
	//获取群组动态
	function getGroupFeed($gid,$appid,$pageLimit=6,$time=0) {
		$gid = is_array($gid) ? $gid : array($gid);

		return $this->api->Feed_getApp($appid,'',$pageLimit,array('group_create'),'','',array('IN',$gid),array('ctime'=>array('gt',$time)));

	}
	
	//获取我所在群组的动态
	function getMyJoinGroup($uid,$appid,$time=0) {
		$feedList = array();
		$joinGroup = D('Member')->field('gid')->where('uid='.$uid." AND level != 0 ")->findPage();
		
		if($joinGroup['data']){
			foreach($joinGroup['data'] as $k=>$v){
				$feed = $this->getGroupFeed($v['gid'],$appid,6,$time);
				if(!$feed) continue;
				
				$feedList[$k]['feed'] = $feed;
				$feedList[$k]['order'] = $feed[0]['id'];
				$sort[$k][] = $feed[0]['id'];
				$feedList[$k]['gid'] = $v['gid'];
			}
			array_multisort($sort, SORT_DESC,$feedList);
		   $joinGroup['data'] = $feedList;
		}
		return $joinGroup;
	}
	
	
	 /**
         * getGroupList 
    
         */
        public function getGroupList($html=1,$map = null,$fields=null,$order = null,$limit=null,$isDel=0) {
            //处理where条件
            if(!$isDel)$map[] = 'is_del=0';
            else $map[] = 'is_del=1';
   			$map = implode(' AND ',$map);
            //连贯查询.获得数据集
            $result         = $this->where( $map )->field( $fields )->order( $order )->findPage($limit) ;
          	
            if($html) return $result;
            return $result['data'];

        }
        
      //回收站 群组，话题，文件，相册，话题回复
      function remove($id) {
      	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
      	
      	D('Member')->where('gid IN '.$id)->delete();       //删除成员   
      	D('Group')->setField('is_del',1,'id IN'.$id);  //回收群组
      	D('Topic')->setField('is_del',1,'gid IN'.$id); //回收话题
      	D('Post')->setField('is_del',1,'gid IN'.$id);  //回收话题回复
      	D('Dir')->setField('is_del',1,'gid IN'.$id);  //文件回收
      	$dirList = D('Dir')->field('attachId')->where('gid IN'.$id)->findAll();
      
      	if($dirList){
      		$attachIds = array();
      		foreach($dirList as $k=>$v){
      			$attachIds[] = $v['attachId'];
      		}
      		
      		D('Attach')->removeAttach($attachIds);
      		unset($attachIds);
      		unset($dirList);
      	}
      	
      	
      	D('Album')->setField('is_del',1,'gid IN'.$id);   //相册回收
      	
      	D('Photo')->setField('is_del',1,'gid IN'.$id);   //图片回收
      	$photoList = D('Photo')->field('attachId')->where('gid IN'.$id)->findAll();
      	if($photoList){
      		$attachIds = array();
      		foreach($photoList as $k=>$v){
      			$attachIds[] = $v['attachId'];
      		}
      		D('Attach')->removeAttach($attachIds);
      		unset($attachIds);
      		unset($photoList);
      	}
      	                                   
      }
      
      //删除文件
      function del($id) {
      	$id = in_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
      	D('Group')->where('id IN'.$id)->delete();  //删除群组
      
      	D('Topic')->where('gid IN'.$id)->delete(); //回收话题
      	D('Post')->where('gid IN'.$id)->delete();  //回收话题回复
      	D('Dir')->where('gid IN'.$id)->delete();  //文件回收  删除文件unlink 
      	D('Album')->where('gid IN'.$id)->delete();
      	D('Photo')->where('gid IN'.$id)->delete();   //图片回收	
      }

}

?>