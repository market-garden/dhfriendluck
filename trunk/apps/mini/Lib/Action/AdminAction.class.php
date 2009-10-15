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
    class AdminAction extends Administrator{
        /**
         * mini 
         * MiniModel的实例化对象
         * @var mixed
         * @access private
         */
        private $mini;

        /**
         * smile 
         * Smile的实例化对象
         * @var mixed
         * @access private
         */
        private $smile;
        /**
         * config 
         * MiniConfig的实例化对象
         * @var mixed
         * @access private
         */
        private $config;

        /**
         * _initialize 
         * 初始化
         * @access public
         * @return void
         */
        public function _initialize(){
            $this->mini  = D( 'Mini' );
            $this->smile = D( 'Smile' );
            $this->config = D( 'AppConfig' );
        }
        /**
         * basic 
         * 基础设置管理
         * @access public
         * @return void
         */
        public function index (){
            $config = $this->config->getConfigData();

            $smiletype     =  $this->smile->getSmileType() ;
            $this->assign( 'smilelist' , $smiletype );
        
            $delete       = intval( $config['delete'] );
            $all          = intval( $config['all'] );
            $fileaway     = intval( $config['fileaway'] );
            $fileawaypage = intval( $config['fileawaypage'] );
            $replay       = intval( $config['replay'] );
            $pagenum      = intval( $config['pagenum'] );
            $stringcount  = intval( $config['stringcount'] );
          
            if( is_null( $fileawaypage ) ){
                $fileawaypage = 6;
            }

            //处理发送通知配置
            $this->assign( 'smiletype',$config['smiletype'] );
            $this->assign( 'stringcount',$stringcount);
            $this->assign( 'delete',$delete );
            $this->assign( 'all',$all );
            $this->assign( 'fileaway',$fileaway );
            $this->assign( 'fileawaypage',$fileawaypage );
            $this->assign( 'replay',$replay );
            $this->assign( 'pagenum',$pagenum );
            $this->display();

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

            if( !isset( $_GET['type'] ) ){
                $type = $_POST['type'];
                //检查合法性
                if( empty( $type ) )
                    $this->error( "没有填写表情分类" );

                if( $this->smile->where( "type='{$type}'" )->getField( 'id' ) )
                    $this->error( $type."已经存在" );

                $smilelist = $this->smile->getSmileFileList( $type );
                if( !$smilelist )
                    $this->error( $type."不存在表情根目录下" );
                $smilelist['type'] = $type;

                $this->smile->addSmile( $smilelist );
                $smilelist = $this->smile->getSmileList( $type );
            }else{
                $type      = $_GET['type'];
                $smilelist = $this->smile->getSmileList($type);
                if( isset( $_GET['doUpdate'] ) ){
                    $smilelist1 = $this->smile->getSmileFileList( $type );
                    $smilelist = $this->smile->filterData( $smilelist,$smilelist1,$type );
                }
                //去处已经添加的
            }

            $path      = __APP__."/Admin/doAddSmile/";

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
        public function doEditSmile( ){
            if( count( array_filter( $_POST['emotion'] ) ) < count( $_POST['emotion'] ) ){
                $this->error( "修改失败，不允许表情缩写为空" );
                exit;
            }

            if( count( array_filter( $_POST['title'] ) ) < count( $_POST['title'] ) ){
                $this->error( "修改失败，不允许表情标题为空" );
                exit;
            }
            if( $this->smile->editSmile( $_POST['emotion'],$_POST['title'],$_POST['type'] ) ){
                $this->redirect( 'Admin/smile/type/'.$_POST['type'] );
            }else{
                $this->error( "修改失败" );
            }
        }

        /**
         * doAddSmile 
         * 添加表情
         * @access public
         * @return void
         */
        public function doAddSmile(){
            $result = $this->smile->addSmile( $_POST,true );
            if( count( array_filter($_POST) ) < count( $_POST ) )
                $this->error( "添加失败，轻完整填写" );
            if(true == $result){
                $this->redirect( 'Admin/smile/type/'.$_POST['type'] );
            }elseif( 'has_file' == $reuslt ){
                $this->error( "添加失败，和现有文件名重名。" );
            }else{
                $this->error( "添加失败，请检查文件是否存在于{$_POST['type']}文件夹下" );

            }
        }
        /**
         * minilist 
         * 获得所有人的minilist
         * @access public
         * @return void
         */
        public function minilist (){
            //姓名，uid,心情内容
            $_POST['name']     && $this->mini->name    = t( $_POST['name'] );
            $_POST['uid']      && $this->mini->uid     = intval( t( $_POST['uid'] ) );
            $_POST['content']  && $this->mini->content = array( 'like',"%".t( $_POST['content'] )."%" );

            //处理时间
            $_POST['stime'] && $_POST['etime'] && $this->mini->cTime = $this->mini->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            $order = t( $_POST['sorder'] )." ".t( $_POST['eorder'] );
            $list  = $this->mini->getMiniList(null,null,$order,t($_POST['limit']));
            $this->assign( $_POST );
            $this->assign( $list );
            $this->display();
        }

        public function doDeleteSmileType(){
            if( !isset( $_POST['type'] ) && empty( $_POST['type'] ) ) {
                echo -1;
                exit();
            }
            $map['type'] = array( 'in',$_POST['type']);
            if( $this->smile->where( $map )->delete() ){
                if( !strpos( $_POST['type'],',' ) ){
                    echo 1;
                }else{
                    echo 2;
                }
            }else{
                echo -1;
            }
        }
        /**
         * doDeleteMini 
         * 删除mili
         * @access public
         * @return void
         */
        public function doDeleteMini(){
            $miniid['id'] = array( 'in',$_REQUEST['id']);        //要删除的id.
            $result       = $this->mini->doDeleteMini($miniid);
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
            $config = array();

            $config['delete']       = intval($_POST['delete'] );
            $config['all']          = intval($_POST['all'] );
            $config['fileaway']     = intval($_POST['fileaway'] );
            $config['fileawaypage'] = intval($_POST['fileawaypage'] );
            $config['replay']       = intval( $_POST['replay'] );
            $config['smiletype']    = $_POST['smiletype'];
            $config['pagenum']      = $_POST['pagenum'];
            $config['stringcount']  = intval($_POST['stringcount']);

            //长度判断
            if( $config['fileawaypage']>10 )
                $this->error( "归档月份不得超过10个月" );
            if( $config['pagenum']>50 )
                $this->error( "分页数不得大于50" );
            if( $config['stringcount'] > 200 )
                $this->error( "字数设置不得大于200" );

            if( $this->config->editConfig($config)){
                $this->redirect( 'index' );
            }else{
                $this->error( "配置失败" );
            }
            
            //$this->forward();
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

        public function doChangePath(){
            
        }
    }
