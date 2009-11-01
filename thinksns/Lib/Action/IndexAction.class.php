<?php
class IndexAction extends BaseAction {


        var $api;
        var $site_opts;

        /**
         * _initialize
         *
         * 初始化
         *
         * @return void
         */
         function _initialize() {            
            $verify_allow = unserialize($this->opts["verify"]);
            $this->assign("login_verify_allow",$verify_allow['login']);
            if( $this->mid > 0 && in_array(strtoupper(ACTION_NAME),array('INDEX','REG','DOREG','LOGIN','DOLOGIN'))) {
                    $this->redirect("Home/index");
            }
        }

        public function userInfo() {
                $uid = $_GET['uid'];
                $out['rank'] = getUserRank($uid);
                $out['credit'] =getCredit($uid,$this->api);

                $user = $this->api->user_getInfo($uid,'name,id,cTime');
                $user['mini'] = $this->__getOneMini($uid);
                $userSearch = D( 'UserSearch' );
                $user['friends'] = $this->api->friend_areFriends($uid,$this->mid);
                $data = $userSearch->getGroupInfo( $this->mid,$this->uid );
                $login = $this->api->LoginRecord_getUserLogin($uid);
                $user['login_time'] = $login['login_time'];
                $this->assign($data);
                $this->assign($out);
                $this->assign('user',$user);
                $this->display('userinfo');
        }

        private function  __getOneMini($uid) {
                $bq_config = D('MiniConfig')->getConfig('mini');
                $bq_emotion = D('Smile')->getSmile($this->opts['ico_type']);
                return D("Mini")->getOneMini($uid,$bq_emotion,$this->opts['ico_type']);
        }
        public function notify() {
                set_time_limit(0);
                $page = D('Notify')->dochange(intval($_GET['p']));
        }

        public function notifyurl() {
                set_time_limit(0);
                D('Notify')->doChangeUrl();
        }
        public function feed() {
                set_time_limit(0);
                $page = D('Feed')->dochange(intval($_GET['p']));
                $this->redirect('/Index/feed/p/'.$page);
        }


        //网站首页
        public function index() {
        //推荐用户
                $userDao = D( 'User' );
                $command_user = $this->api->user_getCommendUser(16);
                if( $command_user ) {
                        $map['id'] = array( 'in',$command_user );
                        $user = $userDao->where( $map )->field( 'id,name,current_province,current_city' )->findAll();
                        foreach ( $user as &$value ) {
                                $value['friend_num'] = $this->api->friend_getFriNum( $value['id'] );
                        }

                        //网站动态
                        unset( $map );
                        //获取前4条动态数据
                        $map['uid'] = array( 'in',$command_user );
                        $feed = $this->api->feed_getFeed( $map,4 );
                //输出
                }else {
                //网站动态
                        $feed = $this->api->feed_getFeed( null,4 );
                }

                $this->assign( 'user',$user );
                $this->assign( 'feed',$feed );
                //公告
                $opts = $this->api->option_get( 'thinksns' );

                $this->assign( 'gonggao', D('Information')->getInfoData(array('category'=>10),3));

                $this->display('index');
        }

        /**
         * login
         *
         * 登陆页面
         *
         * @return void
         */
        public function login() {
                $this->display();
        }

        /**
         * doLogin
         *
         * 登陆操作
         *
         * @return void
         */
        public function doLogin() {
                $opts = D( 'Option' )->getOpts();
                $login_page = $this->__noLogin($opts);
                //    if( false !== strpos($_SERVER['HTTP_REFERER'],'/login') ){
                //        $login_page = 'login';
                //    }
                $login_page = 'login';

                //IP访问控制
                $site_opts = $this->api->option_get();
                ip_banned($site_opts["deny_ips"],$site_opts["allow_ips"]);

                //登陆验证码
                $verify_allow = unserialize($site_opts["verify"]);
                $login_verify_allow = $verify_allow['login'];

                if($login_verify_allow) {
                        if(md5($_POST['verify']) != $_SESSION['verify']) {
                                $this->redirect("/Index/".$login_page."/t/3");
                        }
                }


                $map["email"]  = $_POST["email"];
                $map["passwd"] = md5($_POST["passwd"]);

                $userDao = D("User");
                $user = $userDao->where($map)->field("id,name,active")->find();

                if($user) {
                        if($user["active"] || 0 == $opts['reg_email']) {
                        //修改最后一次登录IP
                                D("LoginRecord")->record($user["id"]);


                                unset($user["active"]);
                                $user["name"] = getValue($user["name"]);

                                $_SESSION["userInfo"] = serialize($user);
                                //如果选中记住
                                if($_POST["remembor"]) {

                                        $user_r["email"]  = $map["email"];
                                        $user_r["passwd"] = $map["passwd"];
                                        $user_r["agent"] = $_SERVER["HTTP_USER_AGENT"];

                                        //Vendor("3des");
                                        // $des	 =	new Crypt3DES;
                                        // $r_info = $des->encrypt(serialize($user_r));
                                        $r_info = serialize($user_r);
                                        Cookie::set('remembor',$r_info,36000000,'/');
                                }
                                //直接登陆或跳转到之前的用户要浏览的页面
                                setScore($user['id'], 'user_login');
                                if($log_refer = Session::get("log_refer")) {
                                        header("location:".$log_refer);
                                }else {
                                        $this->redirect("/Home/index");
                                }

                        }else {
                        //尚未激活
                                header("location:http://".$_SERVER['HTTP_HOST'].__URL__."/".$login_page."/t/2/uid/".$user["id"]."/email/".$_POST['email']);
                        }

                }else {
                        $this->redirect("/Index/".$login_page."/t/1");
                }
        }

        /**
         * logout
         *
         * 退出
         *
         * @return void
         */
        public function logout() {
                $mid = $this->mid;
                setcookie('remembor','',-1,'/');
                Cookie::delete('remembor');
                unset($_SESSION['userInfo']);
                setcookie("gonggao_{$mid}",'',-1,'/');
                Cookie::delete("gonggao_{$mid}");

                Session::clear();

                echo "<meta http-equiv='refresh' content='0;URL=".SITE_URL."/index.php'>";

        }



        /**
         * getPass
         *
         * 获取密码
         *
         * @return void
         */
        public function getPass() {
                $map["email"] = $_POST["email"];

                $user = D("User")->where($map)->find();


                if(!$user) {
                        $this->error("该邮箱没有注册!");
                }else {

                        $subject    =   "你在ThinkSNS的登录密码";
                        $user_id    =   $user["id"];
                        $name       =   $user["name"];
                        $pass       =   $user["passwd"];


                        $x = md5($user_id.'+'.$pass);
                        $code = base64_encode($user_id.".".$x);

                        $change_pass_url  =	"http://".$_SERVER['HTTP_HOST'].__URL__."/resetPass/p/".$code;


                        $body = <<<EOD
<strong>$name，你好：</strong><br/>
您只需通过点击下面的链接重置您的密码：<br/>
<a href="$change_pass_url">$change_pass_url</a><br/>
(如果上面不是链接形式，请将地址手工粘贴到浏览器地址栏再访问)<br/>
上面的页面打开后，输入新的密码后提交，之后您即可使用新的密码登录了。<br/><br/>
EOD;

                        $sr = send_email($_POST["email"],$subject,$body);
                        if($sr) {
                                $this->assign('jumpUrl',__APP__."/Index/login");
                                $this->success("我们已经把密码发送到你的邮箱$email中!");
                        }else {
                                $this->error("取回密码邮件发送失败!");
                        }
                }


        }

        /**
         * resetPass
         *
         * 重置密码
         *
         * @return void
         */
        public function resetPass() {
                $info = explode('.',base64_decode($_GET['p']));

                $map["id"] = $info[0];
                $dao = D("User");
                $user = $dao->where($map)->find();

                $checkCode = md5($info[0].'+'.$user["passwd"]);

                if($info[1] == $checkCode) {
                        $this->assign("name",$user["email"]);
                        $this->display();
                }else {
                        $this->error("错误的修改密码链接!");
                }

        }


        /**
         * doResetPass
         *
         * 修改密码
         *
         * @return void
         */
        public function doResetPass() {
                if($_POST["passwd"] != $_POST["repasswd"]) {
                        $this->error("两次输入密码不一样!");
                }

                $info = explode('.',base64_decode($_POST['p']));

                $map["id"] = $info[0];
                $dao = D("User");
                $user = $dao->where($map)->find();

                $checkCode = md5($info[0].'+'.$user["passwd"]);

                if($info[1] == $checkCode) {
                        $dao->passwd = md5($_POST["passwd"]);
                        $r = $dao->save();
                        if($r) {
                                $this->assign('jumpUrl',__APP__."/Index/login");
                                $this->success("密码修改成功!");
                        }else {
                                $this->error("修改密码失败!");
                        }
                }else {
                        $this->error("修改密码失败!");
                }


        }



        /**
         * reg
         *
         * 注册页面
         *
         * @return void
         */
        public function reg() {

        //IP访问控制
                $site_opts = $this->api->option_get();
                ip_banned($site_opts["deny_ips"],$site_opts["allow_ips"]);

                //注册验证码
                $verify_allow = unserialize($site_opts["verify"]);
                $this->assign("reg_verify_allow",$verify_allow['reg']);

                //是否关闭注册
                if($site_opts["reg_close"] == "1") {
                        $this->error("注册已经关闭!");
                }



                //邀请人信息
                if($_GET["code"]) {
                        $code = jiemi($_GET["code"]);
                        $code = json_decode($code);


                        $fuid = $code[0];
                        $fusername = $code[1];
                        $fgid = $code[2];
                        $gid = $code[3];
                        //print_r($code);
                        $code = !empty($code) ? $_GET['code'] : '';
                        $this->assign('code', $code);

                        if(isset($_GET['uid'])) {  //好友邀请

                                if($code) {
                                        $this->assign('fuid',$fuid);
                                        $this->assign('frinds',$this->api->friend_get($fuid));

                                        $this->assign('gname',D('Group')->getField('name','id='.intval($_GET['gid'])));
                                        $this->display('reg_invite');
                                        exit;
                                }else {
                                        $this->error('错误的邀请验证码');
                                }
                        }
                }



                if($site_opts["reg_invite_close"] == "1" && !$code) {
                        $this->error("目前只接受邀请注册，请向您已注册的朋友索要邀请链接!");
                }

                $this->display();
        }

        /**
         * doReg
         *
         * 注册
         *
         * @return void
         */
        public function doReg() {

			//IP访问控制
			$site_opts = $this->api->option_get();
			ip_banned($site_opts["deny_ips"],$site_opts["allow_ips"]);


			//是否关闭注册
			if($site_opts["reg_close"] == "1") {
					$this->error("注册已经关闭!");
			}

			//注册验证码
			$verify_allow = unserialize($site_opts["verify"]);
			$reg_verify_allow = $verify_allow['reg'];

			if($reg_verify_allow) {
					if(md5($_POST['verify']) != $_SESSION['verify']) {
							$this->error('验证码错误!');
					}
			}

			if(empty($_POST['email']) || empty($_POST['name'])){
				$this->error('邮箱或用户名不能为空！');
			}
			if(strlen($_POST['name'])>20){
				$this->error('用户名不能太长！');
			}
			if(strlen($_POST['passwd'])<=5 || $_POST['passwd']!=$_POST['repasswd']){
				$this->error('密码不正确，建议您得密码设置为五位以上！');
			}

			//看是否注册过了
			$map_xx["email"] = t($_REQUEST["email"]);
			$user_count = D("User")->where($map_xx)->count();
			//if($user_count != 0) $this->error("你的Email已经被注册过了!");

			$current = explode(",", $_POST["ts_areaval"]);
			$_POST["current_province"] = $current[0];
			$_POST["current_city"]     = $current[1];
			$_POST["current_area"]     = $current[2];

			$_POST["passwd"] = md5($_POST["passwd"]);

			$userDao = D("User");
			$userDao->create();

			$privacy = $_POST["baseinfoprivacy"];
			$userDao->cTime = time();
			$userDao->active = ( "1" == $site_opts['reg_email'] ) ? 0:1;

			$uid = $userDao->add();

			$this->__addUserSearch( $uid );


			$code = $_POST['code'];
			if($uid && $site_opts["reg_email"] == "1") {
					$this->jihuo($uid,$_POST["email"],$code);
			}else {
			//登陆
				$userInfo["id"] = $uid;
				$userInfo["name"] = $userDao->name;
				$_SESSION["userInfo"] = serialize($userInfo);
				$this->relation($code,$uid);  //默认关联系统操作

				//跳转
				$msg = "注册成功!";
				$jump_url = __APP__."/Info/face";
				$this->assign('jumpUrl',$jump_url);
				$this->success($msg);
			}

        }


        /**
         * reSendEmail
         *
         * 重新发送激活邮件
         *
         * @return void
         */
        public function reSendEmail() {
                $email  =    $_GET["email"];
                $uid    =    $_GET["uid"];
                //发送激活email
                $this->jihuo($uid,$email,true);
        }



        /**
         * jihuo
         *
         * @param mixed $uid
         * @param mixed $email
         * @param mixed $re
         * @return void
         */
        public function jihuo($uid,$email,$inviteCode = '',$re=false) {
        //发送激活email
        //从配置中取出配置内容
                $opts   = $this->api->option_get();
                $option['smtp']     = $opts['email_stmp'];
                $option['port']     = $opts['email_port'];
                $option['username'] = $opts['email_address'];
                $option['password'] = $opts['email_password'];
                $option['site_name'] = $opts['site_name'];

                $jh_url  = "http://".$_SERVER['HTTP_HOST'].__APP__."/Index/active/code/".jiami($uid);
                if($inviteCode) $jh_url .= "/inviteCode/$inviteCode";

                //$subject = iconv("utf-8","gbk","ThinkSNS给您发来的注册激活邮件。");
                $subject = str_replace( '{SITE_NAME}',$opts['site_name'],$opts['email_subject'] );

                //解析模板标签
                $patterns= array( "/{SEX}/","/{SITE_NAME}/","/{URL}/" );

                //获得性别判断
                $uid  =  abs(intval($uid));
                $info =  D("User")->field( 'sex' )->find($uid);

                $sex   = explode("-",$info['sex']);
                $r_sex = $sex[0] ? "先生":"女士";

                $replacements = array( $r_sex,$opts['sit_name'],$jh_url );
                $body = stripslashes(preg_replace( $patterns,$replacements,$opts['email_body'] ));

                $sr = send_email($email,$subject,$body,"HTML",$option);

                if($sr) {
                //显示待激活页面
                        $email_info = explode("@",$email);
                        switch($email_info[1]) {
                                case "qq.com"  :    $email_url = "mail.qq.com";break;
                                default        :    $email_url = "www.".$email_info[1];
                        }

                        $this->assign("email",$email);
                        $this->assign("email_url",$email_url);
                        $this->assign("uid",$uid);
                        $this->assign("re",$re);

                        $this->display("jihuo");
                }else {
                        $this->error("发送激活邮件失败!");
                }


        }



        /**
         * active
         *
         * 激活账号
         *
         * @return void
         */
        public function active() {
                $code	=    htmlspecialchars($_GET['code']);
                $new    =    $_GET["new"];
                $uid    =    jiemi($code);
                $uid    =    intval($uid);

                if($uid && is_numeric($uid)) {
                        $dao = D("User");
                        $r = $new?1:$dao->setField("active",1,"id=$uid");

                        if($r) {

                                $msg = "您的账号已经激活成功!";
                                $jump_url = __APP__."/Info/face";

                                //如果是修改，则替换原来的email
                                if($new) {
                                        $dao->setField("email",$new,"id=$uid");
                                        $msg = "您的新账号已经激活成功!";
                                        $jump_url = __APP__."/Home";
                                }

                                //默认关联系统后台配置
                                $this->relation($_GET['inviteCode'],$uid);

                                //登陆
                                $userInfo = $dao->field("id,name")->find($uid);
                                $_SESSION["userInfo"] = serialize($userInfo);
                                //跳转
                                setScore($uid, 'email_active');
                                $this->assign('jumpUrl',$jump_url);
                                $this->success($msg);

                        }else {
                                $this->assign('jumpUrl',__APP__."/Index/reg");
                                $this->success("激活失败!");
                        }
                }else {
                        $this->assign('jumpUrl',__APP__."/Index/reg");
                        $this->success("激活失败!!");
                }

        }





        /**
         * checkEmail
         *
         * 检测邮箱是否被占用
         *
         * @return void
         */
        public function checkEmail() {
                $map["email"] = $_REQUEST["email"];
                $user = D("User")->where($map)->count();
                echo $user == 0 ?  "success": "邮箱已被占用";
        }

        /**
         * checkVerify
         *
         * 检测验证码是否正确
         *
         * @return void
         */
        public function checkVerify() {
                echo (md5($_REQUEST['verify']) == $_SESSION['verify']) ?  "success": "验证码输入有误，请重输！";
        }

        /**
         * checkRealName
         *
         * 检查是否实名
         *
         * @return void
         */
        public function checkRealName() {
                $name	=	unescape($_REQUEST['name']);

                //获取配置中的单姓和复性
                $opts = D( 'Option' )->getOpts();
                if( 0 == $opts['reg_checkname'] ) {
                        $danxing = $opts['reg_danxing'];
                        $fuxing  = $opts['reg_fuxing'];
                }else {
                        echo "success";
                        return true;
                }
                $d	=	explode(',',$danxing);
                $f	=	explode(',',$fuxing);
                //单姓
                $name_d	=	substr($name,0,3);
                //复姓
                $name_f	=	substr($name,0,6);
                if( in_array($name_d,$d) ) {
                        echo "success";
                }else if( in_array($name_f,$f) ) {
                                echo  "success";
                        }else {
                                echo  "请输入真实姓名";
                        }
        }


        /**
         * school
         *
         * @access public
         * @return void
         */
        function school() {


                $dao	=	D('Schools');
                //省列表
                $provinceList	=	$dao->field("distinct(province) as p")->order("p DESC")->findAll();

                //大学列表
                $this->assign('provinceList',$provinceList);
                $this->display();
        }


        /**
         * university
         *
         * @access public
         * @return void
         */
        function university() {
                $dao	=	D('Schools');
                if($_POST['province']) {
                        $map['province']	=	$_POST['province'];
                }else {
                        $map['province']	=	'北京';
                }
                $map['type']	=	'university';
                //大学列表
                $list		=	$dao->where($map)->field('id,school')->findAll();
                $this->assign('list',$list);
                $this->display();
        }


        /**
         * verify
         *
         * @return void
         */
        public function verify() {
                import("ORG.Util.Image");
                Image::buildImageVerify();
        }


        /**
         * 获取消息计数
         * @return void
         */
        public function getMsgCount() {
                $uid = intval($_POST['uid']);
                $notify_num = $this->api->notify_getNewNum($uid,null,'json');
                echo $notify_num;
        }

        /**
         * __addUserSearch
         * 插入到用户查询数据库
         * @access private
         * @return void
         */
        private function __addUserSearch($uid) {
                $array['birthday']            = intval($_POST['birthday_year']).'-'.intval($_POST['birthday_month']).'-'.intval($_POST['birthday_day']);

                $ts_areaval                   = $this->__paramAddress($_POST['ts_areaval']);
                $array['ts_areaval']          = $ts_areaval[0];
                $array['extra']['ts_areaval'] = $ts_areaval[1];

                $array['sex']                  = $_POST['sex'];
                foreach( array( 'birthday','ts_areaval','sex' ) as $value ) {
                        $array['__display_'.$value] = 0;
                        $array['__privacy_'.$value] = $_POST['baseinfoprivacy'];
                }

                $dao = D( 'UserSearch' );
                $searchMap = $this->__paramData( $array );
                $dao->setUid( $uid );
                $dao->editInfo( $searchMap );
                $searchMap['sex'][2] = $searchMap['sex'][2]?"男":"女";
                $this->__infoAddCache( $searchMap,$uid,'info' );
        }

        private function __paramData( $data,$default = false) {
        //取得字段名
                $keys = array_keys( $data );
                //字段更新
                $update_data  = array_filter( $keys,array( $this,'filter' ) );  //具体的个人资料

                if( !$default ) {
                        foreach ( $update_data as $value ) {
                        //用户自定义的数据隐私为默认
                                if( "more" == $value ) {
                                        $temp_privacy =  0;
                                        $temp_display =  1;
                                }else {
                                        $temp_privacy =  $data['__privacy_'.$value];
                                        $temp_display =  $data['__display_'.$value];
                                }
                                $searchMap[$value] = array($temp_privacy,$temp_display,$data[$value],$data['extra'][$value] );
                        //if( empty( $data[$value] ) ) unset( $searchMap[$value] );
                        }
                }else {
                        foreach ( $update_data as $value ) {
                        //TODO 隐私默认设置
                                $temp_privacy =  0;
                                $temp_display =  1;
                                $searchMap[$value] = array($temp_privacy,$temp_display,$data[$value],$data['extra'] );
                        }
                }
                return $searchMap;
        }

        private function __paramAddress($data) {
                $result[] = $data;
                $result[] = explode( ',',$data );
                return $result;
        }

        public function filter( $key ) {
                if( false !== strpos( $key,"__" ) ) return false;
                if( 'extra' == $key ) return false;
                return true;
        }

        public function casual(  ) {
                $this->display();

        }

        /**
         * __infoCache
         * 信息缓存到info表里面
         * @param mixed $data
         * @param mixed $type
         * @access private
         * @return void
         */
        private function __infoAddCache( $data,$uid,$type) {
                $type = strtolower( $type );
                $dao = D( 'UserInfo' );
                if( $dao->where( 'uid='.$uid )->find() ) {
                        $this->__infoUpdataCache( $data,$uid,$type );
                }else {
                        $map[$type] = serialize( $data );
                        $map['uid'] = $uid;
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
        private function __infoUpdataCache( $data,$uid,$type ) {
                $map[$type]       = serialize( $data );
                $condition['uid'] = $uid;
                $dao = D( 'UserInfo' );
                $dao->where( $condition )->save($map);
        }

        private function __noLogin( $options ) {
                if( 1 == $options['guest'] ) {
                        return "index";
                }else {
                        return "login";
                }
        }


        //系统默认关联函数
        function relation($code,$uid) {
                $Group = D('Group');
                $Friend = D('Friend');
                $User = D('User');

                D("LoginRecord")->record($user["id"]);
                $code = jiemi($code);
                $code = json_decode($code);
                $fuid = 0;
                $gid = 0;
                
                if($code) {
                        $fuid = $code[0];
                        $fusername = $code[1];
                        $fgid = $code[2];
                        $gid = $code[3];

                        D("Friend")->makeFriend($fuid,$fusername,$fgid,$uid,getUserName($uid),1);
						//添加动态
						$title['fuid'] = $fuid;
						$title['fuser'] = getUserName($fuid);
						$title['uid'] = $uid;
						$title['user'] = getUserName($uid);
						$title['site_name'] = $this->opts['site_name'];
						$this->api->feed_publish('invite_reg',$title,$body='');
                        //添加积分
                        setScore($fuid,'invite_reg');
						
						
                        if($gid > 0) {
                                D('Group')->joingroup($uid,$gid,3,$incMemberCount=true);
                        }

                }

                $relationFriend = explode(',',$this->opts['reg_relation_friend']);  //朋友关联
                $relationGroup = explode(',',$this->opts['reg_relation_group']);   //群众默认关联

                if(!empty($relationFriend) && is_array($relationFriend)) {
                        foreach($relationFriend as $v) {
                                $v  = intval($v);

                                if($User->where('id='.$v)->count()) {

                                        if($fuid != $v) $Friend->makeFriend($v,getUserName($v),1,$uid,getUserName($uid),1);
                                }
                        }
                }

                if(!empty($relationGroup) && is_array($relationGroup)) {
                        foreach($relationGroup as $v) {
                                $v  = intval($v);

                                if($Group->where('id='.$v.' AND is_del=0')->count()) {
                                        if($gid != $v) $Group->joingroup($uid,$v,3,$incMemberCount=true);
                                }
                        }
                }
        }
        
        
        //获取地区显示页面
        function getNetwork(){
        	$list = $this->api->Network_getList();;
            $arrPid = explode(',',$_POST['pid']);
            $level  = $_POST['level'];
            
            if($level=='init'){
            	$this->assign('arealevel',intval($_POST['arealevel']));
            	$this->assign('init','1');
            	$this->assign('arrPid',$arrPid);
            }else{
	            if(is_array($arrPid)){
					unset($arrPid[0]);
		            foreach ($arrPid as $v){
		            	if($list[$v]['child']){
		            		$list = $list[$v]['child'];
		            	}
		            }
	            }
            }
        	$this->assign('list',$list);
        	$this->display();
		}
}
?>
