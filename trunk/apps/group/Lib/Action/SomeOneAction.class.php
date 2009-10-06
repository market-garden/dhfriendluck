<?php

//某人的群组
class SomeOneAction extends BaseAction {
	
	public function _initialize(){
		parent::base();
	}
	
	function index(){
		//加入的 
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		if($type == 'join'){
			$groupList = D('group')->myjoingroup($this->uid,$html = 1);
		}elseif($type == 'manage'){  //管理
			$groupList = D('group')->mymanagegroup($this->uid,$html = 1);
		}else{ //所有的
			$groupList = D('group')->getAllGroup($this->uid,$html = 1);
		}
		
		
		$this->assign('type',$type);
		$this->assign('grouplist',$groupList);
		$this->display();
	}
	
	
	//话题
	function topic(){
		//加入的 
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$cond = '';
		if($type == 'post'){  //发表
			$cond = ' AND istopic=1 ';	
			
		}elseif($type == 'reply'){  //回复
			$cond = ' AND istopic=0 ';
		}
		$postList = D('Post')->where('uid='.$this->uid." $cond AND is_del=0")->order('ctime DESC')->findPage();
		foreach($postList['data'] as $k=>$v){
			$postList['data'][$k] = gettopic($v['tid']);
		}
		$this->assign('type',$type);
		$this->assign('topicList',$postList);
		$this->display();
	}

}



?>