<?php
ob_start();
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

//应用管理
class AppAction extends BaseAction{

	//应用列表
	function index(){
		$dao = D("App");
		$map["status"] = 1;
		$data = $dao->where($map)->order("order2 asc")->findPage(10);
		$data["data"] = textarea_output($data["data"]);
		$this->assign('apps',$data["data"]);
		$this->assign('page',$data["html"]);
        $this->display();
	}

	//查看单个应用
	function view(){
        $intAppId = intval($_GET["view"]);
        $app = D("App")->where('id='.$intAppId.' AND status=1')->find();
        if($app && $intAppId){
        	$pUserApp = D("UserApp");
			$appData['uid']   = $this->mid;
			$appData['appid'] = $intAppId;
        	$result = $pUserApp->where($appData)->count();
        	if($result==0){
		        $this->assign("add_app",$app);
		        $this->display();
        	}else{
        		//跳转 需改进
        		header("Location: ".$this->api->App_getappinfo($intAppId,'APP_URL'));
        	}
        }else{
        	$this->error('您提交了错误参数');
        }
	}

	//载入本地应用
	function load() {
		$appid	=	intval($_REQUEST['appid']);
		$app	=	D('App')->find($appid);
		if(!$app){
			$this->error('不存在这个应用！');
		}
		//如果是iframe载入

		//如果是跳转

		$this->assign('vo',$app);
		$this->display();
	}

	//添加单个应用 如果已安装则自动跳转到应用首页
	function add(){
        $intAppId = intval($_GET["add"]); 
        $app = D("App")->where('id='.$intAppId.' AND status=1')->find();
        if($app && $intAppId){
        	$pUserApp = D("UserApp");
			$appData['uid']   = $this->mid;
			$appData['appid'] = $intAppId;
        	$result = $pUserApp->where($appData)->count();
        	if($result==0){
		        $this->assign("add_app",$app);
		        $this->display();
        	}else{
        		//跳转 需改进
        		header("Location: ".$this->api->App_getappinfo($intAppId,'APP_URL'));
        	}
        }else{
        	$this->error('您提交了错误参数');
        }
	}

	//添加操作
	function doadd(){

    	$intAppId = intval($_POST["id"]);
        $pUserApp = D("UserApp");
		$pApp = D("App");
		$appData['uid']   = $this->mid;
		$appData['appid'] = $intAppId;
		
		$appInfo = $pApp->where('id='.$intAppId.' AND status=1')->find();
		
		$result = $pUserApp->where($appData)->count();
		
        if($result==0){
        	if($pUserApp->add($appData)){
				setScore($this->mid,'add_app');
        		/**
        		 * 添加动态
        		 */
				$title_data['title'] = "<a href='{WR}/index.php?s=/App/add/{$appInfo['id']}'>".$appInfo['name']."</a>";
				$body_data['content'] = "<a href='{WR}/index.php?s=/App/view/{$appInfo['id']}'>查看应用详情</a>";
			    $this->api->feed_publish('app_add',$title_data,$body_data,$appId);
			    $this->redirect("index");
        	}
		}else{
			$this->error('您已经添加过此应用');
		}
	}


    /*
     * 删除应用
     *
     */
    function del() {
        $dao = D("UserApp");
        $data["uid"] = $this->mid;
        $data["appid"] = intval($_GET["id"]);
        if($dao->where($data)->delete()){
        	setScore($this->mid,'delete_app');
        	$this->redirect("index");
        }else{
        	$this->error('您提交了错误参数');
        }
    }
}
