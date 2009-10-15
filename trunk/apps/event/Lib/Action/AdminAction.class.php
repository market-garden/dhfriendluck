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
         * event 
         * EventModel的实例化对象
         * @var mixed
         * @access private
         */
        private $event;

        /**
         * smile 
         * Smile的实例化对象
         * @var mixed
         * @access private
         */
        private $smile;
        /**
         * config 
         * EventConfig的实例化对象
         * @var mixed
         * @access private
         */
        private $config;

        private $type;
        /**
         * _initialize 
         * 初始化
         * @access public
         * @return void
         */
        public function _initialize(){
            $this->event  = D( 'Event' );
            $this->config = D( 'AppConfig' );
            $this->event->setApi( $this->api);
        }
        /**
         * basic 
         * 基础设置管理
         * @access public
         * @return void
         */
        public function index (){
            $config   = $this->changeType( $this->config->getConfigData(),"int");
            $type = D( 'EventType' );
            $type = $type->findAll();

            $this->assign( 'type_list',$type );
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
            $_POST['stime'] && $_POST['etime'] && $map['cTime'] = $this->event->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            $order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";

            $map['status'] = 2;

            $order && $list = $this->event->where( $map )->order( $order )->findPage( t($_POST['limit']) );
            dump( $this->event->getLastSql() );
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
                    $result = $this->event->setField( 'status',1,$map );
                    break;
                case "delete"://彻底物理删除
                    if( empty( $map ) ){
                        echo -1;
                        exit();
                    }
                    $map['status'] = 2;
                    $result = $this->event->where( $map )->delete();
                    break;
                case "allresume":  //全部恢复
                    $result = $this->event->setField( 'status',1);
                    break;
                case "alldelete"://全部彻底物理删除
                    $map['status'] = 2;
                    $result = $this->event->where( $map )->delete();
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

        public function doDeleteType(){
            $id['id']      = array( "in",$_POST['id']);
            $type = D( 'EventType' );
            if( $result = $type->deleteType( $id ) ){
                echo 2;
            }else{
                echo -1;
            }
        }
        /**
         * doAddType 
         * 修改分类
         * @access public
         * @return void
         */
        public function doAddType(){
            $type = D( 'EventType' );
            if( $result   = $type->addType( $_POST ) ){
                echo $result ;
            }else{
                echo -1;
            }
        }

        public function doEditType(){
            $type = D( 'EventType' );
            if( $result   = $type->editType( $_POST['name'] ) ){
                $this->redirect('index');
            }else{
                $this->error( "修改失败" );
            }

        }
        /**
         * eventlist 
         * 获得所有人的eventlist
         * @access public
         * @return void
         */
        public function eventlist (){
            $_POST['name']     && $this->event->name    = t( $_POST['name'] );
            $_POST['uid']      && $this->event->uid     = intval( t( $_POST['uid'] ) );
            $_POST['title']  && $this->event->title = array( 'like',"%".t( $_POST['title'] )."%" );
            isset( $_POST['isHot'] )    && $this->event->isHot = intval( $_POST['isHot'] );

            //处理时间
            $_POST['stime'] && $_POST['etime'] && $this->event->cTime = $this->event->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            $order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";
            $order && $list  = $this->event->getList($order,t($_POST['limit']));
            $this->assign( $_POST );
            $this->assign( $list );
            $this->display();
        }

        /**
         * doDeleteEvent 
         * 删除mili
         * @access public
         * @return void
         */
        public function doDeleteEvent(){
            $eventid['id'] = array( 'in',$_REQUEST['id']);        //要删除的id.
            $result       = $this->event->doDeleteEvent($eventid);
            dump( $result );
            exit;
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
            $event['id'] = array( 'in',$_REQUEST['id']);        //要推荐的id.
            $act  = $_REQUEST['act'];  //推荐动作
            $result  = $this->event->doIsHot($event,$act);

            if( false !== $result){
                    echo 1;            //推荐成功
            }else{
                echo -1;               //推荐失败
            }
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
                if( is_numeric( $value ) )
                $value = $method( $value );
            }
            return $result;
        }

    }
