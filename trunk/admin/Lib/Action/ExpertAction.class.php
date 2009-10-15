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

/**
 +------------------------------------------------------------------------------
 * ThinkSnS 后台权限配置
 +------------------------------------------------------------------------------
 * @Author: Nonant <nonant@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class ExpertAction extends BaseAction{
	
	var $opts;
	public function _initialize() {
		parent::_initialize();
		$this->opts = $this->api->option_get();  
	}
	
	//社区网站
	public function network(){
		$intPid = intval($_GET['pid']);
		$pNetwork = D('Network');
		if($intPid){
			$pidInfo = $pNetwork->find($intPid);
			$pid = $intPid;
			$pidName = $pidInfo['title'];
			$prepid  = $pidInfo['pid'];
		}else{
			$pid = 0;
			$pidName = '顶级分类';
		}
		$this->assign('prepid',$prepid);
		$this->assign('pid',$pid);
		$this->assign('pidName',$pidName);
		$list = $pNetwork->where("pid=".$intPid)->findPage(20);
		
	    $this->assign('pages',$list['html']);
	    $this->assign('list',$list['data']);
	    $this->assign('count',$list['count']);		
		$this->display();
	}
	
	public function donetwork(){
		$pNetwork = D('Network');
		$arrId = $_POST['id'];
		switch ($_POST['type']){
			case 'add':
				$intPid = intval($_POST['pid']);
				$strTitle = h($_POST['title']);
				$map['pid']   = $intPid;
				$map['title'] = $strTitle;
				if(0!=$pNetwork->where($map)->count()){
					$this->error('地区'.$strTitle.'已经存在');
					exit;
				}
				
				if($strTitle){
					$pNetwork->create();
					$pNetwork->pid   = $intPid;
					$pNetwork->title = $strTitle;
					$pNetwork->add();
					$this->success('添加地区'.$strTitle.'成功');
				}else{
					$this->error('地区名不能为空');
				}
			break;
			
			case 'edit':
				$data = array(
					'title' => h($_POST['title']),
					'status' => h($_POST['status']),
				);
				if($pNetwork->where('id='.intval($_POST['id']))->save($data)){
					echo '1';
				}else{
					echo '0';
				}
			break;
			
			case 'del':
				foreach ($arrId as $val){
					$pNetwork->delById($val);
				}
				$this->success('删除成功');;
			break;
			
			case 'commend':
				foreach ($arrId as $key=>$val){
					$pNetwork->setField('status',1,'id='.$val);
				}
				$this->success('操作完成');
			break;
			
			case 'uncommend':
				foreach ($arrId as $key=>$val){
					$pNetwork->setField('status',0,'id='.$val);
				}
				$this->success('操作完成');
			break;			
		}
	}
	
	//学校
	public function school(){
		$this->display();
	}
	//过滤词
	function wordfilter(){
		
	}
	
	//安装cnzz
	function cnzz(){
		 D("Option")->updateCache();
		//$this->opts['cnzz_id']=0;
		$cms = 'ThinkSNS';
		if(!$this->opts['cnzz_id']) {
			$step = $_REQUEST['step'] ? intval($_REQUEST['step']) : 1;
			$domain = $_POST['domain'] ? $_POST['domain'] : $_SERVER['SERVER_NAME'];
			if($step == 1 ){
				$serverName = $_SERVER['SERVER_NAME'];
				$this->assign('serverName',$serverName);
			}elseif($step == 2){
		
				if($_POST['myself_cnzz']) {
					    
						$cnzzId= intval($_POST['cnzzId']);
						$password = $_POST['password'];
						
						$type=1;
						if(!$cnzzId) $this->error('cnzzID不能为空');
						if(!$password) $this->error('密码不能为空');
           		 		
				}else{
				$url = 'http://intf.cnzz.com/user/companion/thinksns.php ';
			
				$key = md5($domain.'KslDiq5H');
		
				$url = "http://intf.cnzz.com/user/companion/thinksns.php?domain=$domain&key=$key&cms=$cms";
				
				
				$data = file_get_contents($url);
				
				
				if($data == -1){$this->error('错误的key');}
				if($data == -2 || $data == -3) {$this->error('错误的key');} 
				if($data == -4) {$this->error('插入失败');}
				if($data == -5) {$this->error('重复申请次数过多');}
				
				
				if(strpos($data,'@')){
					
					@list($cnzzId,$password) = explode('@',$data);
					$type=2;
					
				
				}else{ $this->error('安装失败');}
						
						D('Option')->where("name like '%cnzz%'")->delete();
						
						$map['name'] = 'cnzz_domain';
						$map['value'] = $domain;  //域名设置
						$map['appname'] = 'thinksns';
           		 		D('Option')->add($map);
				
						$map['name'] = 'cnzz_type';
						$map['value'] = $type;  //1 自己申请cnzz ，2 通过thinksns
						$map['appname'] = 'thinksns';
           		 		D('Option')->add($map);
           		 		
           		
				
				        $map['name'] = 'cnzz_id';
						$map['value'] = $cnzzId;
						$map['appname'] = 'thinksns';
           		 		D('Option')->add($map);
           		 		
           		 		$map['name'] = 'cnzz_password';
           		 		$map['value'] = $password;
           		 		$result = D('Option') -> add($map);
           		 		
       					if($result){ 
       						$url = __URL__."/cnzz";
							$this->assign('jumpUrl',$url);
							$this->success('安装cnzz成功');
							exit;
       					}
			}
		}
		
		}
		//更新缓存
        D("Option")->updateCache();
        
		$this->assign('cnzzId',$this->opts['cnzz_id']);
		$this->assign('password',$this->opts['cnzz_password']);
		$this->assign('type',$this->opts['cnzz_type']);
		$this->assign('domain',$this->opts['cnzz_domain']);
		
		$this->assign('step',$step);
		$this->display();
	}
	
	//删除cnzz统计
	function delCnzz(){
		
		$result = D('Option')->where("name like '%cnzz%'")->delete();
		
		D("Option")->updateCache();
		$url = __URL__."/cnzz";
	    $this->assign('jumpUrl',$url);
		if($result) {
			$this->success('删除cnzz成功');
		}else{
			$this->success('删除cnzz失败');
		}
	}
	
	
	function backup(){
		$dir = './data/database/';
		if(is_dir($dir)){
			if($dh = opendir($dir)){
				while (($filename = readdir($dh)) !== false) {
					if($filename != '.' && $filename != '..'){
						
            			if(substr($filename,strrpos($filename,'.')) == '.sql'){
            				$file = $dir.$filename;
            				$filemtime = date('Y-m-d H:i:s',filemtime($file));
            				$addtime[] = $filemtime;
            				$log[] = array(
            					'filename' => $filename,
            					'filesize' => formatsize(filesize($file)),
            					'addtime' => $filemtime,
            					'filepath' => C('SITE_URL').$file,
            				);
            			}
					}
				}
			}
		}else{
			@mk_dir($dir,0777);
		}
		
		array_multisort($addtime,SORT_ASC,$log);
		
		
		
		$tables = D('BackUp')->getTableList();
		$this->assign('tables',$tables);
		$this->assign('log',$log);
		$this->display();
	}
	
	
	//数据备份
	function dobackup(){
	
		$volume = isset($_GET['volume']) ? (intval($_GET['volume']) + 1) : 1;
		$filename = date('ymd').'_'.substr(md5(uniqid(rand())),0,10);
		$tables = array();
		
		$type = $_REQUEST['type']; 
		
		if($type == 'all'){  //备份所有数据
			$tables = D('BackUp')->getTableList();
			$tables = D('BackUp')->arraykey($tables,'Name');
		}elseif($type == 'custom'){
			
			if($_POST['setup']){
				
				$tables = empty($_REQUEST['tablecustom']) ? array() : $_REQUEST['tablecustom'];
				
				ts_cache('tablecustom',$tables);
				//放入缓存 
			}else{
				$tables = ts_cache('tablecustom');
			}
		}
		
		$filename = trim($_REQUEST['filename']) ? trim($_REQUEST['filename']) : $filename;
		$startfrom = intval($_REQUEST['startform']);
		$tableid = intval($_REQUEST['tableid']);
		$sizelimit = intval($_REQUEST['sizelimit']) ? intval($_REQUEST['sizelimit']) : 1000;
		$tablenum = count($tables);
		$filesize = $sizelimit*1000;
		$complete = true;
		$tabledump = '';
		if($tablenum == 0) $this->error('请选择备份的表');
		for(; $complete && ($tableid < $tablenum) && strlen($tabledump)+500 < $filesize; $tableid++ ){
			
			$sqlDump = D('BackUp')->sqlDumpTable($tables[$tableid], $startfrom, $filesize,strlen($tabledump),$complete);
			
			$tabledump .= $sqlDump['tabledump']; 
			$complete = $sqlDump['complete'];
			$startfrom = intval($sqlDump['startform']);
			if($complete){
				$startfrom = 0;
			}
		}
		
		
		!$complete && $tableid--;
		
		if(trim($tabledump)) {
			
			$filepath = './data/database/'.$filename."_$volume".'.sql';
			
			$fp = @fopen($filepath,'wb');
			
			if(!fwrite($fp,$tabledump)){
				//$this->error('文件目录写入失败');
			}else{
				$url = __APP__."/Expert/dobackup/filename/{$filename}/type/{$type}/sizelimit/{$sizelimit}/tableid/{$tableid}/startform/{$startfrom}/volume/{$volume}";
				file_put_contents('1.txt',$url);
				//header('Location:$ur;');
				$this->assign('jumpUrl',$url);
				$this->success("备份第{$volume}卷成功");
			}
		}else{
			$url = __APP__."/Expert/backup";
			$this->assign('jumpUrl',$url);
			$this->success("备份成功");
		}
	}
	
	
	//导入备份
	function import(){
		$filename = $_GET['filename'];
		$sqldump = '';
		$file = './data/database/'.$filename;
		
		if(file_exists($file)){
			
			$fp = @fopen($file,'rb');
			$sqldump = fread($fp,filesize($file));
			
			fclose($fp);
		}
		
		$ret = D('BackUp')->import($sqldump);
		if($ret) {
			$this->success('导入成功');
		}else{
			$this->error('导入失败');
		}
	}
	
	//删除备份文件
	function delBackUpFile(){
		$filename = $_REQUEST['filename'];
		$filename = is_array($filename) ? $filename : array($filename);
		
		foreach($filename as $file){
			$file = './data/database/'.$file;	
			file_exists($file) && @unlink($file);
		}
		$url = __APP__."/Expert/backup";
		$this->assign('jumpUrl',$url);
		$this->success('删除成功');
	}
		
	
}




?>
