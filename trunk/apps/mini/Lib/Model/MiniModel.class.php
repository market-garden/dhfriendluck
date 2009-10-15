<?php
    /**
     * MiniModel 
     * 迷你博客Model层。操作相关迷你博客的数据业务逻辑
     * @package Model::Mini
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class MiniModel extends BaseModel {
        /**
         * _type 
         * 心情的种类.默认是1.
         * @var int
         * @access public
         */
        public $_type = 1;

        public function _initialize(){
            //初始化只搜索status为0的。status字段代表没被删除的
            $this->status = 0;
            $emotion_obj  = D( 'Smile' );
            $config_obj   = D( 'AppConfig' );

            //获取配置和表情信息
            $config       = $config_obj->getConfig();
            $emotion      = $emotion_obj->getSmile( $config['smiletype'] );
            $configarray['ico'] = $emotion;
            if($config && $configarray) $configarray += $config;
            $this->setConfig( $configarray );
            parent::_initialize();
        }
        public function getReplayCount( $data ){
            $map['appid'] = $data['id'];
            $map['type'] = "mini";
            $comment = D( 'Comment' );
            $result = $comment->where( $map )->count();
            return $result;
        }
        /**
         * getMiniInfo
         * 通过userId获取迷你博客的信息
         * 
         * @param int $userId 用户id
         * @param array|string $options 要返回的字符串
         * @access public
         * @return void
         */
        public function getLastMini ( $userId, $options = null) {
            //过滤参数
            if( is_array( $options ) ){
                $temp    = $options;
                $options = implode( ',',$options );
            }
            $result = $this->where ( "uid = '$userId' and status = 0" )->field ( $options )->order ( 'id desc' )->find ();
                $result['content'] = str_replace('{PUBLIC_URL}',__PUBLIC__,$this->replaceContent( $result['content'] ));
                        //清空data数组。防止污染
            if( !isset( $temp ) ){
                $temp = explode( ",", $options );
            }
            foreach( $temp as $value){
                unset( $this->data[$value] );
            }
            $this->status = 0;
            $result = array( $result,$this->getIco(),$this->config->stringcount );
            return $result;
        }

        /**
         * getMiniList 
         * 通过userId获取到用户列表
         * 通过用户Id获取用户心情
         * @param array|string|int $userId 
         * @param array|object $options 查询参数
         * @access public
         * @return object|array
         */
        public function getMiniList($map = null,$field=null,$order = null,$limit = null) {
            //处理where条件
            $map = $this->merge( $map );
            $limit =   !isset( $limit ) ? $this->config->pagenum:$limit;

            //连贯查询.获得数据集
            $result         = $this->where( $map )->field( $field )->order( $order )->findPage( intval( $limit ));

            //对数据集进行处理
            $data           = $result['data'];
            $data           = $this->replace( $data );//替换表情
            $data           = $this->appendReplay($data);//追加回复

            //如果是自己发的心情，可以删除
            foreach( $data as &$value ){
                if( $value['uid'] == $this->uid )
                    $value['isDelete'] = true;
            }
            $result['data'] = $data;
            return $result;

        }

        /**
         * doDeleteMini 
         * 删除Mili博客，检查配置DELETE=true,则真实删除。如果DELETE=false，则是状态为1;
         * @param array|string $map 删除条件
         * @access public
         * @return void
         */
        public function doDeleteMini( $map = null ) {
            //获得配置信息
            $config    = intval($this->config->delete);

            //获得删除条件
            $condition = $this->merge( $map );
            $mid = $this->where( $condition )->getField('uid');

            //判断是否合法。不允许删除整个表
            if( !isset( $condition ) && empty( $condition ) )
                throw new ThinkException( "不允许删除整个表" );
            //如果配置文件中delete的值为true则真是删除，如果delete=false,则设置status＝1;

            if( false == $config ){
                $result = $this->where( $condition )->setField( 'status',1 );
                $count = $this->where( 'uid='.$mid.' AND status <> 1' )->count();
            }else{
                $reuslt = $this->where( $condition )->delete();
                $count = $this->where( 'uid='.$mid )->count();
            }
            $result = $this->api->space_changeCount( 'mini',$count );
            return $result;
        }

        /**
         * getMyReplyMini 
         * 获得我评论过的心情
         * @param mixed 
         * @access public
         * @return void
         */
        public function getMyReplyMini( $appid ){
            $condition['id'] = array( 'in',$appid );
            $condition['status'] = 0;
            $result = $this->getMiniList( $condition,'*','cTime DESC' );
            return $result;
        }

        public function getMyReplyId($condition){
            $comment     = D( 'Comment' );
            
            $map['type'] = APP_NAME;
            $map        += $condition;
            unset( $condition );

            $myReplyMini = $comment->where( $map )->field( 'distinct(appid)' )->findAll();

            $appid       =  array();
            foreach( $myReplyMini as $value ){
                $appid[] = $value['appid'];
            }
            return $appid;
        }

        /**
         * getIco 
         * 获取表情数组
         * @access public
         * @return void
         */
        public function getIco (){
            return $this->config->ico;
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
                $result = parent::fileAway( $findTime,$condition );
                $result['data'] = $this->replace( $result['data'] );//替换表情
                $result['data'] = $this->appendReplay( $result['data'] );//追加回复信息
                $result['data'] =intval( $this->config->replay ) ?$this->appendReplay( $result['data'] ):$result['data'];

            //如果是自己发的心情，可以删除
            foreach( $result['data'] as &$value ){
                if( $value['uid'] == $this->uid )
                    $value['isDelete'] = true;
            }
                //追加用户名
                return $result;
        }
        /**
         * doAddMini 
         * 添加心情
         * @param mixed $map 条件
         * @access public
         * @return void
         */
        public function doAddMini ($map) {
            $map['cTime'] = time();
            $name = $this->getOneName( $map['uid'] );
            $map['name'] = $name['name'];
            $map['type']  = $this->_type;
            $map     = $this->merge( $map );
            //$content = $this->replaceContent( $map['content'] );//替换文本表情


            $result = $this->add( $map );


            if( !$result ){
                return false;
            }
             setScore($map['uid'],'add_mini');
            //发送通知。如果发送失败，则返回false
            //$temp = substr( __PUBLIC__,4,strlen( __PUBLIC__ )-4 );
            $title['content'] = $this->replaceContent( $map['content'] );
            $body_data["id"] = $result;
            $body_data["uid"] = $map['uid'];
            $body_data["con"] = $map['content'];
            //发出动态，并更新feedId
            $map2    = $this->doFeed("mini",$title,$body_data);
            if( false != $map2 ){
                $result = $this->where( 'id ='.$result )->setField( "feedId",$map2 );
            }
            if( $this->config->delete ){
                $count = $this->where( 'uid='.$map['uid'].' AND status <> 1' )->count();
            }else{
                $count = $this->where( 'uid='.$map['uid'])->count();
            }

            $result = $this->api->space_changeCount( 'mini',$count );


            return  str_replace('{PUBLIC_URL}',__PUBLIC__,$this->replaceContent( $map['content'] ));;
        }

        public function replaceContent($data){
            return parent::replaceContent($data);
        }

        /**
         * doAddReplay 
         * 增加回复
         * @param array $data 
         * @access public
         * @return void
         */
        public function addReplay( $data,$more,$mid ){
          
            $data['cTime'] = time();
            $data['type']  = APP_NAME;
            $comment = D( 'Comment' );
            //返回的值为这条心情回复的记录数。存储在mini的数据表里面
            $this->id = $data['appid'];
            $add    = $comment->addComment( $data );
            $addid = $add['id'];
            $count = $add['count'];

            if( empty( $count ) ){
                    return false;
            }

            $this->where( $this->data )->setField( 'replay_numbel',$count);

            //处理时间
            $time = $data['cTime'];
            $data['cTime'] = friendlyDate( $data['cTime'] );
            $data['id'] = $addid;

            //追加头像
            $data['face'] = getUserFace( $this->uid );
            //追加删除
            $data['isDelete']  = true;
            //获得刚添加的回复的html页面.复用原来用与获取全部回复的方法
            if( 2 >= $count || 'false' == $more ){
                $newReplay = $data;
            }else{
                $newReplay['newReplay'] = $data;
                $newReplay['OddReplay'] = $this->_internalReplay( $data['appid'],$time,$mid );
            }
            $newReplay = json_encode( $newReplay );

            return  $newReplay;
        }

        /**
         * ChangeCount 
         * 改变计数
         * @param mixed $count 
         * @access public
         * @return void
         */
        public function ChangeCount($id,$count = null){
          $sql = "UPDATE {$this->tablePrefix}mini
                    SET replay_numbel=replay_numbel-1
                    WHERE id='$id' LIMIT 1 ";
            $result = $this->execute($sql);
            if ( $result ){
                return true;

            }
            return false;

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

            if( is_numeric( $config ) ){
                $config = intval( $config );
            }

            return $config;
        }


        /**
         * unsetConfig 
         * 删除配置
         * @param mixed $index 
         * @param mixed $group 
         * @access public
         * @return void
         */
        public function unsetConfig( $index , $group = null ){
            if( isset( $group ) ){
                unset( $this->config->$group->$index );
            }else{
                unset( $this->config->$index );
            }
            return $this;
        }

        
        /**
         * _internalReplay 
         * 获取剩余的回复
         * @param mixed $id 
         * @access public
         * @return void
         */
        public function _internalReplay( $id ,$time = null,$uid){
            $comment = D( 'Comment' );
            $data = $comment->getComment( APP_NAME,$id,true,null,$time );
            
            if( !$data ){
                return false;
            }

            //去除第一个
            array_shift( $data );

            //处理附带判断
            foreach( $data as &$value ){
                $value['cTime'] = friendlyDate( $value['cTime'] );//追加时间
                $value['face']  = getUserFace( $value['uid'] );//追加头像
                $value['isDelete'] = (( $value['uid'] == $this->uid )  //这个帖子是自己发的
                                        && !empty( $this->uid ))  //必须登录后
                                     || $uid == $this->uid  //只要是在我的心情里的回复都能删除
                                     ?
                                     true:false;
            }
           // $html = W( "OddReplay",array( 'replay'=>$data) ,true);
            return $data;
        }
        /**
         * DateToTimeStemp 
         * 时间换算成时间搓返回
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
         * deleteFeed 
         * 删除动态
         * @param mixed $id  mini的Id 
         * @access public
         * @return void
         */
        public function deleteFeed( $id ){
            $feedId = $this->where( 'id ='.$id )->getField( 'feedId' );
            $sql = "DELETE
                    FROM `{$this->tablePrefix}Feed`
                    WHERE `id` = {$feedId}
                    LIMIT 1";
            return $this->execute( $sql );
        }

        /**
         * checkType 
         * 检查种类和配置。
         * @access private
         * @return void
         */
        private function checkType ( $type = null ){
            $type = isset($this->config->type->$type)?$this->config->type->$type:true;
            //检查配置中对种类的权限处理，如果为真，则可以发送动态，如果为假，则不可以发送动态
            if( $type ){
                return true;
            }else{
                return false;
            }

        }

        /**
         * appendReplay 
         * 追加回复到数据集
         * @param mixed $data 
         * @access private
         * @return void
         */
        private function appendReplay( $data ){
            $result = $data;
            $comment = D( 'Comment' );

            foreach( $result as $key=>&$value ){
                //如果replay_numbel有值。就将评论获取出来追加在上面
                if( 0 != $value['replay_numbel']) {
                        $value['replay'] = $comment->getComment(APP_NAME,$value['id'],false,$value['replay_numbel']);
                }else{
                        $value['replay'] = array( 'id'=>$value['id']);
                }
                $value['replay']['uid'] = $this->uid;
                $value['replay']['mid'] = $value['uid'];
            }
            return $result;
        }
    }
