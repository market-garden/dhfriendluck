<?php

	class TopicAction extends BaseAction {
		var $topic;
		var $post;
		var $gid;
		var $actor_level;
		public function _initialize(){
			parent::_initialize();
			//parent::base();
			$this->topic = D('Topic');
			$this->post = D('Post');
			$this->gid = $this->gid;

			//权限判读
			//顶，精华，锁定，删除
			if(in_array(ACTION_NAME,array('dist','undist','top','untop','lock','unlock'))) {
				if(!$this->isadmin){
					$this->error('你没有权限');
				}
			}

			$this->actor_level =  $this->groupinfo['brower_level']==-1 && !$this->mid? false :
			 ($this->groupinfo['actor_level']==0 ? true : $this->groupinfo['actor_level'] == 1 && isJoinGroup($this->mid,$this->gid)) ;  //如果actor_level=1 仅成员可以发表话题，如果是2 任何人都可以回复

		
			$this->assign('actor_level',$this->actor_level);
			$this->assign('current','topic');

	}

		function index() {
			$dist = isset($_GET['isdist']) && $_GET['isdist'] == 1 ? ' AND dist=1' : '';  //精华

			$topiclist = $this->topic->order('top DESC,replytime DESC')->where('is_del=0 AND gid='.$this->gid.$dist)->findPage();

			$this->assign('dist',$dist);
			$this->assign('topiclist',$topiclist);
			if($dist) $this->setTitle($this->siteTitle['dist_topic']);
			else{
				$this->setTitle($this->siteTitle['topic_index']);

		}
 			$this->display();

		}




		//发表话题 编辑话题
		function add() {
			
			//权限判读 用户没有加入过
			if(($this->groupinfo['actor_level'] == 1 && !isJoinGroup($this->mid,$this->gid)) || !$this->mid){
				$this->alert();
			}


			$this->setTitle($this->siteTitle['add_topic']);
			$this->display();
		}

		//添加内容
		function doAdd(){

			if(isset($_POST['addsubmit']) && trim($_POST['addsubmit']) == 'do') {

				//$title = msubstr(t($_POST['title'],0,60));
				$title = msubstr(t($_POST['title']),0,100,$charset="utf-8", false);


				$content = h($_POST['content']);

				if(empty($title)) $this->error('标题不能为空');
				if(!replaceSpecialChar($content)) $this->error('内容不能为空');
				if($_POST['attach']) $topic['attach'] = serialize($_POST['attach']);   //添加附件

				$topic['gid'] = $this->gid;

				$topic['uid'] = $this->mid;
				$topic['name'] = getUserName($this->mid);
				$topic['title'] = $title;
				$topic['addtime'] = time();
				$topic['replytime'] = time();

				//dump($topic);
				//exit;
				if($tid = D('Topic')->add($topic)) {

					import('ORG.Net.IpLocation');
					$ip = new IpLocation();
					$post['gid'] = $this->gid;
					$post['uid'] = $this->mid;
					$post['tid'] = $tid;
					$post['content'] = $content;
					$post['istopic'] = 1;
					$post['ctime'] = time();
					$post['ip'] = $ip->get_client_ip();

					$result = $this->post->add($post);

					//添加动态
					$title_data["actor"] = getUserName($this->mid);
					$title_data['gid'] = $this->gid;
					$title_data['group_name'] = $this->groupinfo['name'];

   				
   					$body_data['title'] = msubstr($title,0,20);
					$body_data['gid'] = $this->gid;
					$body_data['tid'] = $tid;


					$this->api->feed_publish('group_topic',$title_data,$body_data,$this->appId,0,$this->gid);
					setScore($this->mid,'group_topic_add');

					$this->assign('jumpUrl',__APP__."/Topic/topic/gid/{$this->gid}/tid/".$tid);
					$this->success('发表话题成功');
				}else{


					$this->error('添加失败');
				}
			}
		}


		//编辑话题
		function edit() {
			//权限判读 (管理员和创建者)
			$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
			$thread = $this->topic->getThread($tid);

			if(empty($thread)) $this->error('编辑话题不存在');


			//管理员或者帖子主人
			if(!($this->isadmin || $thread['uid'] == $this->mid)){$this->error('无权限');}


			if(isset($_POST['editsubmit']) && trim($_POST['editsubmit']) == 'do') {

				$title = t($_POST['title']);
				$content_i = replaceSpecialChar(h($_POST['content']));
				if(empty($title) || empty($content_i)) $this->error('内容或者标题不能为空');
				$content = h($_POST['content']);

				$map['attach'] = !empty($_POST['attach']) ? serialize($_POST['attach']) : '';
				$map['title'] = $title;
				$map['mtime'] = time();

				$this->topic->where('id='.$tid)->save($map);


				$this->post->setField('content',$content,'tid='.$tid." AND istopic=1");
				redirect(__APP__."/Topic/topic/gid/{$this->gid}/tid/".$tid);
			}


			$this->assign('thread',$thread);
			$this->display();
		}


		//话题回复
		function post() {




			$tid = isset($_POST['tid']) ? intval($_POST['tid']) : 0;
			$content_i = replaceSpecialChar(h($_POST['content']));

			

			if(empty($content_i)){
				$this->error('请输入内容');
			}


			if($tid > 0 && !empty($content_i)) {
				$topic = gettopic($tid);  //获取话题内容

				import('ORG.Net.IpLocation');
				$ip = new IpLocation();

				if($_POST['attach']) $post['attach'] = serialize($_POST['attach']);   //添加附件
				$post['gid'] = $this->gid;
				$post['uid'] = $this->mid;
				$post['tid'] = $tid;
				$post['content'] = h($_POST['content']);
				$post['istopic'] = 0;
				$post['ctime'] = time();
				$post['ip'] = $ip->get_client_ip();
				
				$title_data['actor'] = $this->mid;
    			$body_data['gid'] = $this->gid;
    			$body_data['tid'] = $tid;
    			$body_data['title'] = $topic['title'];
				
    			if(isset($_POST['quote'])) {  //如果引用帖子
						$qid = isset($_POST['qid']) ? intval($_POST['qid']) : 0;      //引用帖子id

						$post['quote'] = $qid;

						$postData = getPost($qid);
						if($postData['uid'] != $this->mid){
							
            				//通知
            				$cate = "notification";
            				$this->api->notify_setAppId($this->appId);
            				$this->api->notify_send($postData['uid'],'group_topic_quote',$title_data,$body_data,$url,$cate);
							setScore($this->mid,'group_topic_reply');
							
							
						}

				}



				$result = $this->post->add($post);  //添加回复

				if($result) {
					//添加回复通知


					if(!($topic['uid'] == $this->mid || $postData['tid'] == $topic['id'])){ //如果不是回复自己
    					
            			//通知
            			$cate = "notification";
            			$this->api->notify_setAppId($this->appId);
            			$this->api->notify_send($topic['uid'],'group_reply',$title_data,$body_data,$url,$cate);
            			setScore($this->mid,'group_topic_reply');
       
					}

					//添加动态
					
					$this->api->feed_publish('group_topic_post',$title_data,$body_data,$this->appId,0,$this->gid);
					
					$this->topic->setField('replytime',time(),'id='.$tid);
					$this->topic->setInc('replycount','id='.$tid);
				}

				redirect(__APP__."/Topic/topic/gid/{$this->gid}/tid/".$tid);
			}

			redirect(__APP__."/Topic/index/gid/{$this->gid}");
		}




		//编辑话题回复

		function editPost(){
			//权限判读 (管理员和创建者)
			$pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
			$post = $this->post->where('id='.$pid.' AND is_del=0')->find();


			//管理员或者帖子主人
			if(!$post) { $this->error('帖子回复不存在');}
			if( !($this->isadmin || $post['uid'] == $this->mid) ){$this->error('无权限');}


			if(isset($_POST['editsubmit']) && trim($_POST['editsubmit']) == 'do') {


				$content_i = replaceSpecialChar(h($_POST['content']));
				if(empty($content_i)) $this->error('内容或者标题不能为空');
				$content = h($_POST['content']);


				$map['attach'] = !empty($_POST['attach']) ? serialize($_POST['attach']) : '';
				$map['content'] = $content;
				$ret = $this->post->where('id='.$pid." AND istopic=0")->save($map);
				if($ret) {
				 redirect(__APP__."/Topic/topic/gid/{$this->gid}/tid/".$post['tid']);
				}else{
					$this->error('修改失败');
				}
			}


			$this->assign('post',$post);
			$this->setTitle($this->siteTitle['edit_topic']);
			$this->display();
		}


		//搜索话题
		function search() {
			$type = !empty($_POST['type']) ? $_POST['type'] : 'cort';

			if(isset($_POST['searchSubmit'])){
				$keywords = !empty($_POST['keywords']) ? t($_POST['keywords']) : '';

				if(!$keywords) $this->error('关键字太少');

				if($type=='name'){
					$where = 'gid='.$this->gid." AND is_del=0 AND name like '%$keywords%'";
					$topiclist = $this->topic->order('top DESC,replytime DESC')->where($where)->findPage(3);

					foreach($topiclist['data'] as $k => $v){
						$topiclist['data'][$k]['tid'] = $topiclist['data'][$k]['id'];
						$topiclist['data'][$k]['name'] = red($topiclist['data'][$k]['name'],$keywords);
						$topiclist['data'][$k]['content'] = D('Post')->getField('content','tid='.$v['id']." AND istopic=1");
					}
				}elseif($type == 'cort'){
			    	$topiclist=$this->topic->getSearch($keywords,$this->gid,'cort');
			    	foreach($topiclist['data'] as $k => $v){
						$topiclist['data'][$k]['title'] = red(t($topiclist['data'][$k]['title']),$keywords);
						$topiclist['data'][$k]['content'] = redContent(t($topiclist['data'][$k]['content']),$keywords);
					}
				}

				$this->assign('keywords',$keywords);
				$this->assign('topiclist',$topiclist);
			}
			$this->setTitle($this->siteTitle['search_topic']);
			$this->assign('type',$type);
 			$this->display();
		}



		//话题显示
		function topic() {

			if(!checkPriv('browse',$this->groupinfo['brower_level'],$this->mid,$this->gid)){
				$this->alert();
			}


			$tid = intval($_GET['tid']) > 0 ? $_GET['tid'] : 0;

			if($tid == 0) $this->error('参数错误');
			$limit = 20;

			$this->topic->setInc('viewcount','id='.$tid);
			$thread = $this->topic->getThread($tid);     //获取主题
			//判读帖子存不存在
			if(!$thread) $this->error('帖子不存在');

			$postlist = $this->post->order('istopic DESC')->where('is_del = 0 AND tid='.$tid)->findPage($limit);


			$this->assign('limit',$limit);
			$this->assign('topic',$thread);
			$this->assign('tid',$tid);
			$this->assign('postlist',$postlist);

			$this->assign('isCollect',D('Collect')->isCollect($tid,$this->mid));  //判断是否收藏
			//$bq_emotion = D('Smile')->getSmile('mini');//读取表情

			//$this->saveToken();
			//$this->assign('bq_emotion',$bq_emotion);

			 $this->setTitle($thread['title'].'-');
			$this->display();
		}
		//收藏话题
		function collect(){
			$tid = intval($_POST['tid']);
			$Collect = D('Collect');
			if($tid >0){
				$map['tid'] = $tid;
				$map['mid'] = $this->mid;
				$map['addtime'] = time();

				if($Collect->isCollect($tid,$this->mid)) { echo "-1"; exit();}
				if($Collect->add($map)){
					echo "1"; exit();
				}
			}
			echo "0"; exit();
		}

		//收藏话题
		function cancel_collect($gid,$tid){
			$tid = intval($_POST['tid']);
			if($tid >0){

				if(D('Collect')->where('tid='.$tid." AND mid=".$this->mid)->delete()){
					echo "1"; exit();
				}
			}
			echo "0"; exit();
		}


		//引用
		function quote_dialog(){
			$id = intval($_REQUEST['id']);
			//$tid = intval($_REQUEST['tid']);

			$postInfo = $this->post->where('id='.$id)->find();

			if(empty($postInfo))  $this->error('参数错误');

			$this->assign('postInfo',$postInfo);
			$this->assign('id',$id);
			$this->display();
		}

		//精华
		function dist() {



			$tid = isset($_POST['tid']) && intval($_POST['tid']) > 0 ? intval($_POST['tid']) : 0;
			if($tid == 0) exit('tid错误');
			$topicInfo = $this->topic->where('id='.$tid)->find();

			$result = $this->topic->setField('dist','1','id='.$tid);

			if($result) {
				//设置日志
				$content = "把话题“<a href='__APP__/Topic/topic/gid/{$this->gid}/tid/$tid'>".$topicInfo['title']."</a>”，作者 "."<a href='__TS__/space/{$topicInfo['uid']}'>".getUserName($topicInfo['uid'])."</a>"."，设置成为了精华";
				D('Log')->writeLog($this->gid,$this->mid,$content);

				//添加通知
				if($topicInfo['uid'] != $this->mid){
					$title_data['actor'] = getUserName($this->mid);

    				$body_data['gid'] = $this->gid;
    				$body_data['tid'] = $tid;

    				$body_data['title'] = $topicInfo['title'];
    				//通知
    				$cate = "notification";
    				$this->api->notify_setAppId($this->appId);
    				$this->api->notify_send($topicInfo['uid'],'group_topic_dist',$title_data,$body_data,$url,$cate);

    				setScore($this->mid,'group_topic_dist');
				}



				exit(json_encode(array('flag'=>'1','title'=>'精华','body'=>'话题设为精华成功！')));
				exit;
			}else{
				exit(json_encode(array('flag'=>'0','title'=>'精华','body'=>'话题设为精华失败')));
			}
		}

		function undist() {


			$tid = isset($_POST['tid']) && intval($_POST['tid']) > 0 ? intval($_POST['tid']) : 0;
			if($tid == 0) exit('tid错误');

			$topicInfo = $this->topic->where('id='.$tid)->find();
			$result = $this->topic->setField('dist','0','id='.$tid);
			if($result) {

				//设置日志
				$content = "把话题“<a href='__APP__/Topic/topic/gid/{$this->gid}/tid/$tid'>".$topicInfo['title']."</a>”，作者 "."<a href='__TS__/space/{$topicInfo['uid']}'>".getUserName($topicInfo['uid'])."</a>"."，取消了精华";
				D('Log')->writeLog($this->gid,$this->mid,$content);

				setScore($this->mid,'group_topic_cancel_dist');
				exit(json_encode(array('flag'=>'1','title'=>'取消精华','body'=>'话题取消精华成功！')));

			}else{
				exit(json_encode(array('flag'=>'0','title'=>'取消精华','body'=>'取消精华成功！')));
			}
		}
		//置顶
		function top() {

			//权限判读
			$tid = isset($_POST['tid']) && intval($_POST['tid']) > 0 ? intval($_POST['tid']) : 0;
			if($tid == 0) exit('tid错误');

			$topicInfo = $this->topic->where('id='.$tid)->find();
			$result = $this->topic->setField('top','1','id='.$tid);
			if($result) {

				//设置日志
				$content = "把话题“<a href='__APP__/Topic/topic/gid/{$this->gid}/tid/$tid'>".$topicInfo['title']."</a>”，作者 "."<a href='__TS__/space/{$topicInfo['uid']}'>".getUserName($topicInfo['uid'])."</a>"."，置顶";
				D('Log')->writeLog($this->gid,$this->mid,$content);

				//添加通知
				if($topicInfo['uid'] != $this->mid){
					$title_data['actor'] = getUserName($this->mid);

    				$body_data['gid'] = $this->gid;
    				$body_data['tid'] = $tid;

    				$body_data['title'] = $topicInfo['title'];
    				//通知
    				$cate = "notification";
    				$this->api->notify_setAppId($this->appId);
    				$this->api->notify_send($topicInfo['uid'],'group_topic_top',$title_data,$body_data,$url,$cate);
					setScore($this->mid,'group_topic_top');
				}


				exit(json_encode(array('flag'=>'1','title'=>'置顶','body'=>'话题置顶成功！')));
				exit;
			}else{
				exit(json_encode(array('flag'=>'0','title'=>'置顶','body'=>'话题置顶失败')));
			}
		}
		function untop() {
			//权限判读

			$tid = isset($_POST['tid']) && intval($_POST['tid']) > 0 ? intval($_POST['tid']) : 0;
			if($tid == 0) exit('tid错误');

			$topicInfo = $this->topic->where('id='.$tid)->find();
			$result = $this->topic->setField('top','0','id='.$tid);
			if($result) {

				//设置日志
				$content = "把话题“<a href='__APP__/Topic/topic/gid/{$this->gid}/tid/$tid'>".$topicInfo['title']."</a>”，作者 "."<a href='__TS__/space/{$topicInfo['uid']}'>".getUserName($topicInfo['uid'])."</a>"."，取消置顶";
				D('Log')->writeLog($this->gid,$this->mid,$content);

				setScore($this->mid,'group_topic_cancel_top');
				exit(json_encode(array('flag'=>'1','title'=>'取消置顶','body'=>'取消置顶成功！')));

			}else{
				exit(json_encode(array('flag'=>'0','title'=>'取消置顶','body'=>'取消置顶失败！')));
			}
		}
		//锁定
		function lock() {
			//权限判读
			$tid = isset($_POST['tid']) && intval($_POST['tid']) > 0 ? intval($_POST['tid']) : 0;
			if($tid == 0) exit('tid错误');

			$topicInfo = $this->topic->where('id='.$tid)->find();
			$result = $this->topic->setField('lock','1','id='.$tid);
			if($result) {

				//设置日志
				$content = "把话题“<a href='__APP__/Topic/topic/gid/{$this->gid}/tid/$tid'>".$topicInfo['title']."</a>”，作者 "."<a href='__TS__/space/{$topicInfo['uid']}'>".getUserName($topicInfo['uid'])."</a>"."，锁定";
				D('Log')->writeLog($this->gid,$this->mid,$content);

				//添加通知
				if($topicInfo['uid'] != $this->mid){
					$title_data['actor'] = getUserName($this->mid);

    				$body_data['gid'] = $this->gid;
    				$body_data['tid'] = $tid;

    				$body_data['title'] = $topicInfo['title'];
    				//通知
    				$cate = "notification";
    				$this->api->notify_setAppId($this->appId);
    				$this->api->notify_send($topicInfo['uid'],'group_topic_lock',$title_data,$body_data,$url,$cate);
				}


				exit(json_encode(array('flag'=>'1','title'=>'锁定','body'=>'锁定成功！')));

			}else{
				exit(json_encode(array('flag'=>'0','title'=>'锁定','body'=>'锁定失败！')));
			}
		}
		function unlock() {
			//权限判读
			$tid = isset($_POST['tid']) && intval($_POST['tid']) > 0 ? intval($_POST['tid']) : 0;
			if($tid == 0) exit('tid错误');

			$topicInfo = $this->topic->where('id='.$tid)->find();
			$result = $this->topic->setField('lock','0','id='.$tid);
			if($result) {

				//设置日志
				$content = "把话题“<a href='__APP__/Topic/topic/gid/{$this->gid}/tid/$tid'>".$topicInfo['title']."</a>”，作者 "."<a href='__TS__/space/{$topicInfo['uid']}'>".getUserName($topicInfo['uid'])."</a>"."，解锁";
				D('Log')->writeLog($this->gid,$this->mid,$content);

				exit(json_encode(array('flag'=>'1','title'=>'取消锁定','body'=>'取消锁定成功！')));

			}else{
				exit(json_encode(array('flag'=>'0','title'=>'取消锁定','body'=>'取消锁定失败！')));
			}
		}

		//删除
		function del() {
			//权限判读  注意权限

			$id = isset($_POST['tid']) && intval($_POST['tid']) > 0 ? intval($_POST['tid']) : 0;

			if($id == 0) exit();
			if($_POST['type'] == 'thread') {

				$topicInfo = $this->topic->where('id='.$id)->find();

				//设置日志
				$content = "把话题“{$topicInfo['title']}”，作者 "."<a href='__TS__/space/{$topicInfo['uid']}'>".getUserName($topicInfo['uid'])."</a>"."，删除";
				D('Log')->writeLog($this->gid,$this->mid,$content);

				$this->topic->remove($id);
				setScore($this->mid,'group_topic_delete');

				redirect(__APP__."/Topic/index/gid/{$this->gid}");
			}elseif($_POST['type'] == 'post') {
				$tid = $this->post->getField('tid','id='.$id);            //获取要删除的帖子id

				$this->post->remove($id);           //删除回复

				//帖子回复数目减少1个
				$this->topic->setDec('replycount','id='.$tid);
				redirect(__APP__."/Topic/topic/gid/{$this->gid}/tid/".$tid);
			}

		}

		function addShare_check(){

			$result = 1;
			$aimId = intval($_REQUEST['aimId']);

			$test = $this->api->share_isForbid($this->mid,9,$aimId);

			if($test==-1){
				$result = -2;
			}

			echo $result;
		}
		function addShare(){
			$aimId = intval($_REQUEST['aimId']);
			$this->assign('aimId',$aimId);
			$group = D('Topic')->getThread($aimId,'title');

			$this->assign('title',$group['title']);
			$this->assign($group);
			$this->assign('mid',$this->mid);

			$this->display();
		}

		function doaddShare(){
			$type['typeId'] = 9;
			$type['typeName'] = '话题';
			$type['alias'] = 'topic';

			$info = h($_REQUEST['info']);
			$aimId = intval($_REQUEST['aimId']);

			$data = D('Topic')->getThread($aimId);

			$data['cid'] = $this->groupinfo['cid0'];
			$data['catagory'] = getCategoryName($data['cid']);
			$data['groupName'] = $this->groupinfo['name'];

		    $intro = str_replace( "&amp;nbsp;","",t($data['content']));
		    $data['intro'] = $this->_getBlogShort($intro,120);
            unset($data['content']);
			//$data['title'] = h($_REQUEST['title']);
			$fids = $_REQUEST['fids'];

			$result = $this->api->share_addShare($type,$aimId,$data,$info,0,$fids);
			echo $result;
		}

		function _getBlogShort($content,$length = 60) {
			$content	=	stripslashes($content);
			$content	=	strip_tags($content);
			$content	=	getShort($content,$length);
			return $content;
		}
	}
?>