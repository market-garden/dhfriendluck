<?php
/**
* AdminAction
* 分享应用的后台操作
*
* @package default
* @version $id$
* @copyright 2009-2011 水上铁
* @author 水上铁 <wxm201411@163.com>
* @license PHP Version 5.2 {@link www.sampeng.cn}
*/
class AdminAction extends Administrator {
	/**
    *model
    * 用于记录分享内容表的模型
    *
    * @var string
    * @access public
    */
    public $model = "" ;

	/**
    *Cmodel
    * 用于记录分享分类表的模型
    *
    * @var string
    * @access public
    */
    public $Cmodel = "" ;

	/**
    *mid
    * 当前登录用户ID
    *
    * @var string
    * @access public
    */
    public $mid = "" ;

	/**
    *uid
    * 被浏览用户ID
    *
    * @var string
    * @access public
    */
    public $uid = "" ;

    
    public $types;	
	/**
    * _initialize
    * 初始化函数
    *
    * 初始化数据模型,用户ID
    * @param string $aArgs 参数说明
    * @access public
    * @return void
    */
	public function _initialize(){
		$this->model = D('Share');
		$this->Cmodel = D('ShareType');

		$this->mid = $this->api->user_getLoggedInUser();
		 
		$this->uid = $_REQUEST['uid'];
		if(empty($this->uid)){
			$this->uid = $this->mid;
		}
		
		$this->assign('mid',$this->mid);
		//后台的操作全部刷新类型缓存.
		$this->types = type_cache();
	}

	/**
    * index
    * 分享主页面
    *
    * @param void
    * @access public
    * @return void
    */
	public function index(){
		$config = D('AppConfig')->getConfig();

		$this->assign('config',$config);
		$this->display();
	}
	
	function type(){

		$types = $this->Cmodel->order('sort asc')->findAll();
		$this->assign('types',$types);
		
		$this->display();		
	}
	function edit_type(){
        $id = intval($_GET['id']);
		$type = $this->Cmodel->where('id='.$id)->find();
		if($type){
			$this->assign('vo',$type);
			$this->display();
		}else {
			$this->error('非法操作!');
		}		
	}
	function ShareList(){
		/**
        * 构造查询条件
        */
		$search = array();
		$where = "isDel != 1";

		if(!empty($_POST['toUid'])){
			$where .= " AND toUid = '".$_POST['toUid']."'";
			$search['toUid'] = $_POST['toUid'];
		}
		if(!empty($_POST['typeId'])){
			$where .= " AND typeId = '".$_POST['typeId']."'";
			$search['typeId'] = $_POST['typeId'];
		}		
		if(!empty($_POST['toUserName'])){
			$where .= " AND toUserName like '%".$_POST['toUserName']."%'";
			$search['toUserName'] = $_POST['toUserName'];
		}
		if(!empty($_POST['title'])){
			$where .= " AND title like '%".$_POST['title']."%'";
			$search['title'] = $_POST['title'];
		}
		if(!empty($_POST['sorder'])&&!empty($_POST['eorder'])){
			$order = $_POST['sorder'].' '.$_POST['eorder'];
			$search['sorder'] = $_POST['sorder'];
		}else{
			$order = 'cTime DESC';
		}
		if(!empty($_POST['limit'])){
			$limit = $_POST['limit'];
			$search['limit'] = $_POST['limit'];
		}else{
			$limit = 30;
		}
		$this->assign('search',$search);
		/**
        * 取出分享列表
        */

		$list = $this->model->where($where)->order($order)->findPage($limit);
		/**
        * 取出分类列表
        */
		$types = $this->Cmodel->order('sort asc')->findAll();

		$content = $list['data'];
		foreach ($content as $k=>$v){			
			$data = unserialize($v['data']);

			foreach ($data as $key=>$item){
				$content[$k]['data_'.$key] = $item;
			}
			unset($content[$k]['data']);
		}

		$this->assign('list',$list);
		$this->assign('content',$content);
		$this->assign('types',$types);
		/**
        * 输出页面
        */
		$this->display();
	}
	
	function doAddType(){		
		$map['title'] = $_POST['title'];
		$map['alias'] = $_POST['alias'];
		$map['sort'] = $_POST['sort'];
		$map['temp_list'] = stripcslashes($_POST['temp_list']);
			
		$result = $this->Cmodel->add($map);

		if($result){
			$this->success('增加成功!');
		}else{
			$this->error('增加失败!');
		}

	}
	
	function doEditTypes(){		
		$title = $_POST['title'];
		$alias = $_POST['alias'];
		$sort = $_POST['sort'];
		
		foreach($_POST['id'] as $v){
			if(empty($v)){
				continue;
			}
			$map['title'] = $title[$v];
			$map['alias'] = $alias[$v];
			$map['sort'] = $sort[$v];

			$map['id'] = $v;
			
			$result = $this->Cmodel->save($map);
		}

		if($result){
			$this->types = type_cache();
			$this->success('更新成功!');
		}else{
			$this->error('更新失败!');
		}
	}
	
	function doEditType(){		

		$map['temp_list'] = stripcslashes($_POST['temp_list']);
		$map['id'] = intval($_POST['id']);

		$result = $this->Cmodel->save($map);
		
		if($result){
			$this->success('更新成功!');
		}else{
			$this->error('更新失败!');
		}
	}	
	/**
    * delete
    * 删除分享
    *
    * @param id  分享ID
    * @access public
    * @return void
    */
	function doDelType() {
		$id	=	$_REQUEST['id'];
		
		$ids = explode(',',$id);

		$result	=	$this->Cmodel->where(" id IN (" . join(",", $ids) . ")")->delete();

		if($result){
			$this->success('删除成功!');
		}else{
			$this->error('删除失败！');
		}
	}
	
	/**
    * delete
    * 删除分享
    *
    * @param id  分享ID
    * @access public
    * @return void
    */
	function delete() {
		$id	=	$_REQUEST['id'];

		$ids = explode(',',$id);
		$where = " id IN (" . join(",", $ids) . ")";
		if(getC('isDel')||isset($_GET['delSure'])){
			$result	= $this->model->where($where)->delete();
		}else{
			$result	= $this->model->where($where)->setField('isDel','1');
		}		

		if($result){
			$this->success('删除成功!');
		}else{
			$this->error('删除失败！');
		}
	}
	
	function doEditConfig(){
		foreach($_POST as $k=>$v){
			$map[$k]	=	t($v);
		}
		$result	=	D('AppConfig')->editConfig($map);
		
		if($result){
			$this->success('更新成功!');
		}else{
			$this->error('更新失败!');
		}
	}
	
	function recycle(){
		/**
        * 构造查询条件
        */
		$search = array();
		$where = "isDel = 1";

		if(!empty($_POST['toUid'])){
			$where .= " AND toUid = '".$_POST['toUid']."'";
			$search['toUid'] = $_POST['toUid'];
		}
		if(!empty($_POST['typeId'])){
			$where .= " AND typeId = '".$_POST['typeId']."'";
			$search['typeId'] = $_POST['typeId'];
		}		
		if(!empty($_POST['toUserName'])){
			$where .= " AND toUserName like '%".$_POST['toUserName']."%'";
			$search['toUserName'] = $_POST['toUserName'];
		}
		if(!empty($_POST['title'])){
			$where .= " AND title like '%".$_POST['title']."%'";
			$search['title'] = $_POST['title'];
		}
		if(!empty($_POST['sorder'])&&!empty($_POST['eorder'])){
			$order = $_POST['sorder'].' '.$_POST['eorder'];
			$search['sorder'] = $_POST['sorder'];
		}else{
			$order = 'cTime DESC';
		}
		if(!empty($_POST['limit'])){
			$limit = $_POST['limit'];
			$search['limit'] = $_POST['limit'];
		}else{
			$limit = 30;
		}
		$this->assign('search',$search);
		/**
        * 取出分享列表
        */

		$list = $this->model->where($where)->order($order)->findPage($limit);
		/**
        * 取出分类列表
        */
		$types = $this->Cmodel->order('sort asc')->findAll();

		$content = $list['data'];
		foreach ($content as $k=>$v){			
			$data = unserialize($v['data']);

			foreach ($data as $key=>$item){
				$content[$k]['data_'.$key] = $item;
			}
			unset($content[$k]['data']);
		}

		$this->assign('list',$list);
		$this->assign('content',$content);
		$this->assign('types',$types);
		/**
        * 输出页面
        */
		$this->display();		
	}
	
	function revert() {
		$id	=	$_REQUEST['id'];

		$ids = explode(',',$id);
		$where = " id IN (" . join(",", $ids) . ")";
		
		$result	= $this->model->where($where)->setField('isDel','0');

		if($result){
			$this->success('还原成功!');
		}else{
			$this->error('还原失败！');
		}
	}
	
	function set0(){
		$id = intval($_GET['id']);
		$field = $_GET['field'];
		
		$result = $this->Cmodel->where('id='.$id)->setField($field,'0');
		if($result){
			$this->success('操作成功!');
		}else {
			$this->error('操作失败2!');
		}
	}
	
	function set1(){
		$id = intval($_GET['id']);
		$field = $_GET['field'];
		
		$result = $this->Cmodel->where('id='.$id)->setField($field,'1');
		if($result){
			$this->success('操作成功!');
		}else {
			$this->error('操作失败2!');
		}
	}
	
	function upset(){
		$types = $this->types;

		$id = intval($_GET['id']);
		$sort = $types[$id]['sort']-1;
		foreach ($types as $k=>$v){
			if($v['sort']==$sort){
				$upid = $k;
			}
		}

		if($sort>0){             
             $res = $this->Cmodel->where('id='.$upid)->setField('sort',$types[$id]['sort']);

             if($res){
             	$result = $this->Cmodel->where('id='.$id)->setField('sort',$sort);

             }else{
             	$this->error('操作失败1!');
             }

             if($result){
             	$this->success('操作成功!');
             }else {
             	$this->error('操作失败2!');
             }
		}else{
			$this->error('已经是最上层!');
		}
	}
	
	function downset(){
		$types = $this->types;
		$id = intval($_GET['id']);
		$downid = $id+1;
		$maxNum = count($types);

		if($types[$id]['sort']<$maxNum&&isset($types[$downid]['sort'])){
             $sort = $types[$downid]['sort'];
             $types[$downid]['sort'] = $types[$id]['sort'];
             $types[$id]['sort'] = $sort;
             
             $this->Cmodel->where("id='$downid'")->setField('sort',$types[$downid]['sort']);
             dump($this->Cmodel->getLastSql());
             if($res){
             	$result = $this->Cmodel->where('id='.$id)->setField('sort',$types[$id]['sort']);
             }else{
             	$this->error('操作失败1!');
             }
             if($result){
             	$this->success('操作成功!');
             }else {
             	$this->error('操作失败2!');
             }
		}		
	}
    //清除垃圾
	function RemoveRefuse(){
		$list = $this->model->findAll();
		foreach ($list as $k=>$v){
			if($v['typeId']==14){  //图片
				$data = unserialize($v['data']);
				$img = $data['url'];			
				$img = explode('data/share/',$img);				
				if(isset($img[1])){					
					$imgList[] = 'data/share/'.$img[1];
				}
			}elseif($v['typeId']==10){  //用户
				$userList[] = $v['aimId'];
			}
		}
		
		//取服务器上的全部图片
		$path = 'data/share';
		$imgs = $this->_get_all_files($path);
		
		//比较图片是否要删除

		$diff = array_diff($imgs,$imgList);
		$dir = SITE_PATH.'/apps/share/';
		//dump(dirname(__FILE__));
		foreach ($diff as $v){
			$dir2 = $dir.$v;
			//dump($dir2);
			unset($dir2);
		}
		//dump($diff);
		//用户处理
		$users = $this->api->user_getInfo($userList,'id');
		foreach ($users as $k=>$v){
			$arr[] = $v['id'];
		}
		
		$diff = array_diff($userList,$arr);
        if(!empty($diff)){
        	$uids = explode(',',$diff);
        	$where = 'typeId=10 ADN aimId IN ('.$uids.')';
        	$this->model->where($where)->delete();
        }
        
        $this->success('分享垃圾已经清空!');
	}
	
	function _get_all_files($path) {
		static $list;
		foreach(glob($path . '/*') as $item) {
			if (is_dir($item)) {
				$this->_get_all_files($item);
			} else {
				$list[] = $item;
			}
		}

		return $list;
	}
}
?>