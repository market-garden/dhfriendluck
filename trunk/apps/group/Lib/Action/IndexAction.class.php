<?php

class IndexAction extends BaseAction {
	var $mid;
	var $perpage;
	var $group;
	public function _initialize(){
		parent::base();
		//$this->mid = 39;//$this->mid; 39  40 ,42
		$this->perpage = 3;
		$this->group = D('group');
	}
	function index() {

		$rows	=	10;
		//$time = intval($this->config['group_feed_time']);
		$myGroup = $this->group->getMyJoinGroup($this->mid,$this->appId,time()-7*24*60*60);

		$this->assign('mymanagegroup',$this->group->mymanagegroup($this->mid));  //我管理的群组
		$this->assign('myjoingroup',$this->group->myjoingroup($this->mid));    //我加入的群组
		$this->assign('volist',$myGroup);
		$this->setTitle($this->siteTitle['my_group']);
		$this->display();
	}



	//群的创建
	function add() {
		if($this->config['isLimit']){
			$user_create_group_count = D('Group')->where('is_del=0 AND uid='.$this->mid)->count();
			$user__post_count = D('Topic')->where('is_del=0 AND uid='.$this->mid)->count();
			$user_score = $this->api->UserScore_getScore($this->mid);

			if($this->config['createMaxGroup'] <= $user_create_group_count){
				//系统后台配置要求，如果超过，则不可以创建
				$this->error('你不可以再创建了，超过系统规定数目');
			}
			if($this->config['userScore'] >= $user_score){
				$this->error('你不可以创建群组，积分没有达到'.$this->config['userScore']);
			}
			if($this->config['userThreadCount'] >= $user__post_count){
				//系统后台配置要求，没有达到发帖子数量，不能创建群组
				$this->error('你不可以创建群组，发帖字数量不够'.$this->config['userThreadCount']);
			}

		}


		$this->setTitle($this->siteTitle['create_group']);
		$this->display();
	}

	//做创建操作
	function doAdd(){
		if(trim($_POST['dosubmit'])) {

			if(!t($_POST['name']) || strlen(t($_POST['name'])) > 60 ) {
				$this->error('标题不能为空或者标题过长！！');
			}
			if(!t($_POST['intro'])){
				$this->error('群组简介不能为空！');
			}
			$group = $this->group->create();
			$group['name'] =t($_POST['name']);
			$group['type'] = $_POST['type'];
			$group['intro'] = t($_POST['intro']);
			$group['anno'] = intval($_POST['anno']);
			$group['uid'] = $this->mid;
			$group['need_invite'] = intval($this->config[$group['type'].'_isInvite']);  //是否需要邀请
			$group['need_verify'] = intval($this->config[$group['type'].'_review']);   //申请是否需要同意
			$group['actor_level'] = intval($this->config[$group['type'].'_sayMember']);  //发表话题权限
			$group['brower_level'] = intval($this->config[$group['type'].'_viewMember']); //浏览权限
			//$group['brower_level'] = intval($this->config[$group['type'].'_viewMember']); //浏览权限


			$group['openUploadFile'] = intval($this->config['openUploadFile']);
			$group['whoUploadFile'] = intval($this->config['whoUploadFile']);
			$group['openAlbum'] = intval($this->config['openAlbum']);
			$group['whoCreateAlbum'] = intval($this->config['whoCreateAlbum']);
			$group['whoUploadPic'] = intval($this->config['whoUploadPic']);
			//exit;

			$group['ctime'] = time();



			$info		=	$this->api->attach_upload('group_logo');
		    if($info['status']) {
			    $group['logo'] = $info['info'][0]['savepath'].$info['info'][0]['savename'];
		    }else{
		    	$group['logo'] = 'default.gif';
		    }

		    $gid = $this->group->add($group);

			if($gid) {
				//把自己添加到成员里面
				$this->group->joingroup($this->mid,$gid,1,$incMemberCount=true);


				 $title_data['actor'] = getUserName($this->mid);

				 $body_data['gid'] = $gid;
				 $body_data['group_name'] = $group['name'];

   			     $this->api->feed_publish('group_create',$title_data,$body_data,$this->appId,0,$gid);


				 setScore($this->mid,'group_create');
				 //跟新空间计数
				 //$count = D('Member')->where( 'uid='.$this->mid )->count();
            	 //$this->api->space_changeCount( 'group',$count );

				redirect( __APP__."Invite/create/gid/$gid/from/create");
			}
			$this->error('创建失败');
		}
	}



	//最新话题

	function newtopic(){


		$this->assign('mymanagegroup',$this->group->mymanagegroup($this->mid));  //我管理的群组
		$this->assign('myjoingroup',$this->group->myjoingroup($this->mid));    //我加入的群组

		$this->assign('newTopic',$this->group->getnewtopic($this->mid));         //最新话题 自己加入的群组和自己创建的
		$this->setTitle($this->siteTitle['my_group_new_topic']);
		$this->display();
	}

	function allTopic(){
		$type = isset($_GET['type']) && in_array($_GET['type'],array('post','reply','collect')) ? $_GET['type'] : '';


		if($type == 'post'){ //发表的话题
			$value = D('Post')->field('tid')->order('ctime DESC')->where('istopic=1 AND is_del=0 AND uid='.$this->mid)->findPage();
			$this->setTitle($this->siteTitle['newTopic_my_post']);
		}elseif($type == 'reply'){ //回复话题
			$value = D('Post')->field('distinct(tid) as tid')->order('ctime DESC')->where('istopic=0 AND is_del=0 AND uid='.$this->mid)->findPage();
			$this->setTitle($this->siteTitle['newTopic_my_reply']);
		}elseif($type == 'collect'){
			$value = D('Collect')->order('addtime DESC')->field('tid')->where('is_del=0 AND mid='.$this->mid)->findPage();
			$this->setTitle($this->siteTitle['newTopic_my_collect']);
		}
		else{  //所有话题
			$value = D('Topic')->order('isrecom DESC,replytime DESC')->field('id as tid')->where('is_del=0')->findPage();
			$this->setTitle($this->siteTitle['newTopic_all']);
		}


		$this->assign('value',$value);
		$this->assign('type',$type);
		$this->display();
	}


	//首页发布话题
	function issue(){
		//获取我所有的群组
		$this->assign('smileList',$this->getSmile($this->opts['ico_type']));
		$this->assign('smilePath',$this->getSmilePath($this->opts['ico_type']));
		$myAllGroup = D('Group')->getAllGroup($this->mid);

		if($myAllGroup){
			$this->assign('myAllGroup',$myAllGroup);
			$this->setTitle($this->siteTitle['issue_topic']);
			$this->display();
		}else{  //如果没有群组，则跳转到创建页面
			$url = __APP__."/Index/add";
			$this->assign('jumpUrl',$url);
			$this->error('你还没有创建群组，请你先创建群组！');
		}
	}



	//最新动态
	function myjoinfeed() {
		$myJoinFeed = $this->group->getMyJoinGroup($this->mid);

		$this->assign('myJoinFeed',$myJoinFeed);
		$this->display();
	}


	//好友群组
	function flist() {
		$group = $page = '';
		$groupdata = $this->group->friendjoingroup($this->mid);
		if($groupdata) {
			list($group,$page) = $groupdata;
		}

		$this->assign('group',$group);
		$this->assign('page',$page);
		$this->setTitle($this->siteTitle['my_friend_group']);

		$this->display();
	}

	//全部群组

	function search() {

		$grouplist = "";
		$cond = array("type='open'",'is_del=0','brower_level!=1');
		$name = isset($_POST['name']) ? t($_POST['name']) : '';
		if($name) {
			$cond[] = "name like '%".t($name)."%'";
		}

		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;

		if($cid) {
			$cond[] = 'cid0='.$cid;
		}

		$where = !empty($cond) ? implode(' AND ',$cond) : '';


		$grouplist = $this->group->where($where)->findPage();

		$category = D('Category')->_makeTree();

		$this->assign('recomGroup',$this->group->where('isrecom=1 AND is_del=0 ')->limit(10)->findAll());
		$this->assign('name',$name);
		$this->assign('cid',$cid);
		$this->assign('category',$category);
		$this->assign('grouplist', $grouplist);

		$this->setTitle($this->siteTitle['all_group']);
		$this->display();
	}

	//搜索话题
	function searchTopic(){
		$keywords = t($_POST['keywords']);
		if(!$keywords) $this->error('关键字太少');

		$where = "is_del=0 AND title like '%$keywords%'";
		$topiclist = D('Topic')->order('top DESC,replytime DESC')->where($where)->findPage(3);


		foreach($topiclist['data'] as $k => $v){
			$topiclist['data'][$k]['tid'] = $topiclist['data'][$k]['id'];
			$topiclist['data'][$k]['title'] = red($topiclist['data'][$k]['title'],$keywords);
			$content = D('Post')->getField('content','tid='.$v['id']." AND istopic=1");
			$topiclist['data'][$k]['content'] = msubstr(t($content),0,100);
			$topiclist['data'][$k]['group_name'] =  getgroupinfo($topiclist['data'][$k]['gid'],'name');
			$topiclist['data'][$k]['group_id'] =  $topiclist['data'][$k]['gid'];
		}
		$this->assign('keywords',$keywords);
		$this->assign('topiclist',$topiclist);
		$this->display();
	}

}



?>