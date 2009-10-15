<?php
    /**
     * BrowseBehavior 
     * 浏览行为
     * @uses Behavior
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class BrowseBehavior extends Behavior{
        private static $uid  = 0;
        private static $id  = 0;
        private static $type = "";
        private static $lefttime = 0;

        /**
         * cache 
         * 浏览的缓存
         * @var array
         * @access private
         */
        private $_cache = array();

        public function __construct($options = null){
            $this->setOptions( $options );
            $this->_cache = ts_cache( self::$type.'browse' );
;
        }

        public function run(){
            if( empty( $this->_cache ) ){
                $this->_cache[] = array( 'id'=>self::$id,'uid'=>self::$uid );
                $this->_setCache();
                return true;
            }

            if( time() <= $this->_cache['endTime'] ){
                if( $this->_checkHasBrowse() ){
                    return false;
                }else{
                    $this->_cache[] = array( 'id'=>self::$id,'uid'=>self::$uid );
                    $this->_setCache();
                    return true;
                }
            }else{
                //清空缓存
                ts_cache( self::$type.'browse','');
                $this->_cache = array();
                $this->_setCache();
                return true;
            }

        }
        /**
         * _checkHasBrowse 
         * 检查是否已经浏览过
         * @param mixed $uid 
         * @access private
         * @return void
         */
        private function _checkHasBrowse(  ){
            //如果缓存不存在。则表示过了防刷新浏览间隔时间
            if( empty( $this->_cache ) )
                return false;

            foreach( $this->_cache as $value ){
                if( $value['id'] == self::$id && $value['uid'] == self::$uid )
                    return true;
            }
            return false;
        }

        /**
         * _statistics 
         * 统计
         * @param mixed $id 
         * @param mixed $uid 
         * @access private
         * @return void
         */
        private function _statistics(){
            //检查时间
            $count = 0;
            foreach( $this->_cache as $value ){
                if( $value['id'] == self::$id)
                    $count++;
            }
            return $count;
        }

        /**
         * _setCache 
         * 设置缓存
         * @param mixed $id 
         * @param mixed $uid 
         * @access private
         * @return void
         */
        private function _setCache(){

            $this->_cache['endTime'] = time()+self::$lefttime;

            return ts_cache( self::$type.'browse',$this->_cache ) ;

        }

        /**
         * setOptions 
         * 批量设置
         * @param array $options 
         * @access public
         * @return void
         */
        public function setOptions(array $options ){
            $option = array_change_key_case( $options,CASE_LOWER );

            //循环设置属性
            foreach ( $option as $key => $value ){
                self::$$key = $value;
            }
            return $this;
        }
    }
