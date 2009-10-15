<?php
/**
* IndexAction
* 礼品应用的后台操作
*
* @package default
* @version $id$
* @copyright 2009-2011 水上铁
* @author 水上铁 <wxm201411@163.com>
* @license PHP Version 5.2 {@link www.sampeng.cn}
*/
class AdminAction extends Administrator
{
	var $category;   //礼品类型模型
	var $gift;       //礼品模型
	
	/**
	 * 构造函数
	 *
	 * 获取礼品模型，礼品类型模型
	 * 获取礼品配置信息并赋值给模板
	 */	
	function _initialize(){
		$this->category = D('GiftCategory');
		$this->gift = D('Gift');

		$config = D('AppConfig')->getConfig();
		$this->assign('config',$config);
	}


	/**
	 * 礼物中心
	 *
	 */
	function index() {

		$categoryId = intval($_GET['categoryId']);
		if($categoryId){
			$map['categoryId'] = $categoryId;
		}
		
		$list = $this->gift->where($map)->order('id desc')->findPage(10);

		$this->assign("list",$list);
		
        $types = $this->api->CreditSetting_getCreditType();	
        $this->assign("types",$types);
        	
		$this->display();
	}
	/*
	*编辑礼物分类
	*/
	function edit()
	{
		$id = (int)$_REQUEST['id'];
		$vo = $this->gift->find($id);
		if(!$vo)
		{
			$this->error("无法找到对象!");
			return;
		}
		$catagorys = $this->category->findAll();
		$this->assign('categorys',$catagorys);

		$this->assign("vo",$vo);
		$this->display();
	}
	/*
	*显示增加礼品页面
	*/
	function add() {
		$catagorys = $this->category->findAll();
		$this->assign('categorys',$catagorys);
		$this->display();
	}
	/*
	*增加礼品到数据库
	*/
	function insert()
	{
		$path	= 'Tpl/default/Public/gift/';

		$info	=	$this->_upload($path);
		if(!is_array($info))$this->error("上传出错! ".$info);
		if($info&&is_array($info)){
			$imgname = $info[0]['savename'];
			$model	=	$this->gift;
			if(false === $model->create()) {
				$this->error($model->getError());
			}
			//保存当前数据对象
			$model->img = $imgname;
			$model->cTime = time();
			$id = $model->add();
			if($id) { //保存成功
				//成功提示
				$this->success(L('_INSERT_SUCCESS_'));
			}else {
				//失败提示
				$this->error(L('_INSERT_FAIL_'));
			}
		}
	}
	/*
	*更新礼品到数据库
	*/
	function update()
	{
		$path	= 'Tpl/default/Public/gift/';

		$info	=	$this->_upload($path);
			if($info && is_array($info)){
				$imgname	=	$info[0]['savename'];
			}
			$model	=	$this->gift;
			if(false === $model->create()) {
				$this->error($model->getError());
			}
			//保存当前数据对象
			if($imgname){
				$model->img = $imgname;
			}
			$id = $model->save();
			if($id) { //保存成功
				//成功提示
				$this->success(L('_UPDATE_SUCCESS_'));
			}else {
				//失败提示
				$this->error(L('_UPDATE_FAIL_'));
			}
		//}
	}



	/**
	 +----------------------------------------------------------
	 * 默认删除操作
	 */
	public function delete()
	{
		//删除指定记录
		$model = $this->gift;
		if(!empty($model)) {
			$id = $_REQUEST['id'];
			if(isset($id)) {
				if($model->delete($id)){
					$this->assign("message",'删除成功！');
					$vo = $model->find($_GET['id']);
					$this->assign("jumpUrl",$this->getReturnUrl());

				}else {
					$this->error("删除失败!");
				}
			}else {
				$this->error('非法操作');
			}
			$this->forward();
		}
	}

	/**
	 +----------------------------------------------------------
	 * 默认锁定操作
	 *
	 */
	function forbid()
	{
		$model	=	$this->gift;
		$condition = 'id IN ('.$_GET['id'].')';
		if($model->forbid($condition)){
			$this->assign("message",'状态禁用成功！');
			$vo = $model->find($_GET['id']);
			$this->assign("jumpUrl",$this->getReturnUrl());
		}else {
			$this->assign('error',  '状态禁用失败！');
		}
		$this->forward();
	}


	/**
	 +----------------------------------------------------------
	 * 默认恢复操作
	 */
	function resume()
	{
		//恢复指定记录
		$model	=	$this->gift;
		$condition = 'id IN ('.$_GET['id'].')';
		if($model->resume($condition)){
			$this->assign("message",'状态恢复成功！');
			$vo = $model->find($_GET['id']);
			$this->assign("jumpUrl",$this->getReturnUrl());
		}else {
			$this->assign('error',  '状态恢复失败！');
		}
		$this->forward();
	}
	/*
	*获取返回地址
	*/
	function getReturnUrl()
	{
		return base64_decode($_REQUEST['returnUrl']);
	}
	/*
	*执行单图上传操作
	*/	
	protected function _upload($path,$save_name,$is_replace,$is_thumb,$thumb_name,$thumb_max_width) {

		if(!checkDir($path)){
			return '目录创建失败: '.$path;
		}

		import("ORG.Net.UploadFile");

        $upload = new UploadFile();

        //设置上传文件大小
        $upload->maxSize	=	'2000000' ;

        //设置上传文件类型
        $upload->allowExts	=	explode(',',strtolower('jpg,gif,png,jpeg,bmp'));

		//存储规则
		$upload->saveRule	=	'uniqid';
		//设置上传路径
		$upload->savePath	=	$path;
        //保存的名字
        $upload->saveName   =   $save_name;
        //是否缩略图
        $upload->thumb          =   $is_thumb;
        $upload->thumbMaxWidth  =   $thumb_max_width;
        $upload->thumbFile      =   $thumb_name;

        //存在是否覆盖
        $upload->uploadReplace  =   $is_replace;
        //执行上传操作
        if(!$upload->upload()) {
            //捕获上传异常
            return $upload->getErrorMsg();
        }else{
			//上传成功
			return $upload->getUploadFileInfo();
    	}
    }
	/*
	*更新礼品配置信息
	*/	   
	function doEditConfig(){
		$types = $this->api->CreditSetting_getCreditType();	
		foreach($_POST as $k=>$v){
			$credit = t($v);
			$map['creditType']	=	$credit;
			$map['creditName']  =   $types[$credit];
		}

		$result	=	D('AppConfig')->editConfig($map);
		
		if($result){
			$this->success('更新成功!');
		}else{
			$this->error('更新失败!');
		}
	}    
}
?>