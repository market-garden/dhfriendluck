<?php
class InfoAction extends BaseAction{


        public function _initialize(){
            //参数转义
            new_addslashes($_POST);
            new_addslashes($_GET);
            $_POST = $this->__filterLabel( $_POST );
            parent::_initialize();
        }
    /*
     * 1、-------------------------基本资料----------------------------------
     *
     */
    function index() {
        $info = $this->__setAssign();
        if( empty( $info['name'] ) ) $info['name'] = $this->my_name;
        $this->assign( 'info',$info );
        $this->display();
    }


    /*
     * 修改基本资料
     *
     */
    function doIndex(){
        $_POST['name'] = trim( t($_POST['name']) );
        if( empty($_POST['name']) && isset( $_POST['name'] ) ) $this->error( '姓名必须填写' );
        //添加一条动态

        $temp_post = array();

        //处理居住和家乡地址
        if( !empty( $_POST['ts_areaval'] ) ){
            $ts_areaval= $this->__paramAddress( $_POST['ts_areaval'] );
            $_POST['ts_areaval'] = $ts_areaval[0];
            $_POST['extra']['ts_areaval'] = $ts_areaval[1];
        }else{
            unset( $_POST['ts_areaval'] );
        }

        if( !empty( $_POST['ts_hometown'] ) ){
            $ts_hometown= $this->__paramAddress( $_POST['ts_hometown'] );
            $_POST['ts_hometown'] = $ts_hometown[0];
            $_POST['extra']['ts_hometown'] = $ts_hometown[1];
        }else{
            unset( $_POST['ts_hometown'] );
        }


        $_POST["birthday"]         = intval($_POST['birthday_year']).'-'.intval($_POST['birthday_month']).'-'.intval($_POST['birthday_day']);
        unset( $_POST['birthday_year'] );
        unset( $_POST['birthday_month'] );
        unset( $_POST['birthday_day'] );


        $this->_addInfoFeed($_POST);
        $searchMap = $this->__paramData( $_POST );
        $temp_post = $_POST;
        //重组数据
        unset( $_POST );
        $_POST['name'] = $temp_post['name'];
        $_POST['sex']  = $temp_post['sex'];

        $dao = D("User");

        $r = $dao->create();
        if(false === $r) $this->error($dao->getError());
        $dao->id = $this->mid;

        $result = $dao->save();
        if( $result ){
            $changeMap['uid'] = $this->mid;
            $changeMap['name'] = $_POST['name'];
            $this->__changeFriendName( $changeMap );
        }

        $userInfo = unserialize($_SESSION['userinfo']);
        $userInfo['name'] = t($_POST['name']);
        $_SESSION['userinfo'] = serialize($userInfo);

        $userInfo = unserialize($_SESSION['userInfo']);
        $userInfo['name'] = t($_POST['name']);
        $_SESSION['userInfo'] = serialize($userInfo);

		//更新搜索用数据
        $searchDao = D( 'UserSearch' );
        $searchDao->setUid( $this->mid );
        $searchDao->editInfo( $searchMap );
        $searchMap['sex'][2] = $searchMap['sex'][2]?"男":"女";
        unset( $searchMap['name'] );
        $this->__infoAddCache( $searchMap,'info' );
        $this->redirect("Info/index/t/1");

    }


    /*
     * 添加Info动态
     *
     */
    function _addInfoFeed($posts) {
		return false;
        if(isset( $posts['ts_hometown'] )){
            $posts['ts_hometown'] = getAreaInfo( $posts['ts_hometown'],$posts['ts_hometown'] );
        }
        if(isset( $posts['ts_areaval'] )){
            $posts['ts_areaval']  = getAreaInfo( $posts['ts_areaval'],$posts['ts_areaval'] );
        }
        unset( $posts['extra'] );
        foreach($posts as $name=>$v){
            if(strpos($name,"__") !== 0 && strpos($name,"old_") !== 0){
                //if(strcmp($posts["old_".$name],$post[$name]))
                $update[$name] = $v;
            }
        }


        if($update["name"]){
            //更新session
            $user["id"] = $this->mid;
            $user["name"] = $update["name"];
            $_SESSION["userInfo"] = serialize($user);
        }

        foreach($update as $name=>$v){
            if($name == "sex") $v = $v?"男":"女";
            if(getFieldName($name)) $feed_title .= "将".getFieldName($name)."改为 \"".$v."\"，";
        }

        $feed_title = rtrim($feed_title,"，");

        if($feed_title){
            $title_data["content"] = $feed_title;
            $feedId = $this->api->feed_publish("info",$title_data,$body_data);
        }
    }


    /*
     * 2、-------------------------个人情况-------------------------------------
     *
     */
    function intro() {

        //1、要显示的项目
        $item[] = array("display"=>"我想结交","name"=>"jiejiao");
        $item[] = array("display"=>"兴趣爱好","name"=>"interest");
        $item[] = array("display"=>"喜欢的书","name"=>"book");
        $item[] = array("display"=>"喜欢的电影","name"=>"film");
        $item[] = array("display"=>"偶像","name"=>"idol");
        $item[] = array("display"=>"座右铭","name"=>"motto");
        $item[] = array("display"=>"最近心愿","name"=>"wish");
        $item[] = array("display"=>"我的简介","name"=>"summary");



        //dump($item);
        $this->assign("item",$item);



        //2、如果已有值
        $this->__setAssign();




        $this->display();
    }

    /*
     * 修改个人情况
     *
     */
    function doIntro() {

        $more = array();
        $_POST['more_item'] = array_filter( $_POST['more_item'] );
        $_POST['more_con'] = array_filter( $_POST['more_con'] );
        //for( $i=0;$i<count( $_POST['more_item'] );$i++ ){
        foreach (  $_POST['more_item'] as $i=>$value ){
            $more[$i]['name']    = $value;
            $more[$i]['privacy'] = $_POST['__privacy_more'][$i];
            $more[$i]['display'] = isset($_POST['__display_more'][$i])?$_POST['__display_more'][$i]:0;
            $more[$i]['value']   = $_POST['more_con'][$i];
        }


        $_POST['more'] = serialize($more);
        unset ( $_POST['more_item'] );
        unset ( $_POST['more_con'] );
        unset( $_POST['__privacy_more'] );
        unset( $_POST['__display_more'] );
        $searchMap = $this->__paramData( $_POST );

        //更新搜索用数据
        $searchDao = D( 'UserSearch' );
        $searchDao->setUid( $this->mid );
        $searchDao->editInfo( $searchMap );

        //更新info表将数据缓存
        $this->__infoAddCache( $searchMap,'Intro' );
        $this->redirect("Info/intro/t/1");

    }




   /*
     * 3、-------------------------联系信息------------------------------
     *
     */
    function contact() {

        //1、要显示的项目
        $item[] = array("display"=>"地址","name"=>"address");
        $item[] = array("display"=>"邮编","name"=>"postcode");
        $item[] = array("display"=>"电话","name"=>"phone");
        $item[] = array("display"=>"手机","name"=>"cellphone");
        $item[] = array("display"=>"QQ","name"=>"qq");
        $item[] = array("display"=>"MSN","name"=>"msn");


        //dump($item);
        $this->assign("item",$item);




        //2、如果已有值
        $this->__setAssign();

        $this->display();
    }




    /*
     * 修改联系信息
     *
     */
    function doContact() {
        //$_POST = t( $_POST );
         //添加一条动态
        $this->_addInfoFeed($_POST);

        unset($_POST["is_update"]);
        $searchMap = $this->__paramData( $_POST );

        //更新搜索用数据
        $searchDao = D( 'UserSearch' );
        $searchDao->setUid( $this->mid );
        $searchDao->editInfo( $searchMap );

        //更新info表缓存数据
        $this->__infoAddCache( $searchMap,'contact' );
        $this->redirect("Info/contact/t/1");
    }


    /*
     * 4、教育工作信息
     *
     */
    function education() {
        //$dao = D("EduWork");
        //$data = $dao->where("uid=$this->mid AND type= 'education'")->order("year asc")->findAll();
        $this->__setExtraAssing(array( 'uid'=>$this->mid ),"year ASC",'edu');
        //$this->assign("data",$data);
        $this->display();
    }

    function career() {
        $this->__setExtraAssing(array( 'uid'=>$this->mid ),"begin ASC",'career');
        $this->display();
    }

    public function doEdu(){
        if( count( array_filter( $_POST ) )< count( $_POST ) ) $this->error( "所有信息必须填写" );
        $type = t( $_POST['type'] );
        unset( $_POST['type'] );
        //重组数据
        $map['privacy'] = 0;
        $map['home']    = 1;
        $map['uid']     = $this->mid;
        $map            = array_merge( $map,$_POST );
        unset($map['thinksns_html_token']);

        $eduSearch = D( 'EduSearch' );
        $result = $eduSearch->add( $map );

        $cache = $eduSearch->where( 'uid='.$this->mid )->findAll();
        $this->__infoAddCache( $cache,'education' );
        $this->redirect("Info/education/t/1");
    }

    /**
     * doWork
     * 工作处理代码
     * @access public
     * @return void
     */
    public function doWork(  ){
        if( count( array_filter( $_POST ) )< count( $_POST ) ) $this->error( "所有信息必须填写" );
        $type = t( $_POST['type'] );
        unset( $_POST['type'] );
        //重组数据
        $map['privacy'] = 0;
        $map['home']    = 1;
        $map['uid'] = $this->mid;
        $_POST['begin'] = mktime( 0,0,0,$_POST['beginmonth'],1,$_POST['beginyear'] );
        $_POST['end']   = !isset( $_POST['nowworkflag'] ) ? mktime( 0,0,0,$_POST['endmonth'],1,$_POST['endyear'] ):0;
        if( $_POST['begin'] > $_POST['end'] && !isset( $_POST['nowworkflag'] ) ) $this->error( '结束时间不能小于开始时间' );
        //过滤无关数据
        unset( $_POST['beginyear'] );
        unset( $_POST['beginmonth'] );
        unset( $_POST['endyear'] );
        unset( $_POST['endmonth'] );
        unset( $_POST['nowworkflag'] );
        $map = array_merge( $map,$_POST );
        unset($map['thinksns_html_token']);
        $WorkSearch = D( 'WorkSearch' );
	$map['name'] = $this->my_name;
        $WorkSearch->add( $map );
        $cache = $WorkSearch->where( 'uid='.$this->mid )->findAll();
        $this->__infoAddCache( $cache,'career' );
        $this->redirect("Info/career/t/1");
    }

    /*
     * 设置学校工作信息的权限
     *
     */
    function doSetEdu() {
        //$_POST = t( $_POST );
        $type = t($_POST['type']);
        unset( $_POST['type'] );
        $keys = array_keys( $_POST['__privacy_school'] );
        $searchDao = "career" == $type ?D( 'WorkSearch' ):D( 'EduSearch' );
        foreach ( $keys as $value ){
            $condition['id'] = $value;
            $map['privacy'] = $_POST['__privacy_school'][$value];
            $map['home'] = isset($_POST['__display_school'][$value])?$_POST['__display_school'][$value]:0;
            //更新搜索用数据
            $searchDao->where( $condition )->save( $map );
        }
        $searchMap = $searchDao->where( 'uid='.$this->mid )->findAll();
        $this->__infoAddCache( $searchMap,$type );
        $this->redirect( 'Info/'.$type.'/t/1' );
    }



    /*
     * 删除某条教育信息
     *
     */
    function delEdu() {
        if( 'career' == $_POST['type'] ){
            $dao = D("WorkSearch");
        }else{
            $dao = D("EduSearch");
        }

        $map["id"] = $_POST["id"];
        $map["uid"] = $this->mid;

        echo $dao->where($map)->delete();
        $data = $dao->where( 'uid='.$this->mid )->findAll();
        $this->__infoAddCache( $data,$_POST['type'] );
    }



   /*
     * temp
     *
     */
    function temp() {
        $item[] = array("display"=>"地址","name"=>"address");
        $item[] = array("display"=>"邮编","name"=>"postcode");
        $item[] = array("display"=>"电话","name"=>"phone");
        $item[] = array("display"=>"手机","name"=>"cellphone");
        $item[] = array("display"=>"QQ","name"=>"qq");
        $item[] = array("display"=>"MSN","name"=>"msn");

        foreach($item as $k=>$v){
            $data["field"] = $v["name"];
            $data["name"]  = $v["display"];
            D("FieldName")->add($data);
        }
    }

	public function face() {
		//IM和头像截取冲突，先关闭IM
		$this->assign('is_im_closed',true);
		$this->display();
	}
	public function doUploadFaceImg() {
		//上传头像附件
		$info	=	$this->api->attach_upload('face');
		//处理输出
		if(!$info['status']){
			echo "<script language='javascript'>alert('".$info['info']."')</script>";
			$this->redirect('uploadFaceImg');
		} else {
			$uploadfile	=	UPLOAD_URL.$info['info'][0]['savepath'].$info['info'][0]['savename']."?".time();
			echo '<script language="javascript">parent.insertImg1("'.$uploadfile.'");parent.$("#bigImage").val("'.$uploadfile.'");</script>';
		}
	}


	//保存用户头像
	function saveThumb() {
		//头像大方快的宽高
		$targ_w	=	120;
		$targ_h	=	120;

		//头像小方块的宽高
		$small_w	=	50;
		$small_h	=	50;

		//图像质量
		$jpeg_quality	=	80;

		$src_arr		=	explode("?",$_POST['bigImage']);
        $src			=	$src_arr[0];
		$src			=	str_ireplace(SITE_URL,'.',$src);

		//获取图片的扩展名。来选择使用什么函数
		if(	$arr = @getimagesize($src)	){
			$ext = image_type_to_extension($arr[2],false);
		} else {
			$this->error('对不起,GD库不存在或远程图片不存在');
		}
		$func = ($ext != 'jpg')?'imagecreatefrom'.$ext:'imagecreatefromjpeg';
		$img_r = call_user_func($func,$src);

		//开始切割大方块头像

		$dst_r	=	ImageCreateTrueColor( $targ_w, $targ_h );
		$x		=	$targ_h/$_POST['txt_Zoom'];
		imagecopyresampled($dst_r,$img_r,0,0,$_POST['txt_left']/$_POST['txt_Zoom'],$_POST['txt_top']/$_POST['txt_Zoom'],$targ_w,$targ_h,$x,$x);

		$path		=	SITE_PATH."data/thumb/";
		$filename	=	$path.'xxx_s.jpg';
        $face_path  =	getFacePath($this->mid);
		mkdir($face_path,0777,true);
        $middle_name =	$face_path.$this->mid."_middle_face.jpg";     //中图
		imagejpeg($dst_r,$middle_name);  //生成中图
		imagedestroy($dst_r);
		imagedestroy($img_r);

		$small_name  = $face_path.$this->mid."_small_face.jpg";     //小图
		vendor("yu_image");
		$img = new yu_image();
		$img->param($middle_name)->thumb($small_name,$small_w,$small_h,0);        //缩出小图

        //添加一条动态
		$body_data["src"] = getUserFace($this->mid);
		$this->api->feed_publish("head",$title_data,$body_data);
                setScore($this->mid, 'update_face');
		$this->redirect("/Home/index");
	}

    public function filter( $key ){
        if( false !== strpos( $key,"__" ) ) return false;
        if( 'extra' == $key ) return false;
        return true;
    }

    public function filterPrivacy( $key ){
        if( false !== strpos( $key,"privacy" ) ) return false;

        return true;
    }

    private function __paramData( $data,$default = false){
        //取得字段名
        $keys = array_keys( $data );
        //字段更新
        $update_data  = array_filter( $keys,array( $this,'filter' ) );  //具体的个人资料

        if( !$default ){
            foreach ( $update_data as $value ){
                //用户自定义的数据隐私为默认
                if( "more" == $value ){
                    $temp_privacy =  0;
                    $temp_display =  1;
                }else{
                    $temp_privacy =  $data['__privacy_'.$value];
                    $temp_display =  isset($data['__display_'.$value])?1:0;
                }
                $searchMap[$value] = array($temp_privacy,$temp_display,$data[$value],$data['extra'][$value] );
                //if( empty( $data[$value] ) ) unset( $searchMap[$value] );
            }
        }else{
            foreach ( $update_data as $value ){
                //TODO 隐私默认设置
                $temp_privacy =  0;
                $temp_display =  1;
                $searchMap[$value] = array($temp_privacy,$temp_display,$data[$value],$data['extra'] );
            }
        }
        return $searchMap;
    }

    private function __setAssign(){
        $dao = D("UserSearch");

        $dao->setUid( $this->mid );
        $infoData = $dao->getInfo(true,$map);

        //重组数据
        $info = $privacy = $display = array();

        foreach($infoData as $k=>$v){
           $info[$k]  = $v[0];
           $privacy[$k] = $v[1];
           $display[$k] = $v[2];
        }
        if ( isset( $infoData['ts_areaval'] ) ){
            $info['current_province'] = $infoData['ts_areaval'][4];
            $info['current_city']     = $infoData['ts_areaval'][5];
        }

        if ( isset( $infoData['ts_hometown'] ) ){
            $info['home_province'] = $infoData['ts_hometown'][4];
            $info['home_city']     = $infoData['ts_hometown'][5];
        }

        //扩展的数据类容
        if( isset( $infoData['more'] ) ){
            $add_more = unserialize( $infoData['more'][0] );
            $this->assign( "add_more",$add_more );
        }

        $this->assign("privacy",$privacy);
        $this->assign("display",$display);
        $this->assign("info",$info);
        return $info;
    }

    private function __setExtraAssing( $map,$order,$type ){
        if( 'career' == $type ){
            $dao = D("WorkSearch");
            $infoData = $dao->where( $map )->order( $order )->findAll();

            //重组数据
            $class = $year = $id = $info = $privacy = $display = array();
            foreach($infoData as $k=>$v){
               $id[$k]      = $v['id'];
               $info[$k]    = $v['company'];
               $privacy[$k] = $v['privacy'];
               $display[$k] = $v['home'];
               $class[$k]   = $v['position'];
               $year[$k]    = date( 'Y年m月',$v['begin'] ).'-'.date('Y年m月',0==$v['end']?time():$v['end']);;
            }
        }else{
            $dao = D("EduSearch");
            $infoData = $dao->where( $map )->order( $order )->findAll();
            //重组数据
            $class = $year = $id = $info = $privacy = $display = array();
            foreach($infoData as $k=>$v){
               $id[$k]      = $v['id'];
               $info[$k]    = $v['school'];
               $privacy[$k] = $v['privacy'];
               $display[$k] = $v['home'];
               $class[$k]   = $v['class'];
               $year[$k]    = $v['year'];
            }
        }



        $this->assign("privacy",$privacy);
        $this->assign("display",$display);
        $this->assign("info",$info);
        $this->assign("id",$id);
        $this->assign( "year",$year );
        $this->assign( "class",$class );
    }

    /**
     * __infoCache
     * 信息缓存到info表里面
     * @param mixed $data
     * @param mixed $type
     * @access private
     * @return void
     */
    private function __infoAddCache( $data,$type){
        $type = strtolower( $type );
        $dao = D( 'UserInfo' );
        if( $dao->where( 'uid='.$this->mid )->find() ){
            $this->__infoUpdataCache( $data,$type );
        }else{
            $map[$type] = serialize( $data );
            $map['uid'] = $this->mid;
            $dao->add( $map );
        }
    }

    /**
     * __infoUpdataCache
     * 信息缓存更新
     * @param mixed $data
     * @param mixed $type
     * @access private
     * @return void
     */
    private function __infoUpdataCache( $data,$type ){
        $map[$type]       = serialize( $data );
        $condition['uid'] = $this->mid;
        $dao = D( 'UserInfo' );
        $dao->where( $condition )->save($map);
    }


    private function __filterLabel( $data ){
        foreach( $data as &$value ){
            if( is_array($value) ){
                foreach( $value as &$v ){
                    $v = t( $v );
                }
            }else{
                $value = t( $value );
            }
        }
        return $data;

    }

    private function __paramAddress($data){
        $result[] = $data;
        $result[] = explode( ',',$data );
        return $result;
    }
    /**
     * __changeFriendName
     * 修改好友列表里面的名字
     * @param mixed $data
     * @access private
     * @return void
     */
    private function __changeFriendName( $input_data ){
        //初始化函数内部变量
        $data = $input_data;
        $nameSql = array();

        //构造查询和修改条件
        $condition['fuid'] = $data['uid'];
        $map['fname']      = $data['name'];
        $menu_map['fusername'] = $data['name'];
        $visitor_condition['visitId'] = $data['uid'];
        $visitor_map['name'] = $data['name'];

        //选择数据库并执行
        D( 'FriendBlack' )->where( $condition )->save( $map );
        D( 'FriendHide' )->where( $condition )->save( $map );
        D( 'Friend' )->where( $condition )->save( $menu_map );
        D( 'Visitor' )->where( $visitor_condition )->save( $visitor_map );
    }

    function school(){

    }
}
?>
