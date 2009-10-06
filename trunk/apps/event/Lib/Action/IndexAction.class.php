<?php
/**
 * IndexAction
 * 活动
 * @uses Action
 * @package
 * @version $id$
 * @copyright 2009-2011 SamPeng
 * @author SamPeng <sampeng87@gmail.com>
 * @license PHP Version 5.2 {@link www.sampeng.cn}
 */
class IndexAction extends Action {
    private $event;

    /**
     * __initialize
     * 初始化
     * @access public
     * @return void
     */
    public function _initialize() {
    //参数转义
        new_addslashes($_POST);
        new_addslashes($_GET);
     
        //设置心情Action的数据处理层
        $this->event = D( 'Event' );
        $this->event->setApi( $this->api);
    }

    /**
     * index
     * 首页
     * @access public
     * @return void
     */
    public function index() {
        $this->event->setMid( $this->mid );

        //获取好友
        $friends = $this->api->friend_get( $this->uid );
        if( !isset( $friends ) ) {
            $result = false;
            $cate = D( 'EventType' )->getType();
            $this->assign( 'category',$cate );
            $this->display();
            exit;
        }
        $cate = D( 'EventType' )->getType();
        $this->assign( 'category',$cate );
        //查询
        if( empty( $_GET['cid'] ) && empty( $_POST ) ) {
            $result  = $this->event->getEventList( $friends );
        }else {
            $title = t( $_POST['title'] );
            isset( $_POST ) && !empty( $title ) && $map['title'] = array( 'like',"%".$title."%" );
            isset( $_GET['cid'] )  && $map['type']  = $_GET['cid'];
            $result  = $this->event->getEventList( $friends,false,$map );
        }
        $this->assign( $result );
        $this->display();
    }

    public function my() {
        $this->event->setMid( $this->mid );

        $result  = $this->event->getEventList( $this->mid );
        $this->assign( $result );
        $this->display();
    }

    public function personal() {
        $uid = intval($_GET['uid']);
        $name = $this->api->user_getInfo($uid,'name');
        if($uid == 0 || !$name) {
            $this->error('此用户被删除或者被屏蔽');
        }

        $this->event->setMid( $uid );

        $result  = $this->event->getEventList( $uid );
        $this->assign( $result );
        $this->assign($_GET);
        $this->assign($name);
        $this->display();
    }

    public function all() {
        $this->event->setMid( $this->mid );
        //获得所有分类
        $cate = D( 'EventType' )->getType();
        $this->assign( 'category',$cate );

        //查询
        if( empty( $_GET['cid'] ) && empty( $_POST ) ) {
            $result  = $this->event->getEventList(null,true);
        }else {
            $title = t( $_POST['title'] );
            isset( $_POST ) && !empty( $title ) && $map['title'] = array( 'like',"%".$title."%" );
            isset( $_GET['cid'] )  && $map['type']  = $_GET['cid'];

            $result  = $this->event->getEventList( null,true,$map );
        }
        $this->assign( $result );
        $this->display();
    }

    /**
     * addEvent
     * 发起活动
     * @access public
     * @return void
     */
    public function addEvent() {
        $typeDao = D( 'EventType' );
        $this->assign('type',$typeDao->getType());
        //TODO 获取本人创建的群组
        $this->display();
        
    }

    /**
     * joinIn
     * ajax的参与活动
     * @access public
     * @return void
     */
    public function doAction() {
        $data['id']   = intval( t($_POST['id']) );
        $data['uid']  = intval( t($this->mid) );
        $allow        = intval( t($_POST['allow']) );
        $data['name'] = $this->my_name;
        $data['action'] = t( $_POST['action'] );
        $this->event->setMid( $this->mid );
        //检测id和uid是否为0
        if( false == $this->checkUrl( $data ) ) {
            echo -4;
            return;
        }
        echo trim($this->event->doAddUser( $data,$allow ));
  
    }


    /**
     * joinIn
     * ajax的参与活动
     * @access public
     * @return void
     */
    public function doAgreeAction() {
        $data['id']      = intval( $_POST['id'] );
        $data['eventId'] = intval( $_POST['eventId'] );
        $data['uid']     = intval( $_POST['uid'] );

        //检测id和uid是否为0
        if( false == $this->checkUrl( $data ) ) {
            echo -4;
            return;
        }
        echo trim($this->event->doArgeUser( $data));
    }

    public function doDelAction() {
        $data['id']   = intval( $_POST['id'] );
        $data['uid']  = intval( $this->mid );
        $allow        = intval( $_POST['allow'] );
        $data['name'] = $this->my_name;
        $data['action'] = t( $_POST['action'] );
        //检测id和uid是否为0
        if( false == $this->checkUrl( $data ) ) {
            echo -4;
            return;
        }
        echo trim($this->event->doDelUser( $data,$allow ));

    }

    /**
     * eventDetail
     * 活动详细页
     * @access public
     * @return void
     */
    public function eventDetail() {
        $id   = intval( $_GET['id'] );
        $uid  = intval( $_GET['uid'] );
        $test = array( $id,$uid );
        //检测id和uid是否为0
        if( false == $this->checkUrl( $test ) ) {
            $this->error( "错误的访问页面，请检查链接" );
        }

        $this->event->setMid( $this->mid );
        if($result = $this->event->getEventContent( $id,$uid )) {
            $this->assign( $result );
            $this->setTitle($result['title']);
            $this->display();
        }else {
            $this->error( '错误的访问页面，请检查链接' );
        }
    }

    public function member() {
        $id = intval( $_GET['id'] );
        //检查url参数
        if( false == $this->checkUrl( array( $id ) ) ) {
            $this->error( "错误的访问页面，请检查链接" );
        }

        //检查id是否存在
        if( false == $event = $this->event->where( 'id='.$id )->field( 'uid,id,name,title,joinCount,attentionCount,optsId' )->find() )
            $this->error( "活动已删除或取消" );

        $this->assign( $event );

        //计算待审核人数
        if( $this->mid == $event['uid'] )
            $verifyCount = D( 'EventUser' )->where( "status = 0 AND action='joinIn' AND eventId =".$event['id'] )->count();
        $this->assign( 'verifyCount',$verifyCount );

        //获得action对应的成员
        switch( $_GET['action'] ) {
            case "att":
                $map['action'] = 'attention';
                $map['status'] = 1;
                break;
            case "join":
                $map['action'] = 'joinIn';
                $map['status'] = 1;
                break;
            case 'verify':
                $map['action'] ='joinIn';
                $map['status'] = 0;
                break;
            default:
                $map['action'] = array( 'in',"'admin','attention','joinIn'" );
                $map['status'] = 1;
        }
        $map['eventId'] = $event['id'];
        //取得成员列表
        $result = $this->event->getMember($map,$event['uid'],$event['name']);
        $this->assign( 'guest',$this->mid );
        $this->assign( $result );
        $this->display();
    }


    /**
     * doAddEvent
     * 添加活动
     * @access public
     * @return void
     */
    public function doAddEvent() {
        $map['title']      = t($_POST['title']);
        $map['address']    = t($_POST['address']);
        $map['limitCount'] = intval(t( $_POST['limitCount'] ));
        $map['type']       = $_POST['type'];
        $map['explain']    = h($_POST['explain']);
        $map['contact']    = $_POST['contact'];
        $map['deadline'] = $deadline = $this->_paramDate( $_POST['deadline'] );
        $map['sTime']    = $stime = $this->_paramDate($_POST['sTime']);
        $map['eTime']    = $etime = $this->_paramDate($_POST['eTime']);
        $map['uid']      = $this->mid;
        $map['name']     = $this->my_name;

        if( ( $stime  > $etime ) && $stime < time()) {
            $this->error( "结束时间不允许小于开始时间,并且开始时间不得小于当前时间" );
            exit;
        }
        if( $deadline < time() ) {
            $this->error( "结束时间不得小于当前时间" );
        }

        //处理省份，市，区
        list( $opts['province'],$opts['city'],$opts['area'] ) = explode( " ",$_POST['city']);;

        //得到上传的图片
        $option['save_photo']['albumId'] = intval( $_POST['albumId'] );
        $option['max_size'] = $this->event->getConfig( 'limitphoto' )*1024*1024;
        $option['allow_exts'] = $this->event->getConfig( 'limitsuffix' );
        $cover = $this->api->attach_upload( 'event_cover',$option );

        //处理选项
        $opts['cost']        = intval( $_POST['cost'] );
        $opts['costExplain'] = t( $_POST['costExplain'] );
        $friend              = isset( $_POST['friend'] )?1:0;
        $alow                = isset( $_POST['alow'] )?1:0;
        $opts['opts']        = array( 'friend'=>$friend,'alow'=>$alow );
        if( $addId =$this->event->doAddEvent( $map,$opts,$cover )) {
                setScore($this->mid, 'creat_event');
            $this->redirect( 'Index/eventDetail/id/'.$addId.'/uid/'.$this->mid );
        }
    }


    /**
     * doEditEvent
     * 修改活动
     * @access public
     * @return void
     */
    public function doEditEvent() {
        $id['id'] = intval( $_POST['id'] );
        $id['optsId'] = intval( $_POST['optsId'] );
        $map['title']      = t($_POST['title']);
        $map['address']    = t($_POST['address']);
        $map['limitCount'] = intval(t( $_POST['limitCount'] ));
        $map['type']       = $_POST['type'];
        $map['explain']    = h($_POST['explain']);
        $map['contact']    = h($_POST['contact']);
        $map['deadline'] = $deadline = $this->_paramDate( $_POST['deadline'] );
        $map['sTime']    = $stime = $this->_paramDate($_POST['sTime']);
        $map['eTime']    = $etime = $this->_paramDate($_POST['eTime']);
        $old_cover['status']  = true;
        $old_cover['info'][0]['id'] = intval( $_POST['old_cover'] );

        if( ( $stime  > $etime ) && $stime < time()) {
            $this->error( "结束时间不允许小于开始时间,并且开始时间不得小于当前时间" );
            exit;
        }
        if( $deadline < time() ) {
            $this->error( "结束时间不得小于当前时间" );
        }

        //处理省份，市，区
        list( $opts['province'],$opts['city'],$opts['area'] ) = explode( " ",$_POST['city']);;

        //得到上传的图片
        $option['save_photo']['albumId'] = intval( $_POST['albumId'] );
        $option['max_size'] = $this->event->getConfig( 'limitphoto' )*1024*1024;
        $option['allow_exts'] = $this->event->getConfig( 'limitsuffix' );
        $cover = $_FILES['cover']['size']>0?$this->api->attach_upload( 'event_cover',$option ):$old_cover;


        //处理选项
        $opts['cost']        = intval( $_POST['cost'] );
        $opts['costExplain'] = t( $_POST['costExplain'] );
        $friend              = isset( $_POST['friend'] )?1:0;
        $alow                = isset( $_POST['alow'] )?1:0;
        $opts['opts']        = array( 'friend'=>$friend,'alow'=>$alow );
        if( $this->event->doEditEvent( $map,$opts,$cover,$id )) {
            $this->redirect( 'Index/eventDetail/id/'.$id['id'].'/uid/'.$this->mid );
        }
    }

    /**
     * doEndAction
     * 结束活动
     * @access public
     * @return void
     */
    public function doEndAction() {
        $id = $_POST['id'];
        $this->event->setMid( $this->mid );
        echo $this->event->doEditData( time(),$id );
    }

    /**
     * edit
     * 编辑活动
     * @access public
     * @return void
     */
    public function edit(  ) {
        $id = intval( $_GET['id'] );
        $uid = $this->event->where( 'id='.$id )->getField( 'uid' );
        if( $uid != $this->mid ) {
            $this->error( '您没有权限编辑这个活动' ) ;
        }

        $typeDao = D( 'EventType' );
        $this->event->setMid( $this->mid );
        if($result = $this->event->getEventContent( $id,$uid )) {
            $this->assign( $result );
            $this->assign('category',$typeDao->getType());
            $this->display('edit');
        }else {
            $this->error( '错误的访问页面，请检查链接' );
        }

    }
    /**
     * doAdminAction
     * 管理员操作行为
     * @access public
     * @return void
     */
    public function doAdminAction() {
        $admin          = t( $_POST['admin'] );
        $data['uid']    = intval( $_POST['uid'] );      //被操作的用户
        $data['id']     = intval( $_POST['eventId'] );  //被操作的活动
        $data['action'] = t( $_POST['action'] );    //被操作的用户的动作
        //取得是否需要审核
        $opts           = D( 'EventOpts' )->getOpts(intval( $_POST['opts'] ));  //被操作的活动的动作
        $temp = unserialize( $opts['opts'] );
        $allow = $temp['alow'];

        //检查链接合法性
        if( !$this->checkUrl( $data ) ) {
            echo -4;
            return;
        }
        switch ( $admin ) {
            case 'user':   //成员管理
                echo $this->event->doDelUser( $data,$allow );
                return;
                break;
            default:
        //TODO 更多的操作
        }

    }

    /**
     * _paramDate
     * 解析日期
     * @param mixed $date
     * @access private
     * @return void
     */
    private function _paramDate( $date ) {
        $date_list = explode( ' ',$date );
        list( $year,$month,$day ) = explode( '-',$date_list[0] );
        list( $hour,$minute,$second ) = explode( ':',$date_list[1] );
        return mktime( $hour,$minute,$second,$month,$day,$year );
    }

    /**
     * checkUrl
     * 检查url参数是否合法
     * @param array $data
     * @access public
     * @return void
     */
    public function checkUrl(array $data ) {
        $count1 = count( $data );
        $count2 = count( array_filter( $data ));
        if( $count2 < $count1 ) {
            return false;
        }else {
            return true;
        }
    }

    /**
     * upload
     * 上传图片
     * @access public
     * @return void
     */
    public function upload() {
        $eventId = intval($_GET['eventId']);
        //检查空
        if( empty($eventId) || 0 === $eventId ) {
            $this->error( '没有传入' );
        }

        //检查是否有有这个活动
        if( false === $event = $this->event->where( 'id='.$eventId )->field( 'id,title,uid' )->find() ) {
            $this->error( '没有您请求的页面，请检查链接' );
        }

        //检查是否访问者有权限上传图片
        $action = $this->event->hasMembel( $this->mid,$eventId );

        switch ( $this->event->getConfig( 'membel' ) ) {
            case 0:
                ('attention' == $action['action'] || false == $action || 1 != $action['status']) && $this->error( "只允许活动参与者上传照片" );
                break;
            case 1;
                ('admin' != $action['action'] || false == $action) && $this->error( "只允许活动创建者者上传照片" );
                break;
            default:
                $this->error( '错误的配置信息' );
        }
        $this->assign( $event );

        $this->display();

    }

    /**
     * upload
     * flash上传图片
     * @access public
     * @return void
     */
    public function flash() {
        $eventId = intval($_GET['eventId']);
        //检查空
        if( empty($eventId) || 0 === $eventId ) {
            $this->error( '没有传入' );
        }

        //检查是否有有这个活动
        if( false === $event = $this->event->where( 'id='.$eventId )->field( 'id,title,uid' )->find() ) {
            $this->error( '没有您请求的页面，请检查链接' );
        }

        //检查是否访问者有权限上传图片
        $action = $this->event->hasMembel( $this->mid,$eventId );

        switch ( $this->event->getConfig( 'membel' ) ) {
            case 0:
                ('attention' == $action['action'] || false == $action || 1 != $action['status']) && $this->error( "只允许活动参与者上传照片" );
                break;
            case 1;
                ('admin' != $action['action'] || false == $action) && $this->error( "只允许活动创建者者上传照片" );
                break;
            default:
                $this->error( '错误的配置信息' );
        }
        $this->assign( $event );

        $this->display();

    }

    /**
     * upload_muti_pic
     * 普通上传图片
     * @access public
     * @return void
     */
    public function upload_muti_pic(  ) {
    //上传图片
        $cover = $this->api->attach_upload( 'event_photo' );

        $dao   = D( 'EventPhoto' );
        $dao->setMid( $this->mid );

        $data  = array();
        //存储图片
        if( true === $cover['status'] &&
            $result = $dao->addPhoto( $cover['info'] ,              //相册信息
            intval( $_POST['eventId'] ),  //活动Id
            $this->my_name)                        //用户信息
        ) {
            $this->success( '添加成功' );
        }else {
            $cover['status']?$this->error( '添加失败' ):$this->error( $cover['info'] );
        }

    }

    /**
     * upload_single_pic
     * 执行单照片上传
     * @access public
     * @return void
     */
    public function upload_single_pic() {
    //上传图片
        $photos = $this->api->attach_upload( 'event_photo' );
        $dao   = D( 'EventPhoto' );
        $dao->setMid( $this->mid );

        if($photos['status']  &&
            $result = $dao->addPhoto( $photos['info'] ,              //相册信息
            1,//intval( $_POST['eventId'] ),  //活动Id
            $this->my_name)                        //用户信息)
        ) {
            echo "Flash requires that we output something or it won't fire the uploadSuccess event";
        }else {
            echo "There was a problem with the upload";
            exit(0);
        }
    }

    /**
     * photos
     * 相册列表
     * @access public
     * @return void
     */
    public function photos() {
        $id = t($_GET['id']);
        $uid = t($_GET['uid']);
        //检查合法性
        if (false === $this->checkUrl( array( $id,$uid ) ))
            $this->error( "错误的地址，请检查链接" );
        //检查是否有这个活动
        if( false === $result = $this->event->where( 'id='.$id.' AND uid='.$uid )->find() ) {
            $this->error( "没有您提交的活动" );
        }
        //获得相片
        $photos = D( 'EventPhoto' )->where( 'eventId = '.$id )->order('id DESC')->findPage(20);
        //组装链接地址
        foreach( $photos['data'] as &$value ) {
            $value['path']  = sprintf( '%s/thumb.php?&w=130&h=87&url=%s%s%s',SITE_URL,UPLOAD_URL,$value['filepath'],$value['filename'] );
        }
        $this->assign( $result );
        $this->assign( $photos );
        $this->display();

    }


    //显示一张照片
    public function photo() {

        $id		=	intval($_REQUEST['id']);
        $aid	=	intval($_REQUEST['aid']);
        $uid	=	intval($_REQUEST['uid']);
        $eventId = intval( $_REQUEST['eid'] );

        //$type	=	t($_REQUEST['type']);	//照片来源类型，来自某相册，还是其他的

        //判断来源类型
        //if(!empty($type) && !in_array($type,array('album','mAll','fAll'))){
        //$this->error('错误的链接！');
        //}
        //$this->assign('type',$type);

        //获取照片信息
        $photo	=	D('EventPhoto')->where(" id='$id' AND eventId='$eventId' ")->find();
        $this->assign('photo',$photo);
        //验证照片信息是否正确
        if(!$photo) {
            $this->error('照片不存在或已被删除！');
        }

        //获取所有照片数据
        $photos	=	D('EventPhoto')->where( " eventId = '$eventId'" )->findAll();
        $this->assign('photos',$photos);


        //获取活动信息
        $event = D( 'Event' )->where( "id = '$eventId'" )->find();
        $event['cover'] = $temp_cover?UPLOAD_URL.$temp_cover:C( 'TS_URL' ).'/public/theme_default/images/hdpic1.gif';

        $this->assign( $event );


        //获取上一页 下一页 和 预览图
        if($photos) {
            foreach($photos as $v) {
                $photoIds[]	=	intval($v['id']);
            }
            $photoCount	=	count($photoIds);

            //颠倒数组，取索引
            $pindex		=	array_flip($photoIds);

            //当前位置索引
            $now_index	=	$pindex[$id];

            //上一张
            $pre_index	=	$now_index-1;
            if( $now_index <= 0 ) {
                $pre_index	=	$photoCount-1;
            }
            $pre_photo	=	$photos[$pre_index];

            //下一张
            $next_index	=	$now_index+1;
            if( $now_index >= $photoCount-1 ) {
                $next_index	=	0;
            }
            $next_photo	=	$photos[$next_index];

            //预览图的位置索引
            $start_index	=	$now_index - 2;
            if( ($photoCount+1-$now_index)<2) {
                $start_index	=	($photoCount+1-5);
            }
            if($start_index<0) {
                $start_index	=	0;
            }

            //取出预览图列表 最多5个
            $preview_photos	=	array_slice($photos,$start_index,5);
        }else {
            $this->error('照片列表数据错误！');
        }

        $this->assign('photoCount',$photoCount);
        $this->assign('now',$now_index+1);
        $this->assign('pre',$pre_photo);
        $this->assign('next',$next_photo);
        $this->assign('previews',$preview_photos);

        unset($pindex);
        unset($photos);
        unset($album);
        unset($preview_photos);
        $this->display();
    }

    function addShare_check() {
        $result = 1;
        $aimId = $_REQUEST['aimId'];

        //$event = $this->event->where("id='$aimId'")->field('uid')->find();
        //if($event['uid']==$this->mid){
        //$result = -1;
        //}else{
        $test = $this->api->share_isForbid($this->mid,12,$aimId);
        if($test==-1) {
            $result = -2;
        }
        //}

        echo $result;
    }
    function addShare() {
        $aimId = $_REQUEST['aimId'];
        $this->assign('aimId',$aimId);

        $title = $this->event->where("id='$aimId'")->field('title')->find();

        $this->assign($title);

        $this->display();
    }

    function doaddShare() {
        $type['typeId']   = 12;
        $type['typeName'] = '活动';
        $type['alias']     = 'event';

        $info  = h($_REQUEST['info']);
        $aimId = intval($_REQUEST['aimId']);

        $fids = $_REQUEST['fids'];

        $field         = 'title,name,uid';
        $data          = $this->event->where("id='$aimId'")->field($field)->find();
        //$data['title'] = h($_REQUEST['title']);
        $data['url']   = __URL__."/eventDetail/id/".$aimId."/uid/".$data['uid'];
        $result        = $this->api->share_addShare($type,$aimId,$data,$info,0,$fids);
        echo $result;
    }

    /**
     * commentSuccess
     * 活动评论回调函数
     * @access public
     * @return void
     */
    public function commentSuccess() {
        $result = json_decode($_POST['data']);  //json被反解析成了stdClass类型
        //计数更新
        $count = $this->__setCount($id);
        //发送两条消息
        $data = $this->__getNotifyData($result);
        $this->api->comment_notify('event',$data,$this->appId);
        echo $count;
    }
    public function deleteSuccess() {
        $id = $_POST['id'];
        echo $this->__setBlogCount($id);;
    }


    public function __getNotifyData($data){
        $result = array();
        //发送两条消息
        $result['toUid'] = $data->toUid;
        $need  = $this->event->where('id='.$data->appid)->field('uid,title')->find();
        $result['uids'] =$need['uid'];
        $result['url'] = sprintf('%s/Index/eventDetail/id/%s/uid/%s','{'.$this->appId.'}',$data->appid,$result['uids']);
        $result['title_body']['comment'] = $data->comment;
        $result['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$result['url'],$need['title']);
        $result['title_data']['type']  = "活动";
        return $result;
    }
    private function __setCount($id) {
        $count = $this->api->comment_getCount('event',$id);
        $this->event->setCount($id,$count);
        return $count;
    }


    /**
     * photoCommentSuccess
     * 活动相片评论成功回调函数
     * @access public
     * @return void
     */
    public function photoCommentSuccess() {
        $result = json_decode($_POST['data']);  //json被反解析成了stdClass类型
        //计数更新
        $dao = D( 'EventPhoto' );
        $dao->setInc('commentCount','id='.$result->appid);
        $photo = $dao->getPhoto( $result->appid );
        $title = $dao->where('id='.$photo['eventId'])->getField('savename');

        //发送两条消息
        $data['toUid'] = $result->toUid;
        $data['uids'] =$photo['uid'];
        $data['url'] = sprintf('%s/Index/photo/id/%s/uid/%s/eid/%s','{'.$this->appId.'}',$result->appid,$photo['uid'],$photo['eventId']);
        $data['title_body']['comment'] = $result->comment;
        $data['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$data['url'],$title);
        $data['title_data']['type']  = "活动相片";

        echo intval($this->api->comment_notify('event_photo',$data,$this->appId));

    }

    public function editPhoto() {
        $id   = intval( $_POST['id'] );
        $name = t($_POST['name']);
        if( D( 'EventPhoto' )->editName( $id,$name ) ) {
            echo 1;
        }else {
            echo 0;
        }
    }
    }
