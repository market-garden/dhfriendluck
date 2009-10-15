<?php

    /**
     * AdminAction 
     * 后台管理
     * @uses Action
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class AdminAction extends Administrator{
        /**
         * vote 
         * VoteModel的实例化对象
         * @var mixed
         * @access private
         */
        private $vote;

        /**
         * smile 
         * Smile的实例化对象
         * @var mixed
         * @access private
         */
        private $smile;
        /**
         * config 
         * VoteConfig的实例化对象
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
            $this->vote  = D( 'Vote' );
            $this->config = D( 'AppConfig' );
        }

        /**
         * votelist 
         * 获得所有人的votelist
         * @access public
         * @return void
         */
        public function votelist (){
            //姓名，uid,日志内容
            $_POST['name']     && $this->vote->name    = t( $_POST['name'] );
            $_POST['uid']      && $this->vote->uid     = intval( t( $_POST['uid'] ) );
            $_POST['title']  && $this->vote->title = array( 'like',"%".t( $_POST['title'] )."%" );

            //处理时间
            $_POST['stime'] && $_POST['etime'] && $this->vote->cTime = $this->vote->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            $order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";
            $order && $list  = $this->vote->getVoteList(null,null,$order,t($_POST['limit']));
            $this->assign( $_POST );
            $this->assign( $list );
            $this->display();
        }

        /**
         * doDeleteVote 
         * 删除mili
         * @access public
         * @return void
         */
        public function doDeleteVote(){
            $voteid = array( 'in',$_REQUEST['id']);        //要删除的id.
            $result       = $this->vote->doDeleteVote($voteid);
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
         * basic 
         * 基础设置管理
         * @access public
         * @return void
         */
        public function index (){
            $config   = Common::changeType( $this->config->getConfigData(),"int");
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
            $_POST['title']  && $map['title'] = array( 'like',"%".t( $_POST['title'] )."%" );
            isset( $_POST['isHot'] )    && $map['isHot'] = intval( $_POST['isHot'] );

            //处理时间
            $_POST['stime'] && $_POST['etime'] && $map['cTime'] = $this->vote->DateToTimeStemp(t( $_POST['stime'] ),t( $_POST['etime'] ) );

            //处理排序过程
            $order = isset( $_POST['sorder'] )?t( $_POST['sorder'] )." ".t( $_POST['eorder'] ):"cTime DESC";

            $map['status'] = 2;

            $order && $list = $this->vote->where( $map )->order( $order )->findPage( t($_POST['limit']) );
            $this->assign( $list );
            $this->display();
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
    }
