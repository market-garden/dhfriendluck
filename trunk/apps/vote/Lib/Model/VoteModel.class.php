<?php
Import( '@.Unit.Common' );
class VoteModel extends BaseModel
{
	//字段信息
	protected $fields	=   array(
        'id','uid','title','explain','type','glimit','deadline','onlyfriend','cTime','vote_num','commentCount','name','feedId',
		'_autoInc'	=>	true,
		'_pk'		=>	'id',
	);
    //字段类型
	protected $type	=	array(
		'id'			=>	'int(11)' ,
        'uid'	=>	'int(11)' ,
        'name'  =>  'varchar(32)',
		'title'	    =>  'text' ,
		'explain'    =>  'text' ,
		'type'		=>  'tinyint', 
		'glimit'		=>  'tinyint', 
		'deadline'	=>  'int(11)' ,
		'onlyfriend'	=>  'tinyint' ,
		'cTime'		=>	'int(11)' ,
		'vote_num'	=>	'int(11)' ,
        'commentCount'	=>	'int(11)' ,
        'feedId'   =>  'int(11)',
	);

    public function _initialize(){
        $config_obj   = D( 'AppConfig' );
        //获取配置
        $config = $config_obj->getConfig();
        $config = Common::changeType( $config,'int' ); //将数组各元素转换成int类型
        $this->setConfig( $config );
        parent::_initialize();
    }
        public function setCount($appid,$count){
            $map['id'] = $appid;
            $map2['commentCount'] = $count;
            return $this->where($map)->save($map2);
        }
    public function deleteComment( $id ){
        if( is_array( $id ) ){
            $where = 'appid in ('.$id[1].')';
        }else{
            $where = 'appid = '.$id;
        }
        $sql = "DELETE FROM `{$this->tablePrefix}comment` WHERE type=`vote` AND {$where}";
        return $this->execute( $sql );
    }

    
        /**
         * getBlogList 
         * 通过userId获取到用户列表
         * 通过用户Id获取用户心情
         * @param array|string|int $userId 
         * @param array|object $options 查询参数
         * @access public
         * @return object|array
         */
        public function getVoteList($map = null,$field=null,$order = null,$limit = null) {
            //处理where条件
            $map = $this->merge( $map );
            //连贯查询.获得数据集
            $result         = $this->where( $map )->field( $field )->order( $order )->findPage(20) ;
            return $result;
        }

    public function doDeleteVote($id){
        $voteUser        = D( 'VoteUser' );
        $voteOpt         = D( 'VoteOpt' );
                  
        $map2['vote_id'] = $map1['id'] = $id;

        //删除动态
        $feedId = $this->where( $map1 )->field( 'feedId' )->findAll();
        $userFeedId = $voteUser->where( $map2 )->field( 'feedId' )->findAll();

        $temp_userFeedId = array();
        $temp_feedId     = array();
        foreach( $userFeedId as $value ){
            if( !empty($value['feedId']) && $value['feedId'] != 0 )
                    $temp_userFeedId[] = $value['feedId'];
        }
        foreach( $feedId as $value ){
            $temp_feedId[] = $value['feedId'];
        }
        //投票的动态
        $feedId_list = array_merge($temp_userFeedId,$temp_feedId);
        $where = "id in ( ".implode(',',$feedId_list )." )";
        $sql = "DELETE FROM {$this->tablePrefix}feed
                WHERE $where
            ";
        $result = $this->execute( $sql );


        //删除投票
        $result1 = $this->where( $map1 )->delete();

        //删除投票选项库
        $result2 = $voteOpt->where( $map2 )->delete();

        //删除投票参与人员库
        $result3 = $voteUser->where( $map2 )->delete();

        //删除投票评论数据库
        $result4 = $this->deleteComment( $id );


        if( $result1 && $result2 && $result3){
            return true;
        }else{
            return false;
        }

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
         * getConfig 
         * 获取配置
         * @param mixed $index 
         * @access public
         * @return void
         */
        public function getConfig( $index ){
            $config = $this->config->$index;
            return $config;
        }
} 
?>
