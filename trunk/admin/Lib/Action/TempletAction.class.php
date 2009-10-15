<?php
// +----------------------------------------------------------------------
// | ThinkSnS
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Nonant <nonant@163.com>
// +----------------------------------------------------------------------
// $Id$

class TempletAction extends BaseAction {
	//通知模板
	function notify(){
		$intId = intval($_GET['id']);
		$pNotifyTemplate = D('NotifyTemplate');
		$list = $pNotifyTemplate->where()->order('type ASC')->findall();
		$this->assign('list',$list);
		
		if($intId) $info = $pNotifyTemplate->find($intId);
		if($info) $this->assign('info',$info);
		$this->display();
	}
	
	//添加 修改 通知模板
	function donotify(){
		$pNotifyTemplate = D('NotifyTemplate');
		if($pNotifyTemplate->create()){
			$map['type'] = h($_POST['type']);
			$data['type'] = h($_POST['type']);
			$data['type_cn'] = h($_POST['type_cn']);
			$data['deal'] = h($_POST['deal']);
			$data['title'] = h($_POST['title']);			
			$data['body'] = h($_POST['body']);
			$info = $pNotifyTemplate->where($map)->find();
			if($info){
				$pNotifyTemplate->where($map)->save($data);
			}else{
				$pNotifyTemplate->add($data);
			}
			$this->success('操作成功');;
		}else{
			$this->error($pNotifyTemplate->getError());
		}
	}
	
	//删除动态模板
	function delnotify(){
		$intId = intval($_GET['id']);
		$pNotifyTemplate = D('NotifyTemplate');
		if($pNotifyTemplate->delete($intId)){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}	
	
	//动态模板
	function feed(){
		$intId = intval($_GET['id']);
		$FeedTemplate = D('FeedTemplate');
		$list = $FeedTemplate->where()->order('type ASC')->findall();
		$this->assign('list',$list);
		
		if($intId) $info = $FeedTemplate->find($intId);
		if($info) $this->assign('info',$info);
		$this->display();		
	}
	
	//添加 修改 动态模板
	function dofeed(){
		$FeedTemplate = D('FeedTemplate');
		if($FeedTemplate->create()){
			$map['type'] = h($_POST['type']);
			$data['type'] = h($_POST['type']);
			$data['title'] = stripcslashes($_POST['title']);
			$data['body'] = stripcslashes($_POST['body']);
			$info = $FeedTemplate->where($map)->find();
			if($info){
				$FeedTemplate->where($map)->save($data);
			}else{
				$FeedTemplate->add($data);
			}
			$this->success('操作成功');;
		}else{
			$this->error($FeedTemplate->getError());
		}
	}
	
	//删除动态模板
	function delfeed(){
		$intId = intval($_GET['id']);
		$FeedTemplate = D('FeedTemplate');
		if($FeedTemplate->delete($intId)){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}
?>