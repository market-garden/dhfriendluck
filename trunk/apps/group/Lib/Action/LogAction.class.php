<?php

class LogAction extends BaseAction {
	var $log;
	 public function _initialize(){
	 	parent::_initialize();
	 	//dump($this->groupinfo);

	 	$this->log = D('Log');
	 	$this->assign('current','log');  //头部导航切换
	 }

	 //所有日志
	 function index() {

	 	$logList = $this->log->where('gid='.$this->gid)->order('id DESC')->findPage();

	 	$this->assign('on','index');
	 	$this->assign('logList',$logList);
	 	$this->display();
	 }

	 //贴子日志
	 function topic() {
	 	$logList = $this->log->where('gid='.$this->gid." AND type='topic'")->order('id DESC')->findPage();


	 	$this->assign('logList',$logList);

	 	$this->assign('on','topic');
	 	$this->display('index');
	 }

	 //成员日志
	 function member() {
	 	$logList = $this->log->where('gid='.$this->gid." AND type='member'")->order('id DESC')->findPage();


	 	$this->assign('logList',$logList);
	 	$this->assign('on','member');
	 	$this->display('index');
	 }

	 //设置日志

	 function setting() {

	 	$logList = $this->log->where('gid='.$this->gid." AND type='setting'")->order('id DESC')->findPage();


	 	$this->assign('logList',$logList);
	 	$this->assign('on','setting');
	 	$this->display('index');
	 }


}


?>