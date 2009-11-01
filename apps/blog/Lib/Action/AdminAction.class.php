<?php
    /**
     * AdminAction 
     * 心情管理
     * @uses Action
     * @package Admin
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class AdminAction extends Administrator {
        /**
         * blog 
         * BlogModel的实例化对象
         * @var mixed
         * @access private
         */
        private $blog;

        /**
         * smile 
         * Smile的实例化对象
         * @var mixed
         * @access private
         */
        private $smile;
        /**
         * config 
         * BlogConfig的实例化对象
         * @var mixed
         * @access private
         */
        private $config;

        private $category;
        /**
         * _initialize 
         * 初始化
         * @access public
         * @return void
         */
        public function _initialize(){
        	$this->config = D( 'AppConfig' );
            $this->blog  = D( 'Blog' );
        }
        /**
         * basic 
         * 基础设置管理
         * @access public
         * @return void
         */
        public function index (){
            $config   = Common::changeType( $this->config->getConfigData(),"int");
            $category = $this->blog->getCategory();
            $smiletype     =  D( 'Smile' )->getSmileType() ;
            $this->assign( 'smilelist' , $smiletype );

            //去除用户设定的分类
            $category = array_filter( $category,array( $this,'filterUser') );
            $this->assign( 'category_list',$category );
            $this->assign( $config );
            $this->display();

        } 

        /**
         * recycle 
         * 回收站
         * @access public
         * @return void
         */
        public function recycle(  ) {
            //姓名，uid,日志内容
            $_POST['name']     && $map['name']    = t( $_POST['name'] );
            $_POST['uid']      && $map['uid']     = intval( t( $_POST['uid'] ) );
            $_POST['content']  && $map['content'] = array( 'like',"%".t( $_POST['content'] )."%" );
            $_POST['title']  && $map['title'] = array( 'like',"%".t( $_POST['title'] )."%" );
            isset( $_POST['isHot'] )    && $map['isHot'] = intval( $_POST['isHot'] );

            //处理时间
            $_POST['stime'] && $_POST['etime'] && $map['cTime'] = $this->blog->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            $order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";

            $map['status'] = 2;

            $order && $list = $this->blog->where( $map )->order( $order )->findPage( t($_POST['limit']) );
            $this->assign( $list );
            $this->display();
        }

        /**
         * recycleAction 
         * 回收站动作
         * @access public
         * @return void
         */
        public function recycleAction(  ){
            $act = $_REQUEST['act'];  //动作
            isset($_REQUEST['id']) && $map['id']  = array('in',$_REQUEST['id']);  //日志的id

            switch( $act ){
                case "resume":  //恢复
                    $result = $this->blog->setField( 'status',1,$map );
                    break;
                case "delete"://彻底物理删除
                    if( empty( $map ) ){
                        echo -1;
                        exit();
                    }
                    $map['status'] = 2;
                    $result = $this->blog->where( $map )->delete();
                    break;
                case "allresume":  //全部恢复
                    $result = $this->blog->setField( 'status',1);
                    break;
                case "alldelete"://全部彻底物理删除
                    $map['status'] = 2;
                    $result = $this->blog->where( $map )->delete();
                    if( $result ){
                        $this->success( "删除成功" );
                    }
                    break;
                default:
                    echo -1;
                    exit;
                    $this->error( "error_no_action" );
            }

            if( $result ){
                if ( !strpos($_REQUEST['id'],",") ){
                    echo 2;            //说明只是删除一个
                }else{
                    echo 1;            //删除多个
                }
            }else{
                echo -1;
            }

        }
        public function filterUser($var){
            if( 0 != intval($var['uid']) )
                return false;
            return true;
        }
        /**
         * ico 
         * 图像设置
         * @access public
         * @return void
         */
        public function ico (){
            //获取数据库的表情列表
            $smiletype     =  $this->smile->getSmileType() ;
            $this->assign( 'smiletype' , $smiletype );
            $this->display();
        }

        public function smile(){
            $type      = $_REQUEST['type'];
            $smilelist = $this->smile->getSmileFileList($type);
            $path      = __APP__."/Admin/doAddSmile/";

            $this->assign( 'smile_list',$smilelist );
            $this->assign( 'action_path',$path );
            $this->assign( 'smilepath',__PUBLIC__.'/images/biaoqing/'.$type.'/' );
            $this->assign( 'smiletype',$type );
            $this->display(  );
        }

        public function doDeleteCategory(){
            $id['id']      = array( "in",$_POST['id']);
            $category = D( 'BlogCategory' );
            if( $result = $category->deleteCategory( $id ) ){
                echo 2;
            }else{
                echo -1;
            }
        }
        /**
         * doAddCategory 
         * 修改分类
         * @access public
         * @return void
         */
        public function doAddCategory(){
            $category = D( 'BlogCategory' );
            if( $result   = $category->addCategory( $_POST ) ){
                echo $result ;
            }else{
                echo -1;
            }
        }

        public function doEditCategory(){
            $category = D( 'BlogCategory' );
            if( $result   = $category->editCategory( $_POST['name'] ) ){
                $this->redirect('index');
            }else{
                $this->error( "修改失败" );
            }

        }
        /**
         * bloglist 
         * 获得所有人的bloglist
         * @access public
         * @return void
         */
        public function bloglist (){
            //姓名，uid,日志内容
            $_POST['name']     && $this->blog->name    = t( $_POST['name'] );
            $_POST['uid']      && $this->blog->uid     = intval( t( $_POST['uid'] ) );
            $_POST['content']  && $this->blog->content = array( 'like',"%".t( $_POST['content'] )."%" );
            $_POST['title']  && $this->blog->title = array( 'like',"%".t( $_POST['title'] )."%" );
            isset( $_POST['isHot'] )    && $this->blog->isHot = intval( $_POST['isHot'] );

            //处理时间
            $_POST['stime'] && $_POST['etime'] && $this->blog->cTime = $this->blog->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            $order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";
            $order && $list  = $this->blog->getBlogList(null,null,$order,t($_POST['limit']));
            $this->assign( $_POST );
            $this->assign( $list );
            $this->display();
        }

        /**
         * doDeleteBlog 
         * 删除mili
         * @access public
         * @return void
         */
        public function doDeleteBlog(){
            $blogid['id'] = array( 'in',$_REQUEST['id']);        //要删除的id.
            $result       = $this->blog->doDeleteBlog($blogid);
            if( false !== $result){
                if ( !strpos($_REQUEST['id'],",") ){
                    echo 2;            //说明只是删除一个
                }else{
                    echo 1;            //删除多个
                }
            }else{
                echo -1;               //删除失败
            }
        }

        /**
         * doChangeBase 
         * 修改全局设置
         * @access public
         * @return void
         */
        public function doChangeBase (){
            $config = $_POST;
            if( $this->config->editConfig($config)){
                $this->redirect( 'index' );
            }else{
                $this->error( "配置失败" );
            }
            
            //$this->forward();
        }

        public function doChangeIsHot(){
            $blog['id'] = array( 'in',$_REQUEST['id']);        //要推荐的id.
            $act  = $_REQUEST['act'];  //推荐动作
            $result  = $this->blog->doIsHot($blog,$act);

            if( false !== $result){
                    echo 1;            //推荐成功
            }else{
                echo -1;               //推荐失败
            }
        }

        /**
         * doChangeIco 
         * 删除表情
         * @access public
         * @return void
         */
        public function doChangeIco(){
            $id     = $_POST['id'];
            $idlist = explode( ',',$id );
            if( is_array( $idlist ) ){
                foreach ( $idlist as $value ){
                    $this->blog->unsetConfig( $value,'ico' );
                }
                $this->blog->getWrite()->write('smaile');
                echo 2;
            }else{
                $this->blog->unsetConfig( $id,'ico' );
                $this->blog->getWrite()->write('smaile');
                echo 1;
            }
        }

        public function doChangePath(){
            
        }

        /**
         * changeType 
         * 将数组中的数据转换成指定类型
         * @param mixed $data 
         * @param mixed $type 
         * @access private
         * @return void
         */
        private function changeType( $data , $type ){
            $result = $data;

            switch( $type ){
            case 'int':
                $method = "intval";
                break;
            case 'string':
                $method = "strtval";
                break;
            default:
                throw new ThinkException( '暂时只能转换数组和字符串类型' );
            }
            foreach ( $result as &$value ){
                $value = $method( $value );
            }
            return $result;
        }
    }
