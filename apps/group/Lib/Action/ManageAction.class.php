<?php

class ManageAction extends BaseAction {
	var $group;
	public function _initialize(){
		
		$this->group = D('Group');
		parent::_initialize();
		//$this->assign('current','member');
		if(!isadmin($this->mid,$this->gid)) { $this->error('你没有权限进行管理');}
		
	}
	
	//基本设置  修改
	function index() {
		//管理员权权限判读
		
		if(isset($_POST['editsubmit'])) {
			if(empty($_POST['name']) || strlen($_POST['name']) > 60 || empty($_POST['intro'])) {  //判断条件
				redirect( __URL__);	
			}
			
			$groupinfo = $this->group->create();
			$groupinfo['name'] = t($_POST['name']);
			$groupinfo['intro'] = t($_POST['intro']);
			
			if($_FILES['logo']['size'] > 0) {
		   		//判读类型
		   		
			    $info		=	$this->api->attach_upload('photo');
			   
			    if($info['status'])$groupinfo['logo'] = $info['info'][0]['savepath'].$info['info'][0]['savename'];
		    }
		   
			
			$success = $this->group->where('id='.$this->gid)->save($groupinfo);
			$this->assign('success',$success);
			$this->assign('groupinfo',$this->group->where('id='.$this->gid)->find());
		}
		
		$this->assign('groupinfo',$this->group->where('id='.$this->gid)->find());
		$this->assign('current','basic');
		
		$this->display();
	}
	
	//访问权限
	function privacy(){
		if(!iscreater($this->mid,$this->gid)) $this->error('创建者才有的权限');  //创建者才可以修改配置
		if(isset($_POST['editsubmit'])){
			$groupinfo = $this->group->create();
			if(!$_POST['isInvite']) {
				$groupinfo['need_invite'] = 0;
			}
			$success = $this->group->where('id='.$this->gid)->save($groupinfo);
			$this->assign('success',$success);
			$this->assign('groupinfo',$this->group->where('id='.$this->gid)->find());
		}
		$this->assign('current','privacy');
		$this->display();
	}

	//其他权限
	function otherpriv() {
		if(isset($_POST['editsubmit'])){
			$groupinfo = $this->group->create();                //获取表单数据
			
			$success = $this->group->where('id='.$this->gid)->save($groupinfo);     //保存数据
			$this->assign('success',$success);
			$this->assign('groupinfo',$this->group->where('id='.$this->gid)->find());
		}
		$this->assign('current','otherpriv');
		$this->display();
	}

	//成员管理
	function membermanage() {
		$cond = ' AND level>0';
		$type = isset($_GET['type']) && $_GET['type'] == 'apply' ? $_GET['type'] : '';
		if($type) {
			$cond = ' AND level=0';    // 用户默认级别是0 ，没有通过审核
		}
		$where = "gid={$this->gid} $cond";
		$memberlist = D('Member')->where($where)->findPage();
		
		$this->assign('memberlist',$memberlist);
		$this->assign('current','membermanage');
		$this->assign('type', $type);
		
		$this->assign('groupinfo',$this->group->where('id='.$this->gid)->find());
		if($type == 'apply') {
			$this->display('memberapply');
			exit;
		}else{
			$this->display();
		}
		
	}
	
	//操作：设置成管理员，降级成为普通会员，剔除会员，允许成为会员
	function memberaction() {
		$uid = isset($_POST['uid']) && intval($_POST['uid']) > 0 ? intval($_POST['uid']) : 0;  //获取要设置管理员用户id
		if(!isset($_POST['op']) || !in_array($_POST['op'],array('admin','normal','out','allow'))) exit();
		
		switch($_POST['op'])
		{
			case 'admin':  //设置成管理员
				$content = '将用户'."<a href='__TS__/space/{$uid}'>".getUserName($uid)."</a>".'提升为管理员 ';
				D('Member')->where('gid='.$this->gid.' AND uid='.$uid)->setField('level',2);   //3 普通用户 
				break;
			case 'normal':   //降级成为普通会员
				$content = '将用户'."<a href='__TS__/space/{$uid}'>".getUserName($uid)."</a>".'降为普通会员 ';
				D('Member')->where('gid='.$this->gid.' AND uid='.$uid)->setField('level',3);   //3 普通用户 
				break;
			case 'out':     //剔除会员
				$content = '将用户'."<a href='__TS__/space/{$uid}'>".getUserName($uid)."</a>".'剔除群组 ';
				D('Member')->where('gid='.$this->gid.' AND uid='.$uid)->delete();   //剔除用户 
				break;
			case 'allow':   //批准成为会员
				$content = '将用户'."<a href='__TS__/space/{$uid}'>".getUserName($uid)."</a>".'批准成为会员 ';
				D('Member')->where('gid='.$this->gid.' AND uid='.$uid)->setField('level',3);   //level级别由0 变成 3；
				D('Group')->setInc('membercount','id='.$this->gid); //增加一个用户
				break;
		}
		
		D('Log')->writeLog($this->gid,$this->mid,$content,$type='member');
		
		$this->redirect('/Manage/membermanage/gid/'.$this->gid);
	}
	
	
	//群公告
	function announce() {
		
		if(isset($_POST['editsubmit'])){
			$groupinfo = $this->group->create();                //获取表单数据
			
			$announce = t($_POST['announce']);
			$success = $this->group->where('id='.$this->gid)->save($groupinfo);     //保存数据
			
			if(empty($announce)) {
				$content = '清除了公告';
			}else{
				$content = ' 发布公告：'.$announce;
			}
			 D('Log')->writeLog($this->gid,$this->mid,$content,$type='setting');
			
			$this->assign('success',$success);
		}
		$this->assign('groupinfo',$this->group->where('id='.$this->gid)->find());
		$this->assign('current','announce');
		$this->display();
	}
	
}


?>