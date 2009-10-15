<?php
    Import( '@.Unit.Common' );
    import('AdvModel');
    /**
     * BaseModel 
     * 心情的base类
     *
     * @uses Model
     * @package Model::Mini
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class BaseModel extends AdvModel{
        /**
         * API 
         * API名,可以为common里面的扩展API类
         * @var string
         * @access protected
         */
        protected $api;

        /**
         * config 
         * mini的配置
         * @var mixed
         * @access protected
         */
        protected $config;

        /**
         * write 
         * 写入配置文件的处理类
         * @var mixed
         * @access protected
         */
        protected $write;

        /**
         * _initialize 
         * 进行mini博客的时候进行初始化
         *
         * 获取uid,mid,或者friendsId.
         * @access protected
         * @return void
         */
        protected function _initialize(){
            $this->api = new TS_API();
        } 
        
       //获取系统配置文件
        function getConfig() {
        	return D('GroupSetting')->getGroupSetting();
        }
    }

  ?>