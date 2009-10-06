<?php

class SystemAction extends BaseAction {

    /*
     * 站点设置
     *
     */
        public function index() {

                $opt = D("Option")->getOpts4Edit();
                import('ORG.Io.Dir');
                $pDir = new Dir(SITE_PATH.'/public/themes/');
                $this->assign('themelist',$pDir->toArray());
                $this->assign("verify",unserialize($opt["verify"]));
                $this->assign("newuser_time",$opt["newuser_time"]);
                $this->assign("newuser_fri_num",$opt["newuser_fri_num"]);
                $this->assign("opt",$opt);
                $this->display();
        }

    /*
     * 保存站点设置
     *
     */
        function doIndex() {

                $dao = D("Option");


                //上传logo
                if($_FILES['logo']['size'] > 0) {
                //判读类型
                        @unlink(THEME_PATH.'/images/logo.jpg');
                        $info		=	$this->api->attach_upload('photo',array('save_path'=>THEME_PATH.'/images/','save_name'=>'logo.jpg'));
                }
                foreach($_POST as $k=>$v) {
                        $dao->setField("value",$v,"name='".$k."' AND appname='thinksns'");
                }

                //更新缓存
                $opt = D("Option")->updateCache();

                $this->redirect("index");

        }


    /*
     * 隐私设置
     *
     */
        function privacy() {

        // $xxx = ts_cache("site_options");


                $opt = D("Option")->getOpts4Edit();
                $this->assign("allow_ips",$opt["allow_ips"]);
                $this->assign("deny_ips",$opt["deny_ips"]);
                $this->assign("privacy",unserialize($opt["privacy"]));

                // dump(unserialize($opt["privacy"]));
                $this->display();
        }


    /*
     * 保存隐私设置
     *
     */
        function doPrivacy() {
        //        dump($_POST);
        //        return;


        //IP正则组合
                $allow_ips_arr = explode("\n",$_POST["allow_ips"]);
                foreach($allow_ips_arr as $k=>$v) {
                        $v = trim($v);
                        $vv = str_replace("*", "\d+", $v);
                        $vv = str_replace(".", "\.", $vv);
                        $allow_ips .= $vv."|";
                }

                $allow_ips = rtrim($allow_ips,"|");


                $deny_ips_arr = explode("\n",$_POST["deny_ips"]);
                foreach($deny_ips_arr as $k=>$v) {
                        $v = trim($v);
                        $vv = str_replace("*", "\d+", $v);
                        $vv = str_replace(".", "\.", $vv);
                        $deny_ips .= $vv."|";
                }

                $deny_ips = rtrim($deny_ips,"|");


                //存到库里
                $dao = D("Option");
                $dao->setField("value",$allow_ips,"name='allow_ips' AND appname='thinksns'");
                $dao->setField("value",$deny_ips,"name='deny_ips' AND appname='thinksns'");
                $dao->setField("value",serialize($_POST["privacy"]),"name='privacy' AND appname='thinksns'");

                //更新缓存
                $opt = D("Option")->updateCache();


                $this->redirect("privacy");
        }

    /*
     * 注册设置
     *
     */
        function reg() {

                $opt = D("Option")->getOpts4Edit();


                $this->assign("reg_invite_close",$opt["reg_invite_close"]);
                $this->assign("reg_close",$opt["reg_close"]);
                $this->assign("fri_tuijian",$opt["fri_tuijian"]);
                $this->assign("fri_dongtai",$opt["fri_dongtai"]);
                $this->assign("reg_tiaokuan",$opt["reg_tiaokuan"]);
                $this->assign("reg_email",$opt["reg_email"]);
                $this->assign( "reg_danxing",$opt['reg_danxing'] );
                $this->assign( "reg_fuxing",$opt['reg_fuxing'] );
                $this->assign( "reg_checkname",$opt['reg_checkname'] );
                $this->assign('reg_relation_friend',$opt['reg_relation_friend']);
                $this->assign('reg_relation_group',$opt['reg_relation_group']);

                $this->display();
        }


    /*
     *  保存注册设置
     *
     */
        function doReg() {

        // dump($_POST);
        //return;
        //存到库里
                $dao = D("Option");

                foreach($_POST as $k=>$v) {
                        $dao->setField("value",$v,"name='".$k."' AND appname='thinksns'");
                }



                //更新缓存
                $opt = D("Option")->updateCache();


                $this->redirect("reg");

        }



    /*
     * 邀请设置
     *
     */
        function invite() {

                $opt = D("Option")->getOpts4Edit();


                $this->assign("invite_content",$opt["invite_content"]);


                $this->display();
        }

    /*
     *  保存邀请设置
     *
     */
        function doInvite() {

        // dump($_POST);
        //return;
        //存到库里
                $dao = D("Option");

                foreach($_POST as $k=>$v) {
                        $dao->setField("value",$v,"name='".$k."' AND appname='thinksns'");
                }



                //更新缓存
                $opt = D("Option")->updateCache();


                $this->redirect("invite");

        }

        /**
         * email
         * 激活邮箱设置。
         * @access public
         * @return void
         * @author SamPeng
         */
        function email() {
                $opt = D("Option")->getOpts4Edit();


                $this->assign("email_stmp",$opt["email_stmp"]);
                $this->assign("email_port",$opt["email_port"]);
                $this->assign("email_address",$opt["email_address"]);
                $this->assign("email_password",$opt["email_password"]);
                $this->assign("email_subject",$opt["email_subject"]);
                $this->assign("email_body",$opt["email_body"]);

                $this->display();
        }

        function doEmail() {
        //检测和过滤
                if( 0 == intval($_POST['email_port']) ) {
                        $this->error( '错误的邮箱端口' );
                }

                if( false === strpos( $_POST['email_body'],'{URL}' ) ) {
                        $this->error( '邮箱正体缺乏激活链接标签{URL}' );
                }

                if( false == preg_match("/^([\w{1,}])([\w-]*(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i" , $_POST['email_address'])) {
                        $this->error( "邮箱地址不合法" );
                }


                //存到库里
                $dao = D("Option");

                foreach($_POST as $k=>$v) {
                        $dao->setField("value",$v,"name='".$k."' AND appname='thinksns'");
                }


                //更新缓存
                $opt = D("Option")->updateCache();


                $this->redirect("email");
        }




   /*
     * 动态设置
     *
     */
        function feed() {

                $opt = D("Option")->getOpts4Edit();
                $privacy = unserialize($opt["feed_privacy"]);
                $this->assign("privacy",$privacy);
                $this->display();
        }


    /*
     *  保存动态设置
     *
     */
        function doFeed() {


        //存到库里
                $dao = D("Option");


                $privacy = serialize($_POST["privacy"]);


                $dao->setField("value",$privacy,"name='feed_privacy' AND appname='thinksns'");


                //更新缓存
                $opt = D("Option")->updateCache();


                $this->redirect("feed");

        }


    /*
     * 审核设置
     *
     */
        function auditing() {

                $opt = D("Option")->getOpts4Edit();

                $this->assign("gfw_enable",$opt["gfw_enable"]);
                $this->assign("gfw_keywords",$opt["gfw_keywords"]);
                $this->assign("gfw_rep",$opt["gfw_rep"]);

                $this->display();
        }



    /*
     *  保存审核设置
     *
     */
        function doAuditing() {


        //存到库里
                $dao = D("Option");

                foreach($_POST as $k=>$v) {
                        $dao->setField("value",$v,"name='".$k."' AND appname='thinksns'");
                }


                //更新缓存
                $opt = D("Option")->updateCache();


                $this->redirect("auditing");

        }


    /*
     * 公告设置
     *
     */
        function gonggao() {
                $opt = D("Option")->getOpts4Edit();
                $this->assign("gonggao",$opt["gonggao"]);
                $this->assign("gonggao_open",$opt["gonggao_open"]);
                $this->display();
        }


    /*
     * 设置公共
     *
     */
        function doGonggao() {
        //存到库里
                set_magic_quotes_runtime(0);
                //		dump($_POST);
                //		 dump(stripslashes($_POST["content"]));
                //		 return;
                //
                $dao = D("Option");

                $dao->setField("value",stripslashes($_POST["content"]),"name='gonggao' AND appname='thinksns'");
                $dao->setField("value",stripslashes($_POST["gonggao_open"]),"name='gonggao_open' AND appname='thinksns'");

                //更新缓存
                $opt = D("Option")->updateCache();


                $this->redirect("/System/gonggao/t/1");
        }




    /*
     * 添加广告页
     *
     */
        public function ad_add() {

                if($id = $_GET["id"]) {

                        $ad = D("Ad")->find($id);
                        $this->assign("ad",$ad);

                }else {
                        $ads = D("Ad")->field("place")->findAll();
                        foreach($ads as $k=>$v) {
                                $places[] = $v["place"];
                        }
                        $this->assign("places",$places);
                }

                $this->display();
        }



    /*
     * 添加广告
     *
     */
        function doAddAd() {

                set_magic_quotes_runtime(0);

                $dao = D("Ad");

                $r = $dao->create();
                if(false === $r) $this->error($dao->getError());

                if($dao->id) {
                        $dao->save();
                }else {
                        $id = $dao->add();
                }


                $this->redirect("ad_list");
        }



    /*
     * ad列表
     *
     */
        function ad_list() {
                $dao = D("Ad");

                $data = $dao->where($map)->order("id desc")->findAll();

                $this->assign("ads",$data);


                $this->display();
        }

	/*
	 * 删除广告
	 *
	 */
        public function delAd() {
        // 根据id删除指定的记录
                $dao = D("Ad");
                $id = $_REQUEST["id"];
                $id_arr = explode(",",$id);

                foreach($id_arr as $k=>$v) {
                        if(is_numeric($v)) {
                                $dao->delete($v);
                        }
                }

                $this->redirect("ad_list");
        }



    /*
     * 上传公告里的图片
     *
     */
        function up_edit_img() {
                $attachdir = "./data/tip";//上传文件保存路径，结尾不要带/
                $state=$this->_uploadfile('upload',$attachdir);
                echo json_encode($state);
        }


    /*
     * 上传AD里的图片
     *
     */
        function up_ad_img() {
                $attachdir = "./data/ad";//上传文件保存路径，结尾不要带/
                $state=$this->_uploadfile('upload',$attachdir);
                echo json_encode($state);
        }


   /*
     * 添加链接
     *
     */
        public function links_add() {

                if($id = $_GET["id"]) {

                        $link = D("Links")->find($id);
                        $this->assign("link",$link);

                }

                $this->display();
        }


    /*
     * 链接列表
     *
     */
        function links_list() {


                $dao = D("Links");

                $links = $dao->where($map)->order("sort asc")->findAll();
                $this->assign("links",$links);

                $this->display();
        }



    /*
     * 增加链接
     *
     */
        function doLinks() {


                $dao = D("Links");

                $r = $dao->create();
                if(false === $r) $this->error($dao->getError());

                if($dao->id) {
                        $dao->save();
                }else {
                        $id = $dao->add();
                }

                $this->redirect("links_list");
        }

    /*
     * 删除links
     *
     */
        function doDelLinks() {
                $ids = $_GET["ids"];

                $ids_arr = explode(",",$ids);
                foreach($ids_arr as $k=>$v) {
                        D("Links")->delete($v);
                }

                $this->redirect("links_list");

        }

    /*
     * 设置link状态
     *
     */
        function doShenLinks() {
                $dao = D("Links");

                $ids = $_GET["ids"];
                $ids_arr = explode(",",$ids);


                foreach($ids_arr as $k=>$v) {
                        $dao->setField("status",$_GET["status"],"id=".$v);
                }

                $this->redirect("links_list");
        }

    /*
     * 设置link顺序
     *
     */
        function doSortLinks() {
                $dao = D("Links");

                $sort = $_POST["sort"];

                foreach($sort as $id=>$s) {
                        $dao->setField("sort",$s,"id=".$id);
                }

                $this->redirect("links_list");
        }


	/*
	 * 举报列表
	 *
	 */
        function report() {

                $dao = D("Report");

                $data = $dao->where($map)->order("cTime desc")->findPage(10);

                foreach ($data['data'] as $key=>$value) {
                        $data['data'][$key]['url'] = $this->api->APP_getAppInfo($value['appid'],'APP_URL').$value['url'];
                }

                $this->assign("reprots",$data["data"]);
                $this->assign("page",$data["html"]);

                $this->display();



        //dump($data["data"]);

        //		echo "<table>";
        //		echo "<tr>";
        //		echo "<td>举报人</td>";
        //		echo "<td>链接</td>";
        //        echo "<td>原因</td>";
        //		echo "<td>举报时间</td>";
        //		echo "</tr>";
        //
        //		foreach($data["data"] as $key=>$v){
        //			echo "<tr>";
        //			echo "<td>".getUserName($v["uid"])."</td>";
        //			echo "<td><a href='".$v["url"]."'>".getShort($v["info"],30)."</a></td>";
        //            echo "<td>".$v["reason"]."</td>";
        //			echo "<td>".friendlyDate($v["cTime"])."</td>";
        //			echo "</tr>";
        //		}
        //
        //		echo "</table>";
        }

        function friend() {
                $friendGroup = D( 'FriendGroup' );
                $category = $friendGroup->getCategory();
                $this->assign( 'category_list',$category );
                $this->display();
        }


        public function doDeleteCategory() {
                $id['id']      = array( "in",$_POST['id']);
                $category = D( 'FriendGroup' );
                if( $result = $category->deleteCategory( $id ) ) {
                        if( strpos( $_POST['id'],',' ) === false ) {
                                echo 1;
                        }else {
                                echo 2;
                        }
                }else {
                        echo -1;
                }
        }
        /**
         * doAddCategory
         * 修改分类
         * @access public
         * @return void
         */
        public function doAddCategory() {
                $category = D( 'FriendGroup' );
                if( $result   = $category->addCategory( $_POST ) ) {
                        echo $result ;
                }else {
                        echo -1;
                }
        }

        public function doEditCategory() {
                $category = D( 'FriendGroup' );
                if( $result   = $category->editCategory( $_POST['name'] ) ) {
                        $this->redirect('friend');
                }else {
                        $this->error( "修改失败" );
                }

        }
        function _uploadfile($inputname,$attachdir) {

                if(!checkDir($attachdir."/")) {
                        return '目录创建失败: '.$attachdir;
                }
                $dirtype=1;//1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
                $maxattachsize=2097152;//最大上传大小，默认是2M

                $err = "";
                $msg = "";
                $upfile=$_FILES[$inputname];
                if(!empty($upfile['error'])) {
                        switch($upfile['error']) {
                                case '1':
                                        $err = '文件大小超过了php.ini定义的upload_max_filesize值';
                                        break;
                                case '2':
                                        $err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
                                        break;
                                case '3':
                                        $err = '文件上传不完全';
                                        break;
                                case '4':
                                        $err = '无文件上传';
                                        break;
                                case '6':
                                        $err = '缺少临时文件夹';
                                        break;
                                case '7':
                                        $err = '写文件失败';
                                        break;
                                case '8':
                                        $err = '上传被其它扩展中断';
                                        break;
                                case '999':
                                default:
                                        $err = '无有效错误代码';
                        }
                }
                elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none') {
                        $err = '无文件上传';
                }
                else {
                        $temppath=$upfile['tmp_name'];
                        $attachinfo= @getimagesize($temppath);
                        if($attachinfo[2]==IMAGETYPE_GIF||$attachinfo[2]==IMAGETYPE_JPEG||$attachinfo[2]==IMAGETYPE_PNG) {
                                $extension=image_type_to_extension($attachinfo[2],false);
                                $filesize=filesize($temppath);
                                if($filesize <= $maxattachsize) {
                                        switch($dirtype) {
                                                case 1: $attach_subdir = 'day_'.date('ymd'); break;
                                                case 2: $attach_subdir = 'month_'.date('ym'); break;
                                                case 3: $attach_subdir = 'ext_'.$extension; break;
                                        }
                                        $attach_dir = $attachdir.'/'.$attach_subdir;
                                        if(!is_dir($attach_dir)) {
                                                @mkdir($attach_dir, 0777);
                                                @fclose(fopen($attach_dir.'/index.htm', 'w'));
                                        }
                                        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
                                        $filename=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
                                        $target2 = $attach_dir.'/'.$filename;

                                        $attachdir = ltrim($attachdir,".");
                                        $target = C("TS_URL").$attachdir."/".$attach_subdir."/".$filename;

                                        move_uploaded_file($upfile['tmp_name'],$target2);
                                        $msg=$target;
                                }
                                else $err='文件大小超过'.$maxattachsize.'字节';
                        }
                        else $err='文件格式必需为以下格式：jpg,gif,png';

                        @unlink($temppath);
                }
                return array('err'=>$err,'msg'=>$msg);
        }


        function update() {
        //    	$pFeed= D('Feed');
        //    	$map ="type IN ('group_album','group_file','group_photo','group_topic')";
        //    	$grouplist = $pFeed->where($map)->field('appid,id')->findall();
        //    	foreach ($grouplist as $v){
        //    		$t = explode('_',$v['appid']);
        //    		$date['fid'] = $t[1];
        //    		$maps['id'] = $v['id'];
        //
        //    		$pFeed->where($maps)->save($date);
        //    		unset($date);
        //    		unset($maps);
        //    	}
        //    	$feedArr = array(
        //    		'0' => "'app_add','head','info'",
        //    		'4' => "'blog'",
        //    		'14' => "'event'",
        //    		'3' => "'mini'",
        //    		'10' => "'photo'",
        //    		'11' => "'share_album','share_blog','share_event','share_flash','share_group','share_music','share_photo','share_picture','share_topic','share_url','share_user','share_video','share_vote'",
        //   		'12' =>"'group_album','group_file','group_photo','group_topic'",
        //    		'13' =>"'vote_add','vote_in'",
        //    	);
        //
        //    	//更新动态数据
        //    	foreach ($feedArr as $key=>$value){
        //    		$date['appid'] = intval($key);
        //    		$pFeed->where("type in (".$value.")")->save($date);
        //    	}
        //    	echo '动态更新完成<br>';

                $pNotify = D('Notify');
                $notifyArr = array(
                    '0' => "'add_friend','agree_friend','comment_comment'",
                    '11' => "'share_notice','share_comment'",
                    '3' => "'mini_comment'",
                    '12' => "'group','group_reply','group_topic_quote'",
                    '4' => "'blog_mention','blog_comment'",
                    '13' => "'vote_comment','vote_in'",
                    '14' => "'event_comment'",
                    '10' => "'photo_comment'",
                );

                //更新通知数据
                foreach ($notifyArr as $nkey=>$nvalue) {
                        $ndate['appid'] = intval($nkey);
                        $pNotify->where("type in (".$nvalue.")")->save($ndate);
                }
                echo '通知更新完成<br>';

        }


        //
        function updateshare() {
        //动态数据
                $list = D('FeedTemplate')->query("SELECT id,type,body_data FROM `ts_feed` WHERE type like '%share%'");
                $pFeed = D('Feed');
                foreach ($list as $v) {
                        $id = $v['id'];

                        $body = unserialize(stripcslashes($v['body_data']));

                        foreach ($body as $k=>$v1) {
                                $body[$k]  = str_replace('SITE_URL','WR',$v1);
                        }

                        $date['body_data'] = serialize($body);
                        $map['id']  = $v['id'];
                        $pFeed->where($map)->save($date);
                        unset($date);
                        unset($map);
                        dump($res);
                        dump($pFeed->getLastSql());
                }


        //			分享数据
        //
        //			$list = $pFeed->query("SELECT id,url,data FROM ts_share");
        //			foreach ($list as $v){
        //				$data = unserialize($v['data']);
        //
        //				foreach ($data as $k=>$v1){
        //					$d = str_replace('SITE_URL','WR',$v1);
        //					$data[$k] = str_replace('__ROOT__','WR',$d);
        //				}
        //				$data = serialize($data);
        //				$u = str_replace('SITE_URL','WR',$v['url']);
        //				$url = str_replace('__ROOT__','WR',$u);
        //				$id = $v['id'];
        //				$res = $pFeed->query("UPDATE `ts_share` SET url='$url', data='$data'  WHERE id='$id'");
        //	       }
        }

        //积分规则列表
        function scores() {
                $dao = D('CreditSetting');
                $dao2 = D('CreditType');
                $list	=	$dao->findAll();
                //得到所有的字段名
                $fields = $dao->getTableFields();
                //获得所有的类型缓存
                $type = $dao2->getCreditType();
                $type_list = $dao2->findAll();
                //重新组类型。供显示用
                $default_array = array('id'=>'编号','action'=>'动作','info'=>'提示信息','type'=>'种类','actioncn'=>'动作中文别名');
                foreach($fields as $key=>$value) {
                        if(isset($type[$value])) {
                                $new_fields[$value] = $type[$value];
                        }
                        if(isset($default_array[$value])) {
                                $new_fields[$value] = $default_array[$value];
                        }
                }

                $this->assign('typeList',$type_list);
                $this->assign('fields',$new_fields);
                $this->assign('list',$list);
                $this->display();
        }

        function doAddScoresType() {
                $type = t($_REQUEST['type']);
                $alias  = t($_REQUEST['alias']);
                if( false !== D('CreditSetting')->addCreditType($type,$alias)) {
                        $this->success('添加分数种类成功');
                }else {
                        $this->error('积分种类不能重复');
                }
        }

        //添加积分规则
        function doAdd() {
                $dao = D('CreditSetting');
                $array = array('action'=>'动作','info'=>'提示信息','actioncn'=>'动作中文别名');
                $fields = $dao->getTableFields();

                //过滤POST中的空格
                foreach ($_POST as $key => &$value) {
                        if(isset($array[$key])) {
                                $data[$key] = trim($value);
                        }else {
                                $data[$key] = intval($value);
                        }
                }
                //过滤和产生查询条件
                foreach ($fields as $value) {
                        if($value == 'id' || $value == 'type') continue;
                        $new_value = $data[$value];
                        //默认初始化的字符串
                        if(isset($array[$value])) {
                                if(empty($new_value)) $this->error("{$array[$value]}必填");
                        }else {
                                if(!is_numeric($new_value)) {
                                        $this->error('积分只能是数字');
                                }
                        //积分
                        }
                        $map[$value] = $new_value;
                }
                $map['type'] = 'user';
                $result	=	$dao->add($map);
                if($result) {
                        $this->success('添加积分设置成功！');
                }else {
                        $this->success('添加积分设置失败！');
                }
        }

        //修改积分规则
        function doEdit() {
                $dao = D('CreditSetting');
                $fields = $dao->getTableFields();
                $array = array('action','info','type','actioncn');
                $id       = intval($_POST['id']);
                //过滤POST中的空格
                foreach ($_POST as $key => &$value) {
                        if(in_array($key,$array)) {
                                $data[$key] = trim($value);
                        }else {
                                $data[$key] = intval($value);
                        }
                }

                //过滤和产生查询条件
                foreach ($fields as $value) {
                        if(!isset($data[$value] ) ) {
                                echo "{$vlaue}选项必填";
                                exit;
                        }
                        if('id' == $value) continue ;
                        $map[$value] = $data[$value];
                }
                $result	=	 $dao->where('id='.$id)->save($map);
                if($result) {
                        echo "1";
                }else {
                        echo "保存失败！";
                }
        }

        //修改积分规则
        function doEditAll() {
                $dao = D('CreditSetting');
                $fields = $dao->getTableFields();
                foreach ($fields as $field) {
                        foreach($_POST[$field] as $key=>$v) {
                                $credit[$key][$field] = $v;
                        }
                }
                //保存
                $dao = D('CreditSetting');
                foreach($credit as $k=>$map) {
                        $result[]	=	$dao->where("id=".$k)->save($map);
                }
                $this->redirect('scores');
        }


        public function deleteCredit() {
                echo $this->doDelete(intval($_POST['id']),D('CreditSetting'));
        }
        public function deleteCreditType() {
                $id = intval($_POST['id']);
                //删除积分规则中的字段
                $dao = D('CreditSetting');
                $dao2 = D('CreditType');
                $type_info =  $dao2->where('id='.$id)->field('name')->find();
                //删除字段
                $dao->doDeleteFields($type_info['name']);
                echo $this->doDelete($id,$dao2);
                //重置缓存
                $dao2->setCache();
        }
        private function doDelete($id,$dao) {
                if(empty($id)) {
                        return -1;
                }
                $dao->where('id='.$id)->delete();
                return 1;
        }
        function doEditTypeAll() {
                foreach ($_POST['name'] as $k=>$v) {
                        $type[$k]['name'] = $v;
                }
                foreach($_POST['alias'] as $k=>$v) {
                        $type[$k]['alias'] = $v;
                }
                $dao = D('CreditSetting');
                foreach ($type as $id => $map) {
                        $result[] =  $dao->editType($id,$map);
                }
                $this->redirect('scores');
        }



        public function doEditType() {
                $id = intval($_POST['id']);
                $map['name'] = trim($_POST['name']);
                $map['alias']   = trim($_POST['alias']);
                //修改
                $dao = D('CreditSetting');
                if($dao->editType($id,$map)) {
                        echo 1;
                }else {
                        echo -1;
                }
        }


        /**
         * ico
         * 图像设置
         * @access public
         * @return void
         */
        public function ico () {
                $smilDao = D('Smile');
                //获取数据库的表情列表
                $smiletype     =  $smilDao->getSmileType() ;
                $this->assign( 'smiletype' , $smiletype );
                $this->display();
        }


        public function smile() {
                $smilDao = D('Smile');
                if( !isset( $_GET['type'] ) ) {
                        $type = $_POST['type'];
                        //检查合法性
                        if( empty( $type ) )
                                $this->error( "没有填写表情分类" );

                        if( $smilDao->where( "type='{$type}'" )->getField( 'id' ) )
                                $this->error( $type."已经存在" );

                        $smilelist = $smilDao->getSmileFileList( $type );
                        if( !$smilelist )
                                $this->error( $type."不存在表情根目录下" );
                        $smilelist['type'] = $type;
                        $smilDao->addSmile( $smilelist );
                        $smilelist = $smilDao->getSmileList( $type );
                }else {
                        $type      = $_GET['type'];
                        $smilelist = $smilDao->getSmileList($type);
                        if( isset( $_GET['doUpdate'] ) ) {
                                $smilelist1 = $smilDao->getSmileFileList( $type );
                                $smilelist = $smilDao->filterData( $smilelist,$smilelist1,$type );
                        }
                //去处已经添加的
                }

                $path      = __URL__."/doAddSmile/";

                $this->assign( 'smile_list',$smilelist );
                $this->assign( 'action_path',$path );
                $this->assign( 'smilepath',__PUBLIC__.'/images/biaoqing/'.$type.'/' );
                $this->assign( 'smiletype',$type );
                $this->display(  );
        }


        /**
         * doEditSmile
         * 编辑表情
         * @access public
         * @return void
         */
        public function doEditSmile( ) {
                $smilDao = D('Smile');
                if( count( array_filter( $_POST['emotion'] ) ) < count( $_POST['emotion'] ) ) {
                        $this->error( "修改失败，不允许表情缩写为空" );
                        exit;
                }

                if( count( array_filter( $_POST['title'] ) ) < count( $_POST['title'] ) ) {
                        $this->error( "修改失败，不允许表情标题为空" );
                        exit;
                }
                if( $smilDao->editSmile( $_POST['emotion'],$_POST['title'],$_POST['type'] ) ) {
                        $this->redirect( '/System/smile/type/'.$_POST['type'] );
                }else {
                        $this->error( "修改失败" );
                }
        }

        /**
         * doAddSmile
         * 添加表情
         * @access public
         * @return void
         */
        public function doAddSmile() {
                $smilDao = D('Smile');
                $result = $smilDao->addSmile( $_POST,true );
                if( count( array_filter($_POST) ) < count( $_POST ) )
                        $this->error( "添加失败，轻完整填写" );
                if(true == $result) {
                        $this->redirect( '/System/smile/type/'.$_POST['type'] );
                }elseif( 'has_file' == $reuslt ) {
                        $this->error( "添加失败，和现有文件名重名。" );
                }else {
                        $this->error( "添加失败，请检查文件是否存在于{$_POST['type']}文件夹下" );

                }
        }


        public function doDeleteSmileType() {
                if( !isset( $_POST['type'] ) && empty( $_POST['type'] ) ) {
                        echo -1;
                        exit();
                }
                $map['type'] = array( 'in',$_POST['type']);
                if( D('Smile')->where( $map )->delete() ) {
                        if( !strpos( $_POST['type'],',' ) ) {
                                echo 1;
                        }else {
                                echo 2;
                        }
                }else {
                        echo -1;
                }
        }


       /**
         * doChangeIco
         * 删除表情
         * @access public
         * @return void
         */
        public function doDeleteSmile(){
            $id     = $_POST['id'];
            $type   = $_POST['type'];
            $idlist = explode( ',',$id );
            $smile = D( 'Smile' );

            if( $smile->deleteSmile( $idlist,$type ) ){
                echo -1;
                exit();
            }
            if( !strpos( $_POST['id'],',' ) ){
                    echo 1;
            }else{
                echo 2;
            }
        }
}
?>
