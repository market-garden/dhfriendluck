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
    class BaseAction extends Action {
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
    }
