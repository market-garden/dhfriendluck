<?php

class MemberAction extends BaseAction {
	
	var $member;
	public function _initialize(){
		parent::_initialize();
		$this->member = D('Member');
		$this->assign('current','member');
		
	}
	//所有成员
	function index() {
		
		$order = '';
		if(isset($_GET['order']) && $_GET['order'] == 'ctime') {
			$order = 'ctime DESC';
		}elseif($_GET['order'] == 'mtime'){
			$order = 'mtime DESC';
		}else{
			$order = 'mtime DESC';
		}
		
		$memberInfo = $this->member->order($order)->where('gid='.$this->gid." AND level>0")->findPage(21);
		
		$this->assign('memberInfo',$memberInfo);
		
		$this->setTitle($this->siteTitle['member_index']);
		$this->display();
	}
	
	

	//搜索
	function search() {
	}

}

?>