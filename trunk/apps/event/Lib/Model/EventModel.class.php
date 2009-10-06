<?php
    Import( '@.Unit.Common' );
    /**
     * EventModel
     * 活动主数据库模型
     * @uses BaseModel
     * @package
     * @version $id$
     * @copyright 2009-2011 SamPeng
     * @author SamPeng <sampeng87@gmail.com>
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class EventModel extends BaseModel{

        public function _initialize(){
            $config_obj   = D( 'AppConfig' );
            ////获取配置
            $config = $config_obj->getConfig();
            $config = Common::changeType( $config,'int' ); //将数组各元素转换成int类型
            $this->setConfig( $config );
        }
        public function setCount($appid,$count){
            $map['id'] = $appid;
            $map2['commentCount'] = $count;
            return $this->where($map)->save($map2);
        }
        public function getEventList( $uid,$order = false,array $date=array() ){
            $user = self::factoryModel( 'user' );
            if( isset( $uid ) ){
                $map['uid']  = is_array( $uid )?array( 'in',$uid ):$uid;
            }
            //获取活动列表
            if( false == $order ){
                switch( $_GET['action'] ){
                    case "join":
                        $map['action'] = 'joinIn';
                        break;
                    case "admin":
                        $map['action'] = 'admin';
                        break;
                    case "att":
                        $map['action'] = 'attention';
                        break;
                    default:
                        $map['action'] = array( 'in',"'admin','joinIn','attention'" );
                }
                $eventId_list = $user->where( $map )->field( 'eventId' )->order( 'cTime DESC' )->findAll();
                unset( $map['action'] );
                $temp = array();
                foreach( $eventId_list as $value ){
                    $temp[] = $value['eventId'];
                }

                $temp_map = $map;
                unset( $temp_map['uid'] );
                $temp_map['id'] = array( 'in',$temp );
                $order = 'cTime DESC';
            }else{
                //所有活动页面处理
                switch( $_GET['action'] ){
                    case "hot":  //推荐
                        $opts = self::factoryModel( 'opts' );
                        $eventId_list = $opts->where( 'isHot = 1' )->field( 'id' )->findAll();
                        $temp = array();
                        foreach( $eventId_list as $value ){
                            $temp[] = $value['id'];
                        }
                        $temp_map['optsId'] = array( 'in',$temp );
                        $order              = 'cTime DESC';
                        break;
                    case "popular": //人气
                        $order = 'joinCount DESC';
                        break;
                    default: //默认全部活动
                        $eventId_list = $user->where(  )->field( 'distinct( eventId )' )->order( 'cTime DESC' )->findAll();
                        $temp = array();
                        foreach( $eventId_list as $value ){
                            $temp[] = $value['eventId'];
                        }
                        $temp_map['id'] = array( 'in',$temp );
                    $order = 'cTime DESC';
                }
            }
            $temp_map = array_merge( $temp_map,$date ) ;
            //$temp_map += $date;
            $result = $this->where( $temp_map )->order( $order )->findPage( $this->config->limitpage );

            //追加必须的信息
            if( !empty( $result['data'] )){
                $friendsId = $this->api->friend_get();
                $map['action']    = 'joinIn';
                $map['status']    = 1;
                $map['uid']       = $friendsId?array( 'in',$friendsId):null;

                foreach( $result['data'] as &$value ){
                    $value = $this->appendContent( $value );
                    //追加参与的好友
                    $map['eventId']   = $value['id'];
                    $value['friends'] = $user->where( $map )->findAll();
                    $cover[] = $value['coverId'];
                }

                $cover_map['id'] = array( 'in',$cover );
                $cover_list = D( 'Attach' )->getAttach( $cover_map );
                foreach( $result['data'] as &$value ){
                    $cover = $cover_list[$value['coverId']];
                    $temp_cover = $cover[0].$cover[1];
                    $value['cover'] = $temp_cover?UPLOAD_URL.$temp_cover:C( 'TS_URL' ).'/public/theme_default/images/hdpic1.gif';
                }
            }
            return $result;
        }

        /**
         * getEventContent
         * 获得活动具体类容页
         * @param mixed $eventId
         * @param mixed $uid
         * @param mixed $mid
         * @access public
         * @return void
         */
        public function getEventContent( $eventId,$uid){
            //分别获得用户和照片数据库模型
            $user = self::factoryModel( 'user' );
            $photo = self::factoryModel( 'photo' );
            $map['id'] = $eventId;

            $map    = $this->merge( $map );

            $result = $this->where( $map )->find();

            //检查是否正确的管理员id
            if( $uid != $result['uid']  ){
                return false;
            }

            //追加相册图片
            $result['photolist'] = $photo->getPhotos( $eventId,10 );
            //追加参与者和关注者
            $join = $att = array();
            $att['action'] = 'attention';
            $att['eventId'] = $result['id'];
            $att['status']  = 1;
            $join = $att;
            $join['action'] = 'joinIn';


            $result['attention'] = $user->getUserList( $att,4 );
            $result['member']    = $user->getUserList( $join,16 );
            $result['lc'] = 5000000 < $result['limitCount'] ? "无限制":$result['limitCount'];
            $result = $this->appendContent( $result );
            //TODO
            $result['cover'] = ( 0 ==$result['coverId'] )?C( 'TS_URL' ).'/public/theme_default/images/hdpic1.gif':UPLOAD_URL.D( 'Attach' )->getOneAttach($result['coverId']);
            return $result;
        }

        /**
         * doAddEvent
         * 添加活动
         * @param mixed $map
         * @param mixed $feed
         * @access public
         * @return void
         */
        public function doAddEvent($eventMap,$optsMap,$cover){
            $eventMap['cTime'] = isset( $eventMap['cTime'] )?$eventMap['cTime']:time();
            $eventMap['coverId']   = $cover['status']?$cover['info'][0]['id']:0;
            $eventMap['limitCount']  = 0 == intval($eventMap['limitCount']) ? 999999999:$eventMap['limitCount'];
            $eventMap['explain'] = nl2br($eventMap['explain']);
            $has_friend        = $optsMap['opts']['friend'];
            $optsMap['opts']   = serialize( $optsMap['opts'] );

            //false
            $optsDao = D( 'EventOpts' );
            if( $eventMap['optsId'] = $optsDao->add( $optsMap )){
                $eventMap = $this->merge( $eventMap );
                $addId    = $this->add( $eventMap );
            }else{
                return false;
            }

            //添加参与动作
            $user           = self::factoryModel( 'user' );
            $map['uid']     = $eventMap['uid'];
            $map['name']    = $eventMap['name'];
            $map['eventId'] = $addId;
            $map['action']  = 'admin';
            $map['ctime']   = 'cTime';
            $user->add( $map );

            //如果是只有好友可参与，给所有好友发送通知
            if( 1 == $has_friend  ){
                $temp = $this->api->friend_get();
                $title_data['title'] = sprintf("<a href=\"%s/Index/eventDetail/id/%s/uid/%s\">%s</a>",__APP__,$addId,$eventMap['uid'],$eventMap['title']);
                $body['content']     = getBlogShort(t($eventMap['explain']),40);
                $url                 = sprintf( "%s/eventDetail/id/%s/uid/%s",__URL__,$addId,$eventMap['uid'] );
                $this->doNotify( $temp,"add_event",$title_data,$body,$url );
            }

            //发送动态
            $title['title']   = sprintf("<a href=\"%s/Index/eventDetail/id/%s/uid/%s\">%s</a>",__APP__,$addId,$eventMap['uid'],$eventMap['title']);
            //$body['content'] = getBlogShort(t($map['explain']),40);
            $feedId = $this->doFeed("event",$title);
            if( $feedId != false ){
                $temp['feedId'] = $feedId;
                $this->where( 'id ='.$addId )->save( $temp );
            }


            $count = $this->where( 'uid='.$map['uid'].' AND deadline>'.time() )->count();
            $result = $this->api->space_changeCount( 'event',$count );

            return $addId;

        }

        public function doEditEvent($eventMap,$optsMap,$cover,$id){
            $eventMap['cTime'] = isset( $eventMap['cTime'] )?$eventMap['cTime']:time();
            $eventMap['coverId']   = $cover['status']?$cover['info'][0]['id']:0;
            $eventMap['limitCount']  = 0 == intval($eventMap['limitCount']) ? 999999999:$eventMap['limitCount'];

            $has_friend        = $optsMap['opts']['friend'];
            $optsMap['opts']   = serialize( $optsMap['opts'] );

            //false
            $optsDao = D( 'EventOpts' );
            if( $eventMap['optsId'] = $optsDao->where( 'id='.$id['optsId'] )->save( $optsMap )){
                $eventMap = $this->merge( $eventMap );
                $addId    = $this->where( 'id ='.$id['id'] )->save( $eventMap );
            }else{
                return false;
            }

            return $addId;

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
         * checkRoll
         * 检查权限
         * @param mixed $uid
         * @access public
         * @return void
         */
        public function checkMember( $uid,$eventAdmin,$opts,$mid ){
            $result = array(
                            'admin'   => false,
                            'canJoin' => true,
                            'canAtt'  => true,
                            'hasMember' =>false,
                            );
            if( $mid == $eventAdmin ){
                $result['admin']   = true;
                $result['canJoin'] = false;
                $result['canAtt']  = false;
                return $result;
            }


            //如果是好友可以参加
            if( 1 == $opts['friend'] ){
                if( false == $this->api->friend_areFriends( $uid,$mid ) )
                    $result['canJoin'] = false;
            }
            return $result;
        }


        /**
         * appendContent
         * 追加和反解析数据
         * @param mixed $data
         * @access public
         * @return void
         */
        public function appendContent( $data ){
            $opts = self::factoryModel( 'opts' );
            $type = self::factoryModel( 'type' );
            $data['type'] = $type->getTypeName( $data['type'] );


            //反解析时间
            $data['time'] = date( 'Y-m-d H:i:s',$data['sTime'] )." 至 ".date( 'Y-m-d H:i:s',$data['eTime'] );
            $data['dl']   = date( 'Y-m-d H:i:s',$data['deadline'] );

            //追加选项内容
            $opts_list    = $opts->getOpts( $data['optsId'] );
            //追加城市和其他选项
            $data['city']        = $opts_list['province']." ".$opts_list['city']." ".$opts_list['area'];
            $data['opts']        = unserialize( $opts_list['opts'] );
            $data['cost']        = $opts_list['cost'];
            $data['costExplain'] = $opts_list['costExplain'];
            $data['isHot']       = $opts_list['isHot'];

            //追加权限
            $data += $this->checkMember( $data['uid'],$data['uid'],$data['opts'],$this->mid );

            //追加是否已投票和是否已关注的判定
            $userDao = D( 'EventUser' );
            if( $result = $userDao->hasUser( $this->mid,$data['id'],'joinIn' ) ){
                $data['canJoin']   = false;
                $data['canAtt']    = false;
                $data['hasMember'] = $result->status;
                return $data;
            }

            if( $userDao->hasUser( $this->mid,$data['id'],'attention' ) ){
                $data['canAtt'] = false;
            }

            return $data;
        }
        /**
         * doAddUser
         * 添加用户行为
         * @param mixed $data
         * @param mixed $alow
         * @access public
         * @return void
         */
        public function doAddUser( $data,$alow ){
            $userDao = self::factoryModel( 'user' );
            $optsDao = self::factoryModel( 'opts' );

            //检查这个id是否存在
            if( false == $event = $this->where( 'id ='.$data['id'] )->find() ){
                return -1;
            }

            if( $data['action'] === 'joinIn' ){
                //自动获取已填写的可公开联系方式

                $contact = $this->api->userInfo_get( $event['uid'],'contact');

                $array = array( 'QQ','MSN','电话' );
                foreach( $contact as $key=>$value ){
                    if( in_array( $key,$array ) ){
                        $contacts .= $key.":".$value?$value:"隐藏信息"." ";
                    }
                }
            }

            //检查好友仅参与选项
            $opts = $optsDao->where( 'id='.$event['optsId'] )->find();
            $opt = unserialize($opts['opts']);
            $role = $this->checkMember( $data['uid'],$event['uid'],$opt,$data['uid'] );


            //检查是否已经加入或者关注
            if( $result = $userDao->hasUser( $data['uid'],$data['id'],$data['action'] ) ){
                return -2;
            }
            $map = $data;
            $map['eventId'] = $data['id'];
            unset( $map['id'] );
            $map['cTime']   = time();
            $map['contact'] = $contacts;

            switch( $data['action'] ){
                case "attention":
                    if( false === $opts['canAtt'] ){
                        return -2;
                    }
                    if($userDao->add( $map )){
                        $this->setInc('attentionCount','id='.$map['eventId']);
                        setScore($data['uid'], 'att_event');
                        return 1;
                    }else{
                        return 0;
                    }
                    break;
                case "joinIn":
                    if( false === $opts['canJoin'] ){
                        return -2;
                    }
                    $map['status'] = $alow?0:1;
                    if($userDao->add( $map )){
                        //如果有参与的情况。删除单于的数据集
                        if( 0 == $alow ){
                            $this->setInc('joinCount','id='.$map['eventId']);
                            $this->setDec('limitCount','id='.$map['eventId']);
                            setScore($data['uid'], 'join_event');
                            $map['status'] = 1;
                        }else{
                            $map['status'] = $alow?0:1;
                        }

                        $temp_map['uid']      = $map['uid'];
                        $temp_map['action']   = 'attention';
                        $temp_map['eventId']  = $map['eventId'];
                        if( $id = $userDao->where( $temp_map )->getField('id') ){
                            $userDao->delete( $id );
                            $this->setDec('attentionCount','id='.$map['eventId']);
                        }
                        setScore($event['uid'],'corresond_event');
                        return 1;
                    }else{
                        return 0;
                    }
                    break;
                default:
                    return -3;
            }
        }

        /**
         * doArgeUser
         * 同意申请
         * @param mixed $data
         * @access public
         * @return void
         */
        public function doArgeUser( $data ){
            $userDao = self::factoryModel( 'user' );
            if($userDao->where('id='.$data['id'])->setField( 'status',1)){
                $this->setInc('joinCount','id='.$data['eventId']);
                $this->setDec('limitCount','id='.$data['eventId']);
                setScore($data['uid'], 'join_event');
                //如果有参与的情况。删除单于的数据集
                $data['action'] = 'attention';
                if( $id = $userDao->where( $data )->getField('id') ){
                    $userDao->delete( $id );
                    $this->setDec('attentionCount','id='.$data['eventId']);
                }
                return 1;
            }
            return 0;
        }

        /**
         * doDelUser
         * 取消关注和参加
         * @param mixed $data
         * @access public
         * @return void
         */
        public function doDelUser( $data,$allow = null ){
            $userDao = self::factoryModel( 'user' );
            //检查这个id是否存在
            if( false == $event = $this->where( 'id ='.$data['id'] )->find() ){
                return -1;
            }
            //检查是否存在。如果存在，删除这条记录
            $map['uid']     = $data['uid'];
            $map['eventId'] = $data['id'];
            $map['action']  = $data['action'];
            //检测是否存在这个用户
            if( $result = $userDao->hasUser( $data['uid'],$data['id'],$data['action'] ) ){
                //删除用户操作记录
                if($result = $userDao->where( $map )->delete()){
                    $deleteMap['id'] = $map['eventId'];
                    switch( $map['action'] ){
                        case "attention":
                            $delete = "attentionCount";
                            $this->setDec( $delete,$deleteMap );
                            break;
                        case "joinIn":
                            $delete = "joinCount";
                            $this->setInc( 'limitCount',$deleteMap );
                            if( false == $allow ){
                                $this->setDec( $delete,$deleteMap );
                            }
                            break;
                    }
                    //记录数相应减1
                    return 1;
                }
            }else{
                return -2;
            }
        }

        public function getMember( $map,$uid,$name ){
            $user             = self::factoryModel( 'user' ) ;
            $result = $user->getUserList( $map,20,true);
            $data = $result['data'];
            //修正成员状态
            foreach ( $data as $key=>$value ){
                if( $value['uid'] == $uid ){
                    $result['data'][$key]['role'] = "管理员";
                }else{
                    if( 'joinIn' == $value['action'] ){
                        $result['data'][$key]['role'] = "成员";
                    }
                    if( 'attention' == $value['action'] ){
                        $result['data'][$key]['role'] = "关注中";
                    }
                    if( 'joinIn' == $value['action'] && 0 == $value['status'] ){
                        $result['data'][$key]['role'] = "待审核";
                    }
                }
            }
            return $result;
        }

        public function doEditData( $time,$id ){
            //检查安全性，防止非管理员访问
            $uid = $this->where( 'id='.$id )->getField( 'uid' );
            if( $uid != $this->mid ){
                return -1;
            }

            if( $this->where( 'id='.$id )->setField( 'deadline',$time ) ){
                return 1;
            }else{
                return 0;
            }
        }

        /**
         * getList
         * 供后台管理获取列表的方法
         * @param mixed $order
         * @param mixed $limit
         * @access public
         * @return void
         */
        public function getList( $order,$limit ){
            $map = $this->merge( null );
            $result = $this->where( $map )->order( $order )->findPage($limit);
            //将属性追加
            foreach( $result['data'] as &$value ){
                    $value = $this->appendContent( $value );
            }
            return $result;
        }

        /**
         * doDeleteEvent
         * 删除活动
         * @param mixed $eventId
         * @access public
         * @return void
         */
        public function doDeleteEvent( $eventId ){
            //TODO 检查是否是管理员

            if( empty( $eventId ) ){
                return false;
            }
            //取出动态id和选项
            $result = $this->where( $eventId )->field( 'feedId' )->findAll();
            $feedId = array();
            $uid = $this->where( $eventId )->getField('uid');

            foreach( $result as $value ){
                $feedId[] = $value['feedId'];
            }
            $feedId = implode( ',',$feedId );

            //删除活动
            if( $this->where( $eventId )->delete() ){
                $mapId = '`id` IN ( '.$feedId.' )';

                //删除动态
                $sql = "DELETE FROM {$this->tablePrefix}feed
                        where $mapId
                        ";
                $test = $this->execute($sql);

                return true;
                //D( 'EventPhoto' )->where( 'id='.$optsId )->delete();
            }

            $count = $this->where( 'uid='.$uid.' AND deadline>'.time() )->count();
            $result = $this->api->space_changeCount( 'event',$count );
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
            return $config;
        }

        /**
         * doIsHot
         * 设置推荐
         * @param mixed $map
         * @param mixed $act
         * @access public
         * @return void
         */
        public function doIsHot( $map,$act ){
            if( empty($map) ){
                throw new ThinkException( "不允许空条件操作数据库" );
            }
            $map['id'] = $this->where( $map )->getField( 'optsId' );

            switch( $act ){
                case "recommend":   //推荐
                    $field = array( 'isHot' );
                    $val = array( 1);
                    $result = D( 'EventOpts' )->setField( $field,$val,$map );
                break;
                case "cancel":   //取消推荐
                    $field = array( 'isHot' );
                    $val = array( 0 );
                    $result = D( 'EventOpts' )->setField( $field,$val,$map );
                    break;
            }
            return $result;
        }

        /**
         * hasMembel
         * 判断是否是有这个成员
         * @param mixed $uid
         * @access public
         * @return void
         */
        public function hasMembel( $uid, $eventId){
            $user = self::factoryModel( 'user' );
            if( $result = $user->where( 'uid='.$uid.' AND eventId='.$eventId )->field( 'action,status' )->find() ){
                return $result;
            }else{
                return false;
            }
        }
    }
