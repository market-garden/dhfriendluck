<?php
/**
* IndexAction
* 分享应用的全部操作实现
*
* @package default
* @version $id$
* @copyright 2009-2011 水上铁
* @author 水上铁 <wxm201411@163.com>
* @license PHP Version 5.2 {@link www.sampeng.cn}
*/
class IndexAction extends BaseAction{
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
		parent::_initialize();
		$this->assign('mid',$this->mid);
	}

	/**
    * index
    * 默认入口
    *
    * @param void
    * @access public
    * @return void
    */
	public function index(){
        $this->redirect('list_friends');
	}	
	/**
    * friends
    * 朋友的分享
    *
    * @param void
    * @access public
    * @return void
    */
	public function list_friends(){
		/**
        * 通过用户过滤器取得查询条件
        */
		$where = $this->filtrate('friends');
		/**
        * 取出分享列表
        */
		$limitpage = getC('limitpage');
		$list = $this->model->where($where)->order('cTime desc')->findPage($limitpage);

		$content = $list['data'];
		foreach ($content as $k=>$v){
			$content[$k]['data'] = $this->Cmodel->getDataTemp($v);
		}

		/**
        * 输出页面显示控制变量
        */
		$types = $this->_typeClass($this->types);
        //dump($content);
		$this->assign('list',$list);
		$this->assign('content',$content);
		$this->assign('types',$types);

		/**
        * 输出页面
        */
		$this->display();
	}	
	/**
    * my
    * 我的分享
    *
    * @param void
    * @access public
    * @return void
    */
	public function list_my(){
		/**
        * 通过用户过滤器取得查询条件
        */
		$where = $this->filtrate('my');
		/**
        * 取出分享列表
        */
		$limitpage = getC('limitpage');
		$list = $this->model->where($where)->order('cTime desc')->findPage($limitpage);

		$content = $list['data'];
		foreach ($content as $k=>$v){
			$content[$k]['data'] = $this->Cmodel->getDataTemp($v);
			//控制删除按钮是否显示
			$list->data[$k]['conAdmin'] = true;
		}
		
		//归档
        $archive = $this->_Archive(10,1);
        $where = "toUid = '$this->mid' AND isDel=0";
		$total = $this->model->where($where)->count();        
        foreach ($archive as $k=>$v){
        	$time = $v['time'];
        	$month = date('m',$v['time']);
			$lastMonth = $month+1;
			$lastTime = mktime(0,0,0,$lastMonth,1,date('Y',$time));			
        	$where2 = $where." AND cTime BETWEEN '$time' AND '$lastTime'";
        	$archive[$k]['num'] = $this->model->where($where2)->count();
        }
        $this->assign('total',$total);
		$this->assign('archive',$archive);
		/**
        * 输出页面显示控制变量
        */
		$types = $this->_typeClass($this->types);

		$this->assign('list',$list);
		$this->assign('content',$content);
		$this->assign('types',$types);

		/**
        * 输出页面
        */
		$this->display();
	}
	/**
    * all
    * 大家的分享
    *
    * @param void
    * @access public
    * @return void
    */
	public function list_all(){
		/**
        * 通过用户过滤器取得查询条件
        */
		$where = $this->filtrate('all');
		/**
        * 取出分享列表
        */
		$limitpage = getC('limitpage');
		$list = $this->model->where($where)->order('cTime desc')->findPage($limitpage);
		/**
        * 取出分类列表
        */

		$content = $list['data'];
		foreach ($content as $k=>$v){
			$content[$k]['data'] = $this->Cmodel->getDataTemp($v);
            //控制删除按钮是否显示
			if($v['toUid']==$this->mid){
				$list->data[$k]['conAdmin'] = true;
			}
		}

		/**
        * 输出页面显示控制变量
        */
		$types = $this->_typeClass($this->types);
        //dump($types);
		$this->assign('list',$list);
		$this->assign('content',$content);
		
		$this->assign('types',$types);

		/**
        * 输出页面
        */
		$this->display();
	}
	/**
    * all
    * 大家的分享
    *
    * @param void
    * @access public
    * @return void
    */
	public function personal(){
		$uid = intval($_GET['uid']);
		if(empty($uid)){
			$this->error('参数错误,请重试.');
			exit;
		}
		$this->assign('uid',$uid);
		
		//归档
        $archive = $this->_Archive(10,1);
        $where = "toUid = '$uid' AND isDel=0";
		$total = $this->model->where($where)->count();        
        foreach ($archive as $k=>$v){
        	$time = $v['time'];
        	$month = date('m',$v['time']);
			$lastMonth = $month+1;
			$lastTime = mktime(0,0,0,$lastMonth,1,date('Y',$time));			
        	$where2 = $where." AND cTime BETWEEN '$time' AND '$lastTime'";
        	$archive[$k]['num'] = $this->model->where($where2)->count();
        }
        $this->assign('total',$total);
		$this->assign('archive',$archive);
		//dump($archive);
		/**
        * 通过用户过滤器取得查询条件
        */
		$where = $this->filtrate('personal');
		/**
        * 取出分享列表
        */
		$limitpage = getC('limitpage');
		$list = $this->model->where($where)->order('cTime desc')->findPage($limitpage);

		$content = $list['data'];
		foreach ($content as $k=>$v){			
			$content[$k]['data'] = $this->Cmodel->getDataTemp($v);
					
			if($v['uid']==$this->mid){
				$list->data[$k]['conAdmin'] = true;
			}
		}

		/**
        * 输出页面显示控制变量
        */
		$types = $this->_typeClass($this->types);
		//dump($types);
        //dump($content);
		$this->assign('list',$list);
		$this->assign('content',$content);
		$this->assign('types',$types);
		/**
        * 输出页面
        */
		$this->display();
	}

	/**
    * content
    * 分享详细内容
    *
    * @param void
    * @access public
    * @return void
    */
	function content(){
		/**
		 * 取出分享信息
		 */
		$map['id'] = intval($_GET['id']);
		$map['isDel'] = 0;

		$share = $this->model->where($map)->find();
		
		$this->_share_check($share);		
		/**
		 * 取得分享目标内容
		 */
		$data = unserialize($share['data']);

		foreach ($data as $key=>$item){
			$item = str_replace('{WR}',SITE_URL,$item);
			$share['data_'.$key] = str_replace('{SITE_URL}',SITE_URL,$item);			
		} 
   
		/**
		 * 下一篇,上一篇,第几篇
		 */		
        $this->_content_link($share['id'],$share['toUid']);		
		/**
		 * 累积分享,累积浏览,好友分享
		 */
        $this->_content_count($share);
		/**
		 * 增加积分
		 */
		$this->_addScoure($share['toUid'],$share['id']); 
		
        $this->assign('share',$share);
		$this->display('content_'.getTypeAlias($share['typeId']));
	}
	function _share_check($share){
		if(empty($share)){
			$this->error('此分享不存在或者已经被删除！');
			exit;
		}
		$type = $this->Cmodel->find('id='.$share['typeId']);
        $purview = $this->checkPurview($share,$type);
		if(!$purview){
			$this->error('你无权限查看该分享！');
			exit;
		}		
	}
	function _content_link($id,$uid){
		//下一篇
		$where = "isDel=0 AND id > $id AND toUid = $uid";
		$next['upId'] = $this->model->where($where)->order('id ASC')->getField('id');
		if(empty($next['upId'])){
			$where = "isDel=0 AND toUid = $uid";
			$next['upId'] = $this->model->where($where)->order('id ASC')->getField('id');
		}
		
		//上一篇
		$where = "isDel=0 AND id < $id AND toUid = $uid";
		$next['downId'] = $this->model->where($where)->order('id DESC')->getField('id');
		if(empty($next['downId'])){
			$where = "isDel=0 AND toUid = $uid";
			$next['downId'] = $this->model->where($where)->order('id DESC')->getField('id');
		}
		
		//第几篇
        $next['count']    = $this->model->where( "toUid = '$uid' AND isDel = 0 " )->count();
        $next['num']      = $this->model->where( "id < '$id' AND isDel = 0 AND toUid ='$uid'" )->count()+1;

		$this->assign('next',$next);		
	}
	
	function _content_count($share){
		if(browseCount('share',$share['id'],$this->mid,$lifttime = 30)){
			$viewNum = $share['viewNum']+1;
			$this->model->where("id=$_GET[id]")->setField('viewNum',$viewNum);
		}

		$map['typeId'] = $share['typeId'];
		if(!empty($share['aimId'])){
		   $map['aimId'] = $share['aimId'];
		}else{
		   $map['url'] = $share['url'];
		}
        if(!empty($share['aimId'])||!empty($share['url'])){
        	$list = $this->model->where($map)->findAll();
        	$shareCount = count($list);
        	        	
        	foreach ($list as $v){
        		$shareView += $v['viewNum']; 
        		$shareUid[] = $v['toUid'];
        	}
        	$shareUid = array_unique($shareUid);
        	
        	$firendIds = $this->api->friend_get();
        	$fids = array_intersect($shareUid,$firendIds);

        	$this->assign('shareCount',$shareCount);
        	$this->assign('shareView',$shareView);
        	$this->assign('shareUid',$fids);
        }else{
        	$this->assign('shareCount',1);
        	$this->assign('shareView',$share['viewNum']);
        }		
	}
	/**
    * add_share
    * 分享弹窗
    *
    * 点击分享列表页或者分享内容页里的分享铵钮弹出的窗口
    * @param void
    * @access public
    * @return void
    */	
	function add_share_check(){
		$result = 1;
		$mid = $this->mid;
		$id = intval($_POST['tid']);
		
		$share = $this->model->where("id='$id' AND isDel=0")->find();
		
		if($share){
			if($share['toUid']==$mid){
				$result = -1; 
			}else{
				$where = "toUid = '$mid' AND typeId = '".$share['typeId']."' AND aimId = '".$share['aimId']."' AND isDel=0";
				$test = $this->model->where($where)->field('id')->find();
				if($test){
					$result = -1;
				}
			}
			
            if($share['typeId']=='10'&&$share['aimId']==$mid){
            	//$result = -4;
            }else{
            	$data = unserialize($share['data']);
            	if(!empty($data['uid'])&&$data['uid']==$mid){
            		$result = -2;
            	}
            }			
		}else {
			$result = -3;
		}	
		echo $result;
	}
	function add_share(){
		$id = intval($_GET['id']);
		$this->assign('id',$id);
		$this->display();
	}
	/**
    * doadd_share
    * 分享弹窗处理程序
    *
    * @param void
    * @access public
    * @return void
    */	
	function doadd_share(){
		$id = intval($_POST['id']);
		
		$share = $this->model->where("id=$id")->find();

		if($share){
			if($share['toUid']==$this->mid){
				alert('您已经分享过,请不要重复分享!');  
				exit;
			}
			
			$type['typeId'] = $share['typeId'];
			$type['typeName'] = getTypeName($share['typeId']);
			$type['alias'] = getTypeAlias($share['typeId']);

			$info = h($_POST['info']);
			$aimId = $share['aimId'];
			$data = unserialize($share['data']);
			
			$fids = $_POST["fids"];

			$result = $this->api->share_addShare($type,$share['aimId'],$data,$info,0,$fids);
            /* @return  0 失败 1 成功 -1 已经分享 -2 分类出错 -3 内容为空  -4 不能分享自己的东西*/
            echo $result;
		}else {
			echo -5;
			//alert('参数出错,分享失败!');
		}
	}

	/**
    * doAddURL
    * 手动增加网址
    *
    * @param $_POST['url']  网址
    * @access public
    * @return -1 分享的文件不存在 0 失败 1 成功
    */
	function doAddURL(){
		$url = $_POST['url'];

		$url = $this->_js_unescape($url);
        
		$typeId = $_POST['typeId'];
		
        if($typeId==14){
        	//尝试下载图片,尝试次数超过5次后放弃尝试.
        	for ($i=0; $i=5; $i++){
        		$imgpath = $this->_getUrlImg($url);
        		if($imgpath){
        			$url = SITE_URL.'/'.$imgpath;
        			break;
        		}
        	}
        }
		
		if($typeId==1){
			$title = $this->explainURL($url);
			$data['title'] = h($title);
		}	   

		$data['url'] = $url;
		//$data['urlshow'] = getSuburl($url,50);		
		
		$type['typeId'] = $typeId;
		$type['typeName'] = getTypeName($typeId);
		$type['alias'] = getTypeAlias($typeId);		
		$info = h($_POST['info']);
		//$purview = $_POST['purview'];
		$fids = $_POST["fids"];
		$result = $this->api->share_addShare($type,0,$data,$info,0,$fids);
		echo $result;
	}

	/**
    * explainURL
    * 解释网址
    *
    * @param upurl  网址 typeId 网页类型ID
    * @access public
    * @return  title 网页标题  typeName 类型名 typyImg 类型标志图 0 出错，网址不正确
    */
	function explainURL($url){
		error_reporting(0);
		for ($i=0;$i<5;$i++){
			$file = fopen ($url, "r");
			if($file) break;
		}
		

		if (!$file) {
			return '';
		}

		$title = '';
		while (!feof ($file)) {
			$line = fgets ($file, 1024);
			$line = safeEncoding($line);

			if (eregi ("<title>(.*)</title>", $line, $out)) {
				$title = $out[1];
				break;
			}
		}
		fclose($file);

		return $title;
	}
	/**
    * delAajax
    * 删除分享
    *
    * @param $_GET['id']  分享ID
    * @access public
    * @return -2 没权限删除 -1 不存在或已删除 0 失败 1 成功
    */
	function delAajax(){		
		$id = intval($_REQUEST['id']);
		$share = $this->model->find($id);

		if(empty($share)){
			echo -1;
			exit();
		}
		if($share['toUid']!=$this->mid){
			echo -2;
			exit();
		}

		if(getC('isDel')){
			$result = $this->model->delete($id);			
		}else{
			$result = $this->model->where("id='$id'")->setField('isDel','1');
		}

		echo $result;
	}
			
	/**
    * _Archive
    * 自定义归档的显示
    *
    * @param $num 显示日期数 $step 日期间隔
    * @access public
    * @return  $arr array 日期时间和日期名称
    */
	function _Archive($num='5',$step='1'){
		$chooseTime = $_GET['time'];

		$getYear = date('Y');
		$getMonth = date('m');

		for($i=0;$i<$num;$i++){
			$month = $getMonth-($i*$step);
			$time = mktime(0,0,0,$month,1,$getYear);
			$arr[$i]['time'] = $time;
			$arr[$i]['title'] = date("Y",$time).'年'.date('m',$time).'月';

			if($chooseTime==$time){
				$arr[$i]['class'] = 'on';				
			}
		}
		return $arr;
	}
	/**
    * 输出页面显示控制变量
    */	
	function _typeClass($types){
		if(!empty($_GET['typeId'])){
			$typeId = $_GET['typeId'];
			foreach ($types as $k=>$v){
				if($k==$typeId){
					$types[$k]['typeClass'] = 'on';
				}
			}
			
		}else{
			$typeClass = 'fB';
			$this->assign('typeClass',$typeClass);
		}
		
		return $types;
	}
	
	public function commentSuccess(){
		$result = json_decode(stripslashes($_POST['data']));  //json被反解析成了stdClass类型
		//计数更新
		$this->model->setInc('comNum','id='.$result->appid);

		//发送两条消息
		$data['toUid'] = $result->toUid;
		$need  = $this->model->where('id='.$result->appid)->field('toUid,title')->find();
		$data['uids'] =$need['toUid'];
		$data['url'] = sprintf('%s/Index/content/id/%s/',__APP__,$result->appid);
		$data['title_body']['comment'] = $result->comment;
		$data['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$data['url'],$need['title']);
		$data['title_data']['type'] = '分享';

		echo intval($this->api->comment_notify('share',$data,$this->appId));
	}
	
	function _getUrlImg($url){
		
		$Dir = SITE_PATH.'/data/share/'.date('Y').date('m').date('d').'/';
		
		$this->_checkDir($Dir);

		$result =  $this->_GrabImage($url,$Dir);
		//dump($result);
		$result = str_replace(SITE_PATH.'/','',$result);
		return $result;
	}
	
	//获取远程图片
	function _GrabImage($url,$Dir) {
		if($url=="")	return false;
		$url	=	urldecode($url);
		$filename	=	md5($url).strrchr($url,".");
		if(file_exists($Dir.$filename)){
			return $Dir.$filename;
		}
		
		$img = file_get_contents($url);

		if(!$img)		return false;

		$filepath	=	$Dir.$filename;
		$result	=	file_put_contents($filepath,$img);
		if($result){
			return $filepath;
		}else{
			return false;
		}
	}

	//检查并创建多级目录
	function _checkDir($path){
		$pathArray = explode('/',$path);
		$nowPath = '';
		array_pop($pathArray);
		foreach ($pathArray as $key=>$value){
			if ( ''==$value ){
				unset($pathArray[$key]);
			}else{
				if ( $key == 0 )
				$nowPath .= $value;
				else
				$nowPath .= '/'.$value;
				if ( !is_dir($nowPath) ){
					if ( !mkdir($nowPath, 0777) ) return false;
				}
			}
		}
		return true;
	}
	
	function _js_unescape($str){
		$ret = '';
		$len = strlen($str);

		for ($i = 0; $i < $len; $i++){
			if ($str[$i] == '%' && $str[$i+1] == 'u'){
				$val = hexdec(substr($str, $i+2, 4));

				if ($val < 0x7f) $ret .= chr($val);
				else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
				else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));

				$i += 5;
			}else if ($str[$i] == '%'){
				$ret .= urldecode(substr($str, $i, 3));
				$i += 2;
			}else $ret .= $str[$i];
		}
		return $ret;
	}
	
	function _addScoure($uid,$id){ 
		$isDone = browseCount('share_scoure',$id,$this->mid,$lifttime = 30);

		if($this->mid!=$uid&&$isDone){
			setScore($uid,'visit_share');
		}		
	}
}
?>