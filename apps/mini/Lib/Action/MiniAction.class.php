<?php
    /**
     * MiniAction
     * Mini博客的Action。接受网页传递参数。控制页面显示
     * <b>注意:$this->未显示定义的变量名将会取值。对应变量名的model对象</b>
     * @uses Action
     * @package Action::Mini
     * @version $id$
     * @copyright 2009-2011 SamPeng
     * @author SamPeng <sampeng87@gmail.com>
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class MiniAction extends Action {
        private $filter;
        private $mini;
        private $lastMini;
        private $uid;
        /**
         * __initialize
         * 初始化
         * @access public
         * @return void
         */
        public function _initialize(){
            $this->uid = 1; //登录者的id
            $this->mini = D( 'Mini' );
            $this->assign( 'api',$this->mini->getApi() );
        }
        protected $app = null;
        /**
         * index
         * 好友的心情
         * @access public
         * @return void
         */
        public function index(){

            $friends_id = array( 'in',"1" );
            //获得列表
            $list = $this->__getMini($friends_id,'*','cTime desc');
            //获得登录者最后的心情
            $lastMini = $this->mini->getLastMini( $this->uid,'content,cTime,id');

            $this->assign( $list );
            $this->assign( $lastMini );
            $this->assign( 'friend_list',$this->mini->uid );
            $this->display();

        }

        public function friends(){
            //检测合法传值id
            if( isset( $_GET['uid'] ) ){
                $uid = $_GET['uid'];
            }else{
                //TODO 如果uid不存在。
            }

            //如果有date参数。则是归档内容
            if( isset( $_GET['date'] ) ){
                $list = $this->fileAway( $uid );
            }else{
                $list = $this->__getMini( $uid,'',"cTime DESC" );
            }

            $username = $this->mini->getOneName( $uid );

            //获得归档的widget
            $map['uid'] = $uid;
            $link = 'Mini/my';
            $wiget = $this->_getWiget($link,$map);

            $this->assign( 'file_away',$wiget );

            $this->assign( $list );
            $this->assign( $username );  //模板标签 name
            $this->display();
        }

        /**
         * my
         * 我的心情
         * @access public
         * @return void
         */
        public function my(){
            //TODO 登录判断
            if( isset( $_GET['date'] ) ){
                $list = $this->fileAway( $this->uid );
            }else{
                $list = $this->__getMini( $this->uid,'',"cTime DESC" );
            }


            $lastMini = $this->mini->getLastMini( $this->uid,'content,cTime,id');//获得最后一条mini博客

            //获得归档的widget
            $map['uid'] = $this->uid;
            $link = 'Mini/my';
            $wiget = $this->_getWiget($link,$map);

            $this->assign( 'file_away',$wiget );

			print_r($wiget);
			exit;
            //获得表情列表
            $ico_list = $this->mini->getIco();
            $this->assign( 'ico_list',$ico_list );
            $this->assign( $list );
            $this->assign( $lastMini );
            $this->display();
        }

        /**
         * doDeleteMini
         * 删除mini
         * @access public
         * @return void
         */
        public function doDeleteMini(  ){

            $this->mini->id = $_REQUEST['id']; //要删除的id
            $result         = $this->mini->doDeleteMini();

            if( false != $result){
                echo 1;
            }else{
                echo -1;
            }
        }

        /**
         * doAddMini
         * 添加mini
         * @access public
         * @return void
         */
        public function doAddMini(){
            if( empty($_REQUEST['content']) ){
                echo -1;
                return false;
            }

            $this->mini->content = h($_REQUEST['content']);
            //TODO 检测空白输入
            $this->mini->cTime   = time(  ); //时间戳
            $this->mini->type    = $this->mini->_type; //类型为1时是心情，其他的是其他种类的扩展种类
            $this->mini->uid = $this->uid;

            $add = $this->mini->doAddMini();

            if( $add ){
                echo 1;
            }else{
                echo -1;
            }
        }

        /**
         * fileAway
         * 归档，
         * 接受POST或者GET参数为数据表中的cTime。如果是4位数字则是查询除了当前时间以外的心情；比如说其他
         * <code>
         * $_GET['date'] = 200905 //5月份的归档
         * $_GET['date'] = 2009 //09年的归档
         * </code>
         * 如果传递给fileAway()的数据为数组,则是查找一段时间的
         * <code>
         * $findTime = array( '200903','200905' ); //09年3－5月份的归档
         * </code>
         * @access public
         * @return void
         */
        private function fileAway($uid){
            $findTime = $_GET['date']; //获得传入的参数

            $this->mini->status = 0;
            $this->mini->uid = $uid;

            return $this->mini->fileAway( $findTime ) ;
        }

        /**
         * __getMini
         * 获得mini列表
         * @param int|array|string $uid uid
         * @access private
         * @return void
         */
        private function __getMini ($uid,$field=null,$order=null,$limit=null){
            //将数字或者数字型字符串转换成整型
            if( is_numeric( $uid ) ){
                $uid = intval( $uid );
            }
            //给mini对象的uid属性赋值
            $this->mini->uid   = $uid;
            if( isset( $_REQUEST['date'] ) ){
                $this->mini->date  = $_REQUEST['date'];
            }
            return $this->mini->getMiniList (null, $field, $order, $limit);
        }

        /**
         * _getWiget
         * 获得widget
         * @param mixed $link
         * @param mixed $map
         * @access private
         * @return void
         */
        private function _getWiget($link,$map){
            Import( '@.Widget.FileAwayWidget' );
            $wiget = new FileAwayWidget();
            $wiget->assign( 'link',$link );
            $wiget->assign( 'condition',$map );
            $wiget->assign( 'instance',$this->mini );
            $wiget->assign( 'APP',__APP__ );
            return $wiget->render();
        }
    }
?>
