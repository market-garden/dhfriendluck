<?php

class AppManageAction extends BaseAction
{

	protected function applist($state){
		$dao = D("App");
//		switch($_GET["t"]) {
//			case "default" :  $map["status"] = 0; break;
//			case "choice"  :  $map["status"] = 1; break;
//			case "close"   :  $map["status"] = 2; break;
//		}
		if(isset($state)){
			$map["status"] = $state;
		}
		$data = $dao->where($map)->order("order2 asc")->findPage(10);
		
		foreach ($data['data'] as $key => $val) {
			$APP_URL = str_replace('http://{APPS_URL}',SITE_URL.'/apps',&$val['url']);
			$data['data'][$key]['url']         =  $APP_URL;
			$data['data'][$key]['add_url']     =  str_replace('http://{APP_URL}',$APP_URL,&$val['add_url']);
			$data['data'][$key]['icon']  =  str_replace('http://{APP_URL}',$APP_URL,&$val['icon']);
			$data['data'][$key]['uid_url']     =  str_replace('http://{APP_URL}',$APP_URL,&$val['uid_url']);
		}
		
		$data["data"] = textarea_output($data["data"]);

		$this->assign('apps',$data["data"]);
		$this->assign('page',$data["html"]);

		$this->display('index');
	}

	//所有
	public function index(){
		$this->applist();
	}

	//默认
	public function defapp(){
		$this->applist(0);
	}

	//可选
	public function choice(){
		$this->applist(1);
	}

	//关闭的
	public function close(){
		$this->applist(2);
	}

	/*
	 * 添加
	 *
	 */
	public function add()
	{
		$dao = D("App");
		import('ORG.Io.Dir');
		if($_REQUEST['appname']){ // 添加内容的页面
			$strAppname = h($_GET['appname']);
			$result = $dao->where("url='{$strAppname}'")->count();
			if($result){
				$this->error('此应用已安装过');
				exit;
			}else{
				$appConfig = require(SITE_PATH.'/apps/'.$strAppname.'/appinfo/config.php');
			};
			$this->assign('appConfig',$appConfig);
			$this->assign('selected','true');
		}else{
			$pDir = new Dir(SITE_PATH.'/apps/');
			$arrDirs = $pDir->toArray();
			$applist = $dao->findall();
			foreach ($applist as $value){
				$apps[] = $value['enname'];
			}
			foreach ($arrDirs as $value){
				if(!in_array($value['filename'],$apps)  && is_file($value['pathname'].'/appinfo/config.php')){
					$config = include($value['pathname'].'/appinfo/config.php');
					$config['icon'] = str_replace('http://{APP_URL}',SITE_URL.'/apps/'.$value['filename'],&$config['icon']);
					$appList[$value['filename']] = $config;
				}
			}
			$this->assign('appList',$appList);
		}
		$this->display();
	}

	//添加应用
	function doadd(){
		$pApp = D('App');
		$result = $pApp->where("url='".h($_POST['url'])."'")->count();
		if($result){
			$this->error('此应用已安装过');
			exit;
		}else{
			if($pApp->create()){
				if($appId = $pApp->add()){
					$installfile = SITE_PATH.'/apps/'.h($_POST['url']).'/appinfo/install.sql';
					$fp = fopen($installfile, 'rb');
					$sql = fread($fp, filesize($installfile));
					fclose($fp);
					$pApp->runquery($sql);  //运行sql
					$this->upCache();
					$this->assign("jumpUrl",__APP__.'/AppManage/add');
					$this->success('应用安装成功');
				}else{
					$this->error('应用安装失败');
				}
			}else{
				$this->error($pApp->getError());
			}
		}
	}

	/*
	 * 编辑
	 *
	 */
	public function edit()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) != "post") // 编辑页面
		{
			$Dao = D("App");
			$vo = $Dao->where("id=".$_GET["id"])->find();
			if($vo === false)
			{
				$this->assign("jumpUrl",__URL__);
				$this->error("Non-existed record");
			}
           // dump($vo);
			$this->assign("app",textarea_edit($vo));
			$this->display();
		}
		else
			$this->save("save");
	}

	/*
	 * 添加或更新数据
	 *
	 */
	function save($type="add")
	{

		//安全过滤
		$_POST = new_addslashes($_POST);
		//dump($_POST);

		// 保存添加的数据
		$Dao = D("App");
		$vo = $Dao->create();

		if(false === $vo)
	 		$this->error($Dao->getError());

		if($type=="add")
		{
			$rs = $Dao->add();
			$success = "添加应用成功!";
			$error = "添加应用失败";
		}
		else if($type=="save")
		{
			$rs = $Dao->save();
			$success = "修改应用成功!";
			$error = "修改应用失败";
		}



		if($rs)
		{
			
			$this->upCache();
			$this->assign("jumpUrl",__URL__);
			$this->success($success);
		}
		else
			$this->error($error);
	}




	/*
	 * 删除某个应用
	 *
	 */
	public function del() {
		// 根据id删除指定的记录
		$Dao = D("App");
		$id = $_REQUEST["id"];
		if(isset($id))
		{
			$result = $Dao->where("id=".$id)->find();
			if(!$result)
				$this->error("Non-existed record!");

			if($Dao->where("id=".$id)->delete())
			{
				$this->upCache();
				$this->assign("jumpUrl",__URL__);
				$this->success("删除数据成功");
			}
			else
				$this->error("删除数据失败");
		}else{
			$this->error("非法操作");
		}

	}




	/*
	 * 设置状态
	 *
	 */
	 public function setStatus() {

		 $id = intval($_GET["id"]);
		 $s  = intval($_GET["s"]);

		 if($id) {
			 $r = D("App")->setField("status",$s,"id=".$id);
			 if($r){
			 	$this->upCache();
				$this->redirect("AppManage/index/t/".$_GET["t"]);
			 }else{
				$this->error("修改状态失败s!");
			 }
		 }
	 }


     /*
     * 设置顺序
     *
     */
     function doOrder() {

         $map1["order2"] = $_POST["id1"];
         $map2["order2"] = $_POST["id2"];

         $dao = D("App");
         $app1 = $dao->where($map1)->find();
         $app2 = $dao->where($map2)->find();


         $data1["order2"] = $_POST["id2"];
         $data2["order2"] = $_POST["id1"];

         $dao->where("id=".$app1["id"])->save($data1);
         $dao->where("id=".$app2["id"])->save($data2);
		$this->upCache();
         echo 1;
         
     }
	
     protected function upCache(){
     	ts_cache('applist','');
     }

}


?>