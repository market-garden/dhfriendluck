<?php

class GroupAction extends BaseAction {

	 public function _initialize(){
	 	parent::_initialize();
	 
	 	$this->assign('current','group');  //头部导航切换
	 }

	//群首页
	function index() {

		//成员动态

		$appid = 'group_'.$this->gid;
		
		$groupFeed = D('group')->getGroupFeed($this->gid,$this->appId);
		
		
		
		
		$this->assign('groupFeed',$groupFeed);


		//群话题区
		$threadCount = D('Topic')->where('gid='.$this->gid.' AND is_del=0')->count();
		$threadList = D('Topic')->order('top DESC,replytime DESC')->where('gid='.$this->gid.' AND is_del=0')->limit(8)->findAll();
		$this->assign('threadCount', $threadCount);
		$this->assign('threadList',$threadList);



		//群相册区
		$photoCount = D('Photo')->where('gid='.$this->gid.' AND is_del=0')->count();
		$photoList = D('Photo')->getPhotoList(0,array("gid={$this->gid}"),null,null,8);
		$this->assign('photoCount', $photoCount);
		$this->assign('photoList',$photoList);


		//群文件
		$fileCount = D('Dir')->where('gid='.$this->gid.' AND is_del=0')->count();
		$fileList = D('Dir')->getFileList(false,array("gid={$this->gid}"),null,'ctime DESC',3,$isDel=0);
		$this->assign('fileCount',$fileCount);
		$this->assign('fileList',$fileList);

		//创建人和管理员
		$adminList = D('Member')->where("gid={$this->gid} AND (level=1 or level=2)")->findAll();
		$this->assign('adminList',$adminList);

		//新加入的成员
		$newJoinList = D('Member')->where('gid='.$this->gid." AND level>0")->order('ctime DESC')->limit(4)->findAll();
		$this->assign('newJoinList',$newJoinList);


		//最近访问的成员
		$recentVList = D('Member')->where('gid='.$this->gid." AND level>0")->order('mtime DESC')->limit(4)->findAll();
		$this->assign('recentVList',$recentVList);

		
		$this->setTitle($this->groupinfo['name'].'群');
		$this->display();
	}


	//加入该群
	function  joingroup() {
		
		if(isset($_POST['addsubmit'])) {
			
			$level = 0;
			$incMemberCount = false;
			if($this->groupinfo['need_verify'] == 0) {
				$level = 3;   //判读权限，如果是不需要校验，则直接成功
				$incMemberCount = ture;
				$title = '加入了群组';
			}else{  //需要审批，发送动态到管理员
        		//添加动态
				$title = '申请加入群组';
        		$toUserIds = D('Member')->where('gid='.$this->gid.' AND (level=1 or level=2)')->findAll();
        		
        		$title_data['actor'] = '请求你批准加入';
    			$title_data['title'] = $this->groupinfo['name'];
    			$body_data['gid'] = $this->gid;
    			
    

    			$toUserIds = render_in($toUserIds,'uid',1);
         		//通知
        		$cate = "notification";
        		$this->api->notify_setAppId($this->appId);
        		$this->api->notify_send($toUserIds,'group_apply',$title_data,$body_data,$url,$cate);
        	}


			$result = D('Group')->joingroup($this->mid,$this->gid,$level,$incMemberCount,$_POST['reason']);   //加入
			if($result){
				//添加动态

				$title_data["actor"] = getUserName($this->mid);
				$title_data['title'] = $title;
				
   				$body_data['gid'] = $this->gid;
   				$body_data['group_name'] = $this->groupinfo['name'];
   				
				$this->api->feed_publish('group_join',$title_data,$body_data,$this->appId,0,$this->gid);
			}


			exit($result);
		}


		$this->assign('joinCount',D('Member')->where("uid={$this->mid} AND (level=2 OR level=3)")->count());

		$this->assign('isjoin',D('Member')->where("gid={$this->gid} AND uid={$this->mid}")->count());  //判读是否加入过
		$this->display();
	}




	//退出该群
	function quitgroup() {

		if(iscreater($this->mid,$this->gid) || !isJoinGroup($this->mid,$this->gid)  ) { echo '0';exit;} //$this->error('你没有权限'); //管理员不可以退出
		$ret = D('Member')->where("uid={$this->mid} AND gid={$this->gid}")->delete();  //用户退出
		D('Group')->where("id=".$this->gid)->setDec('membercount');     //用户数量减少1
		if($ret){
			echo '1';exit;
		}
	}

	//退出该群对话框
	function quitgroup_dialog() {
		$this->assign('gid',$this->gid);
		$this->display();
	}


	//删除该群
	function delgroup() {

		if(!iscreater($this->mid,$this->gid))  exit('你没有权限');
		D('Group')->remove($this->gid);     //用户数量减少1
	}


	//删除群组对话框
	function delgroup_dialog() {

		$this->assign('gid',$this->gid);
		$this->display();
	}

	function addShare_check(){

		$result = 1;

		$aimId = intval($_REQUEST['aimId']);
		$this->assign('aimId',$aimId);		
			
		$test = $this->api->share_isForbid($this->mid,8,$aimId);

		if($test==-1){
			$result = -2;
		}	

        echo $result;
	}
	function addShare(){
		$aimId = intval($_REQUEST['aimId']);
		$this->assign('aimId',$aimId);
		$group = D('group')->where("id='$aimId'")->field('name')->find();

		$this->assign('name',$group['name']);
		$this->assign($group);
        $this->assign('mid',$this->mid);
		$this->display();
	}

	function doaddShare(){
		$type['typeId'] = 8;
		$type['typeName'] = '群组';
		$type['alias'] = 'group';

		$info = h($_REQUEST['info']);
		$aimId = intval($_REQUEST['aimId']);

		$field = 'uid,name,logo,cid0,membercount';
		$data = D('group')->where("id='$aimId'")->field($field)->find();
		$data['logo'] = get_photo_url($data['logo']);
		$data['catagory'] = D('Category')->where("id=".$data['cid0'])->getField('title');

		//$data['name'] = h($_REQUEST['name']);
		$fids = $_REQUEST['fids'];


		$result = $this->api->share_addShare($type,$aimId,$data,$info,0,$fids);
		echo $result;
	}


}

?>
