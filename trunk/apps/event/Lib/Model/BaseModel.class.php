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
         * mid 
         * 访问者的id
         * @var mixed
         * @access protected
         */
        protected $mid;
        public function setMid( $mid ){
            $this->mid = $mid;
        }
        /**
         * feed_publish 
         * 发送动态
         * @param mixed $type 
         * @param mixed $title 
         * @param mixed $body 
         * @static
         * @access protected
         * @return void
         */
        protected  function doFeed($type,$title,$body = null){
            $appid = $this->api->APP_getChoiceId(APP_NAME);
            return $this->api->feed_publish( $type,$title,$body,$appid);
        }

        protected  function doNotify($uid,$type,$title,$body,$url){
            return $this->api->notify_send( $uid,$type,$title,$body,$url );
        }

        /**
         * paramData 
         * 处理归档查询的时间格式
         * @param string $findTime 200903这样格式的参数
         * @static
         * @access protected
         * @return void
         */
        protected function paramData( $findTime ){
            //处理年份
            $year = $findTime[0].$findTime[1].$findTime[2].$findTime[3];
            //处理月份
            $month_temp = explode( $year,$findTime);
            $month = $month_temp[1];
            //归档查询
            if ( !empty( $month ) ){

                //判断时间.处理结束日期
                switch (true) {
                    case ( in_array( $month,array( 1,3,5,7,8,10,12 ) ) ):
                        $day = 31;
                        break;
                    case ( 2 == $month ):
                        if( 0 != $year % 4 ){
                            $day = 28;
                        }else{
                            $day = 29;
                        }
                        break;
                    default:
                        $day = 30;
                        break;
                }
                //被查询区段开始时期的时间戳
                $start = mktime( 0, 0, 0 ,$month,1,$year  );

                //被查询区段的结束时期时间戳
                $end   = mktime( 24, 0, 0 ,$month,$day,$year  );

                //反之,某一年的归档
            }elseif( isset( $year[4] ) ){
                $start = mktime( 0, 0, 0, 1, 1, $year );
                $end = mktime( 24, 0, 0, 12,31, $year  );
            }else{
                //其他操作
            }

            //fd( array( friendlyDate($start),friendlyDate($end) ) );
            return array( $start,$end );

        }


        /**
         * getOneName 
         * 获得某一个人的姓名
         * @param mixed $uid 
         * @access protected
         * @return void
         */
        public function getOneName( $uid ){
            $name = $this->api->user_getInfo($uid,'name');
            return $name['name'];
        }

        /**
         * setConfig 
         * 设置配置控制器
         * @param mixed $model 
         * @access protected
         * @return void
         */
        protected function setConfig( $data ){
            //引入配置管理类
            Import( '@.Unit.Config' );
            //引入配置信息
            //配置管理对象,把配置数组交给配置管理对象处理
            $config = new Config( $data  );
                
            $this->config = $config;
        }

        /**
         * merge 
         * 合并条件
         * @param mixed $map 
         * @access private
         * @return void
         */
        protected function merge ( $map = null ){
            if( isset( $map ) ){
                $map = array_merge( $this->data,$map );
            }else{
                $map = $this->data;
            }

            return $map;
        }

        public function setApi( $api ){
            $this->api = $api;
        }

        /**
         * factoryModel 
         * 工厂方法
         * @param mixed $name 
         * @static
         * @access private
         * @return void
         */
        public static function factoryModel( $name ){
            return D("Event".ucfirst( $name ));
        }

         /**
         * DateToTimeStemp 
         * 时间换算成时间戳返回
         * @param mixed $stime 
         * @param mixed $etime 
         * @access public
         * @return void
         */
        public function DateToTimeStemp( $stime,$etime ) {
            $stime = strval( $stime );
            $etime = strval( $etime );

           //如果输入时间是YYMMDD格式。直接换算成时间戳 
            if( isset( $stime[7] ) && isset( $etime[7] ) ){
                //开始时间
                $syear  = substr( $stime,0,4 );
                $smonth = substr( $stime,4,2 );
                $sday   = substr( $stime,6,2 );
                $stime  = mktime( 0, 0, 0, $smonth,$sday,$syear );

                //结束时间
                $eyear  = substr( $etime,0,4 );
                $emonth = substr( $etime,4,2 );
                $eday   = substr( $etime,6,2 );
                $etime  = mktime( 0, 0, 0, $emonth,$eday,$eyear );

                return array( 'between',array( $stime,$etime ) );
            }

            //如果输入时间是YYYYMM格式
            $start_temp   = $this->paramData( $stime );
            $end_temp     = $this->paramData( $etime );
            $start        = $start_temp[0];
            $end          = $end_temp[1];

            return array( 'between',array( $start,$end ) );
        }
}
