<?php
/**
 * 用户管理中心
 *
 */
class UserAction extends BaseAction {

        public function msg () {
                $group = D('SystemGroup')->getGroupList();
                $this->assign('group',$group);
                $this->display();
        }
        public function doSend() {
        //获取用户登陆ID
                $this->mid     =    $this->api->user_getLoggedInUser();
                if($_POST['type'] == 1) {
                        $this->doSendMsg();
                }else {
                        $this->doSendEmail();
                }
                $this->assign('jumpUrl',__URL__.'/msg');
                $this->success('发送成功');
        }
        public function doSendMsg() {
                $toUserIds =$this->__GroupGetUser();

                $dao = D("Msg");
                $r = $dao->create();
                if(false === $r) $this->error($dao->getError());

                $dao->cTime = time();
                $dao->fromUserId = $this->mid;
                $title_data['title'] = $dao->subject;
                foreach($toUserIds as $uid) {
                        $dao->toUserId = $uid;
                        $r = $dao->add();
                        //通知
                        $cate = "message";
                        $this->api->notify_send($uid,$type,$title_data,$body_data,$url,$cate);
                }
                return true;
        }

        public function doSendEmail() {
                $toUids = $this->__GroupGetUser();
                $dao = D('User');
                $title = $_POST['subject'];
                $content = $_POST['content'];
                $mid = $this->mid;
                foreach ($toUids as $value) {
                        $temp = $dao->where('id='.$value)->field('email')->find();
                        $email[] = $temp['email'];
                }
                return $this->api->SaveEmail_saveEmail($email,$title,$content,$mid);
        }
        private function __GroupGetUser() {
                $gid = $_POST['gid'];
                return $toUserIds = D('User')->getGroupUsers($gid);
        }
        public function userscore(){
                  $setScore = $this->setCredit();
                $this->assign('setScore',$setScore);
                $this->assign('grounlist',D('SystemGroup')->findall());
                $this->display();
        }
    /*
     * 用户列表
     *
     */
        public function index() {
                $pUser = D('User');
                $setScore = $this->setCredit();
                $this->assign('setScore',$setScore);


                //查询条件
                if(!empty($_REQUEST['name'])) $map['name'] = array('like','%'.$_REQUEST['name'].'%');
                if(!empty($_REQUEST['uid']))  $map['id']   = array('in',explode(',',$_REQUEST['uid']));
                if(!empty($_REQUEST['email']))  $map['email']   = $_REQUEST['email'];
                if(!empty($_REQUEST['commend'])) $map['commend']   = intval($_REQUEST['commend']);
                if($_REQUEST['status']!='9') $map['active']        = $_REQUEST['status'] ;
                if($_REQUEST['groupid']!='9') $map['admin_level']  = $_REQUEST['groupid'] ;

                if(!empty($_REQUEST['bDate'])) {
                //开始和结束时间
                        $bdate = explode('-',$_REQUEST['bDate']);
                        $begindate = mktime(0,0,0,$bdate[1],$bdate[2],$bdate[0]);
                        if(empty($_REQUEST['eDate'])) {
                                $enddate = time();
                        }else {
                                $edate = explode('-',$_REQUEST['eDate']);
                                $enddate = mktime(0,0,0,$edate[1],$edate[2]+1,$edate[0]);
                        }
                        $map['cTime'] = array('in',array($begindate,$enddate));
                }


                //排序
                $field = ($_REQUEST['field'])?$_REQUEST['field']:'id';
                $order = ($_REQUEST['order'])?$_REQUEST['order']:'DESC';
                $limit = ($_REQUEST['limit'])?$_REQUEST['limit']:'20';

                $arrOrder['field'] = $field;
                $arrOrder['order'] = $order;
                $arrOrder['limit'] = $limit;

                if($_POST) {
                        Session::set('userSearch',serialize($map));
                        Session::set('userOrder',serialize($arrOrder));
                        $sorder = $arrOrder;
                }elseif (isset($_GET['p'])) {
                        if(Session::get('userSearch')) {
                                $map = unserialize(Session::get('userSearch'));
                                $sorder = unserialize(Session::get('userOrder'));
                        }else {
                                $map = '';
                                $sorder = $arrOrder;
                        }
                }else {
                        $map = '';
                        $sorder = $arrOrder;
                        unset($_SESSION['userSearch']);
                        unset($_SESSION['userOrder']);
                }

                $this->assign('grounlist',D('SystemGroup')->findall());
                $this->assign('commend',$map['commend']);
                $this->assign('name',$_REQUEST['name']);
                if($map['id']) {
                        $this->assign('uid',implode(',',$map['id'][1]));
                }

                $this->assign('email',$map['email']);

                $this->assign('groupid',$map['admin_level']);
                $this->assign('status',$map['active']);
                if(!empty($_REQUEST['bDate'])) {
                        $this->assign('bDate',date('Y-m-d',$map['cTime'][1][0]));
                        $this->assign('eDate',date('Y-m-d',$map['cTime'][1][1]));
                }

                $this->assign('field',$sorder['field']);
                $this->assign('order',$sorder['order']);
                $this->assign('limit',$sorder['limit']);
                $list = $pUser->where( $map )->field( '*' )->order( $sorder['field'].' '.$sorder['order'] )->findPage($sorder['limit']) ;

                $this->assign('pages',$list['html']);
                $this->assign('list',$list['data']);
                $this->assign('count',$list['count']);
                $this->display();
        }
        public function doSetCredit(){
                                //查询条件
              set_time_limit(0);
                if(!empty($_REQUEST['name'])) $map['name'] = array('like','%'.$_REQUEST['name'].'%');
                if(!empty($_REQUEST['uid']))  $map['id']   = array('in',explode(',',$_REQUEST['uid']));
                if(!empty($_REQUEST['email']))  $map['email']   = $_REQUEST['email'];
                if(!empty($_REQUEST['commend'])) $map['commend']   = intval($_REQUEST['commend']);
                if($_REQUEST['status']!='9') $map['active']        = $_REQUEST['status'] ;
                if($_REQUEST['groupid']!='9') $map['admin_level']  = $_REQUEST['groupid'] ;

                if(!empty($_REQUEST['bDate'])) {
                //开始和结束时间
                        $bdate = explode('-',$_REQUEST['bDate']);
                        $begindate = mktime(0,0,0,$bdate[1],$bdate[2],$bdate[0]);
                        if(empty($_REQUEST['eDate'])) {
                                $enddate = time();
                        }else {
                                $edate = explode('-',$_REQUEST['eDate']);
                                $enddate = mktime(0,0,0,$edate[1],$edate[2]+1,$edate[0]);
                        }
                        $map['cTime'] = array('in',array($begindate,$enddate));
                }
                $user = D('User')->where($map)->field('id')->findAll();
                if($user == false){
                        $this->error('查询失败，没有这样条件的人');
                }
                $credit['credit'] = $_POST['credit'];
                $credit['action'] = 'sys_add';
                $credit['actioncn'] = '系统操作积分';
                $credit['info'] = trim($_POST['info']);
                foreach($user as $value){
                        $result[] = setUserScore($value['id'], $credit);
                }
                if (count(array_filter($result) ) == count($user)) {
                        $this->success('调整积分成功');
                }else{
                        $this->error('操作失败');
                }
        }
        public function setCredit() {
                $dao = D('CreditSetting');
                $dao2 = D('CreditType');
                //得到所有的字段名
                $fields = $dao->getTableFields();
                //获得所有的类型缓存
                $type = $dao2->getCreditType();
                foreach($fields as $key=>$value) {
                        if(isset($type[$value])) {
                                $new_fields[$value] = $type[$value];
                        }
                }
                return $new_fields;
        }

        /**
         * 编辑用户
         */
        public function edit() {
                $intId = intval($_GET['id']);
                $pUser = D('User');
                $tinfo = $pUser->find($intId);
                if($tinfo && $intId) {
                        $dao = D("UserSearch");
                        $dao->setUid( $intId );
                        $infoData = $dao->getInfo(true,$map);
                        //重组数据
                        $info = $privacy = $display = array();

                        foreach($infoData as $k=>$v) {
                                $info[$k]  = $v[0];
                                $privacy[$k] = $v[1];
                                $display[$k] = $v[2];
                        }
                        if ( isset( $infoData['ts_areaval'] ) ) {
                                $info['current_province'] = $infoData['ts_areaval'][4];
                                $info['current_city']     = $infoData['ts_areaval'][5];
                        }

                        if ( isset( $infoData['ts_hometown'] ) ) {
                                $info['home_province'] = $infoData['ts_hometown'][4];
                                $info['home_city']     = $infoData['ts_hometown'][5];
                        }
                        $info['id']    = $tinfo['id'];
                        $info['email'] = $tinfo['email'];

                        $this->assign('group',D('SystemGroup')->findall());
                        $this->assign('info',$info);
                        $this->assign('tinfo',$tinfo);

                        $this->display();
                }else {
                        $this->error('提交错误参数');
                }
        }

        //修改用户资料
        public function doedit() {
                $intId = intval($_POST['id']);
                $pUser = D('User');
                $info = $pUser->where('id='.$intId)->find();
                if(!$info || !$intId) {
                        $this->error('提交错误参数');
                        exit;
                }

                switch ($_POST['type']) {
                        case 'accounts':  //帐户设置
                                $strEmail = h($_POST['email']);
                                $strPassword = h($_POST['password']);
                                $map['email'] = $strEmail;
                                $map['id']    = array('neq',$intId);

                                if(false!=$pUser->where($map)->count()) {
                                        $this->error("邮箱 $strEmail 已存在");
                                }

                                if($strPassword) {
                                        if(strlen($strPassword)<=6) {
                                                $this->error('密码不能小于6位');
                                        }else {
                                                $data['password'] =  md5($strPassword);
                                        }
                                }
                                $data['email'] = $strEmail;
                                $pUser->where('id='.$intId)->save($data);
                                $this->success('修改成功');
                                break;

                        case 'set':  //帐号设置
                                $intActive = intval($_POST['active']);
                                $intLevel  = intval($_POST['level']);
                                $pUser->setField('active',$intActive,'id='.$intId);
                                $pUser->setField('admin_level',$intLevel,'id='.$intId);
                                $this->success('操作成功');
                                break;


                }
        }

        /**
         * 用户推荐
         */
        function commend() {
                $intState = intval($_GET['type']);
                $pUser = D('User');
                $intId = intval($_GET['id']);
                $info = $pUser->where('id='.$intId)->field('commend')->find();
                if($info && $intId) {
                        $date['commend'] = $intState;
                        $pUser->where('id='.$intId)->save($date);
                        $this->assign('jumpUrl',$_SERVER['HTTP_REFERER']);
                        $this->success('设置成功');
                }else {
                        $this->error('您提交了错误参数');
                }
        }

        //首页批量处理
        function doBatch() {
                $strDotype = h($_POST['dotype']);
                $userId = $_POST['id'];
                $pUser = D('User');
                if(!empty($strDotype) && is_array($userId)) {
                        switch ($strDotype) {
                                case 'commend':  //推荐
                                        foreach ($userId as $key=>$val) {
                                                $pUser->setField('commend','1','id='.$val);
                                        }
                                        break;

                                case 'uncommend': //取消推荐
                                        foreach ($userId as $key=>$val) {
                                                $pUser->setField('commend','0','id='.$val);
                                        }
                                        break;

                                case 'active': //激活
                                        foreach ($userId as $key=>$val) {
                                                $pUser->setField('active','1','id='.$val);
                                        }
                                        break;

                                case 'unactive': //取消激活
                                        foreach ($userId as $key=>$val) {
                                                $pUser->setField('active','0','id='.$val);
                                        }
                                        break;

                                case 'movegroup': //设置群组
                                        $groupId = intval($_POST['togroupId']);
                                        $groupInfo = D('SystemGroup')->where('id='.$groupId)->find();
                                        if($groupInfo || $groupId==0) {
                                                foreach ($userId as $key=>$val) {
                                                        $pUser->setField('admin_level',$groupInfo['id'],'id='.$val);
                                                }
                                        }else {
                                                $this->error('用户组不存在');
                                        }

                                        break;
                        }
                        $this->success('操作成功');
                }else {
                        $this->error('您提交错误请求');
                }
        }

    /*******************************  前台用户资料配置 *************************/
        function info() {

                $this->display();
        }
}
?>