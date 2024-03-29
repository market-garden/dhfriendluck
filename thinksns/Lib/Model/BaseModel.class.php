<?php
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
    class BaseModel extends Model{
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
         * uid 
         * 当前登录用户uid
         * @var mixed
         * @access protected
         */
        protected $uid;
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

        
        /**
         * fileAwayCount 
         * 归档计数，和fileAway获得归档具体内容一样处理。只是是获得记录数
         * @param mixed $findTime 
         * @param mixed $condition 
         * @access public
         * @return void
         */
        public function fileAwayCount( $findTime,$condition ){
                if( is_array( $findTime) ){
                    $start_temp   = $this->paramData( strval($findTime[0] ));
                    $end_temp     = $this->paramData( strval($findTime[1] ));
                                                      
                    $start        = $start_temp[0];
                    $end          = $end_temp[1];
                }else{
                    $findTime  = strval( $findTime );
                    $paramTime = self::paramData( $findTime );
                    $start     = $paramTime[0];
                    $end       = $paramTime[1];
                }

                $this->cTime = array( 'between', array( $start,$end ) );
                //如果查询时没有设置其他查询条件，就只是按时间来进行归档查询
                $map = $this->merge( $condition );
                $result = $this->where( $map )->field( "count(*)" )->findAll();
                return $result;

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
            $result = $this->api->feed_publish( $type,$title,$body );
            return $result;

        }

        /**
         * checkNull 
         * 检查变量是否为空,暂时只能检查一维数组
         * @param mixed $value 
         * @access public
         * @return void
         */
        protected static function checkNull( $value ){
            if( !isset( $value ) || empty( $value ) ){
                return true;            
            }else{
                return false;
            }
        }
        

        /**
         * fileAway 
         * 归档查询
         *
         * @param string|array $findTime 查询时间。
         * @param mixed $condition 查询条件
         * @param Model $object 查询对象
         * @param mixed $limit 查询limit
         * @access public
         * @return void
         */
        public function fileAway($findTime ,$condition = null){
                //如果是数组。进行的解析不同
                if( is_array( $findTime) ){
                    $start_temp   = $this->paramData( strval($findTime[0] ));
                    $end_temp     = $this->paramData( strval($findTime[1] ));
                                                      
                    $start        = $start_temp[0];
                    $end          = $end_temp[1];
                }else{
                    $findTime  = strval( $findTime );
                    $paramTime = $this->paramData( $findTime );
                    $start     = $paramTime[0];
                    $end       = $paramTime[1];
                }
                $this->cTime = array( 'between', array( $start,$end ) );
                //如果查询时没有设置其他查询条件，就只是按时间来进行归档查询
                $map = $this->merge( $condition );
                //查询条件赋值
                $result = $this->where( $map )->field( '*' )->order( 'cTime DESC' )->findPage(20);
                return $result;
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
                $end   = mktime( 0, 0, 0 ,$month,$day,$year  );

                //反之,某一年的归档
            }elseif( isset( $year[4] ) ){
                $start = mktime( 0, 0, 0, 1, 1, $year );
                $end = mktime( 0, 0, 0, 12,31, $year  );
            }else{
                //其他操作
            }

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
            return $this->api->user_getInfo($uid,'name');
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
         * setWrite 
         * 设置配置写入类
         * @param ArrayWrite $write 
         * @access protected
         * @return void
         */
        protected function setWrite( ArrayWrite $write ){
            $this->write = $write;
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

        public function getApi(  ){
            return $this->api;
        }

        /**
         * replace 
         * 在数据集中替换
         * @param mixed $data 
         * @access private
         * @return void
         */
        protected function replace( $data ){
            $result = $data;

            //修改content
            foreach( $result as &$value ){
            	dump($value['content']);
                            $value['content'] = str_replace('{PUBLIC_URL}',__PUBLIC__,$this->replaceContent( $value['content'] ));
                            dump($value['content']);
            }
            return $result;
        }

        /**
         * replaceContent 
         * 替换内容
         * @param mixed $content 
         * @access private
         * @return void
         */
        protected function replaceContent( $content,$temp=null ){
            $public = isset( $temp )?$temp:__PUBLIC__;
            $path   = $public."/images/biaoqing/".$this->config->smiletype."/";//路径

            //循环替换掉文本中所有ubb表情
            foreach( $this->config->ico as $value ){

                $img = sprintf("<img title='%s' src='%s%s'>",$value['title'],$path,$value['filename']);
                $content = str_replace( $value['emotion'],$img,$content );

            }
            return $content;
        }

        protected function replayPath( ){
            $config = $this->config->replay; //回复的配置
        }

        public function setUid( $uid ){
            $this->uid = $uid;
        }
}
