<?php
    /**
     * WishAction 
     * Wish博客的Action。接受网页传递参数。控制页面显示
     * <b>注意:$this->未显示定义的变量名将会取值。对应变量名的model对象</b>
     * @uses Action
     * @package Action::Wish
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class WishAction extends Action {
        private $filter;
        private $wish;
        private $lastWish;
        private $uid;
        /**
         * __initialize 
         * 初始化
         * @access public
         * @return void
         */
        public function _initialize(){
            $this->uid = 1; //登录者的id
            $this->wish = D( 'Wish' );
            $this->assign( 'api',$this->wish->getApi() );
            dump('123123');
        }
        protected $app = null;
        /**
         * index 
         * 好友的愿望
         * @access public
         * @return void
         */
        public function index(){

            $friends_id = array( 'in',"1" );
            //获得列表
            $list = $this->__getWish($friends_id,'*','cTime desc');
            //获得登录者最后的愿望
            $lastWish = $this->wish->getLastWish( $this->uid,'content,cTime,id');

            $this->assign( $list );
            $this->assign( $lastWish );
            $this->assign( 'friend_list',$this->wish->uid );
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
                $list = $this->__getWish( $uid,'',"cTime DESC" );
            }

            $username = $this->wish->getOneName( $uid );

            //获得归档的widget
            $map['uid'] = $uid;
            $link = 'Wish/my';
            $wiget = $this->_getWiget($link,$map);

            $this->assign( 'file_away',$wiget );

            $this->assign( $list );
            $this->assign( $username );  //模板标签 name
            $this->display();
        }

        /**
         * my 
         * 我的愿望
         * @access public
         * @return void
         */
        public function my(){
            //TODO 登录判断
            if( isset( $_GET['date'] ) ){
                $list = $this->fileAway( $this->uid );
            }else{
                $list = $this->__getWish( $this->uid,'',"cTime DESC" );
            }


            $lastWish = $this->wish->getLastWish( $this->uid,'content,cTime,id');//获得最后一条wish博客

            //获得归档的widget
            $map['uid'] = $this->uid;
            $link = 'Wish/my';
            $wiget = $this->_getWiget($link,$map);

            $this->assign( 'file_away',$wiget );

            //获得表情列表
            $ico_list = $this->wish->getIco();
            $this->assign( 'ico_list',$ico_list );
            $this->assign( $list );
            $this->assign( $lastWish );
            $this->display();
        }

        /**
         * doDeleteWish 
         * 删除wish
         * @access public
         * @return void
         */
        public function doDeleteWish(  ){

            $this->wish->id = $_REQUEST['id']; //要删除的id
            $result         = $this->wish->doDeleteWish();

            if( false != $result){
                echo 1;
            }else{
                echo -1;
            }
        }

        /**
         * doAddWish 
         * 添加wish
         * @access public
         * @return void
         */
        public function doAddWish(){
            if( empty($_REQUEST['content']) ){
                echo -1;
                return false;
            }

            $this->wish->content = h($_REQUEST['content']);
            //TODO 检测空白输入
            $this->wish->cTime   = time(  ); //时间戳
            $this->wish->type    = $this->wish->_type; //类型为1时是愿望，其他的是其他种类的扩展种类
            $this->wish->uid = $this->uid;

            $add = $this->wish->doAddWish();

            if( $add ){
                echo 1;
            }else{
                echo -1;
            }
        }

        /**
         * fileAway 
         * 归档，
         * 接受POST或者GET参数为数据表中的cTime。如果是4位数字则是查询除了当前时间以外的愿望；比如说其他
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

            $this->wish->status = 0;
            $this->wish->uid = $uid;

            return $this->wish->fileAway( $findTime ) ;
        }

        /**
         * __getWish 
         * 获得wish列表
         * @param int|array|string $uid uid
         * @access private
         * @return void
         */
        private function __getWish ($uid,$field=null,$order=null,$limit=null){
            //将数字或者数字型字符串转换成整型
            if( is_numeric( $uid ) ){
                $uid = intval( $uid );
            }
            //给wish对象的uid属性赋值
            $this->wish->uid   = $uid;
            if( isset( $_REQUEST['date'] ) ){
                $this->wish->date  = $_REQUEST['date'];
            }
            return $this->wish->getWishList (null, $field, $order, $limit);
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
            $wiget->assign( 'instance',$this->wish );
            $wiget->assign( 'APP',__APP__ );
            return $wiget->render();
        }
    }
?>
