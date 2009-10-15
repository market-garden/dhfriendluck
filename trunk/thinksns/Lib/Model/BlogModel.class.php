<?php
    Import( '@.Unit.Common' );
    /**
     * BlogModel 
     * 迷你博客Model层。操作相关迷你博客的数据业务逻辑
     * @package Model::Blog
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class BlogModel extends BaseModel {
        /**
         * _type 
         * 日志的种类。默认为0
         * @var float
         * @access public
         */
        public $_type = 0;

        /**
         * limit 
         * 每页显示多少条
         * @var float
         * @access public
         */
        private $limit = 10;
        private static $uid = 0;

        public function _initialize(){
            //初始化只搜索status为0的。status字段代表没被删除的
            $this->status = 1;
            //$emotion_obj  = D( 'Smile' );
            $config_obj   = D( 'BlogConfig' );
            ////获取配置
            $config = $config_obj->getConfig( APP_NAME );
            $config = Common::changeType( $config,'int' ); //将数组各元素转换成int类型
            $this->setConfig( $config );
            parent::_initialize();
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
        public function getBlogList($map = null,$field=null,$order = null,$limit = null) {
            //处理where条件
            $map = $this->merge( $map );
            //连贯查询.获得数据集
            $result         = $this->where( $map )->field( $field )->order( $order )->findPage( $this->config->limitpage) ;
            //对数据集进行处理
            $data           = $result['data'];
            $data           = $this->replace( $data ); //本类重写父类的replace方法。替换日志的分类和追加日志的提及到的人
            //$data           = intval( $this->config->replay ) ? $this->appendReplay($data):$data;//追加回复
            $result['data'] = $data;
            return $result;

        }

        /**
         * getBlogContent 
         * 重写父类的getBlogContent
         * @param mixed $id 
         * @param mixed $how 
         * @param mixed $uid 
         * @access public
         * @return void
         */
        public function getBlogContent( $id,$how =null,$uid = null  ){
            $result         = parent::getBlogContent( $id,$how,$uid );
            $result['role'] = $this->checkCommentRole( $result['canableComment'],$result['uid'],self::$uid );
            return $result;
        }
        public function setUid($value){
            self::$uid = $value;
        }
        /**
         * getMentionBlog
         * 获取提到我的好友的帖子数据
         * @param mixed $uid 
         * @access public
         * @return void
         */
        public function getMentionBlog( $uid = null ){
            $mention   = self::factoryModel( 'mention' );


            if( isset( $uid ) ){
                $userId = $uid;
            }else{
                //获得当前登录者的好友
                $userId   = $this->getFriends();
            }

            //通过用户id获得相应的blogid列表
            $bloglist   = $mention->getUserMention( $userId );
             
            //获得blogId列表和组装查询条件
            $blogidlist = array_keys( $bloglist );
            if( empty( $bloglist ) )
                return false;
            $map['id']  = array( 'in',$blogidlist );
             
            //组合查询条件，如，status=1;
            $map        = $this->merge( $map );

            //返回查询结果
            if( $result = $this->where( $map )->findPage( $this->config->limitpage) ){
                $data           = $this->replace( $result['data'],$bloglist );
                $result['data'] = $data;
                return $result;
            }
            return false;
            
        }

        public function getCategory( $uid ){
                $category        = self::factoryModel( 'Category' );
                if( isset( $uid ) ){
                    $categorycontent = $category->getUserCategory($uid);
                }else{
                    $categorycontent = $category->getCategory();
                }
                return $categorycontent;
        }

        /**
         * checkCommentRole 
         * 检查是否可以评论
         * @param mixed $role 评论权限
         * @param mixed $userId 日志所有者
         * @access protected
         * @return void
         */
        private function checkCommentRole( $role,$userId,$mid ){
            if( $userId == $mid ){
                return 1;
            }
            switch ( $role ){
                case 1:  //全站可评论
                    return 1;
                case 2:  //好友可评论
                    if( $this->api->friend_areFriends($mid,$userId) ){
                        return 1;
                    }else{
                        return 2;
                    }
                case 3:  //关闭评论
                    return 3;
            }
        }
        /**
         * getBlogCategoryCount 
         * 根据uid获得日志分类的日志数
         * @param mixed $uid 
         * @access public
         * @return void
         */
        public function getBlogCategoryCount( $uid ){
            $sql = "SELECT count( 1 ) as count, category
                    FROM `{$this->tablePrefix}blog`
                    WHERE `category` IN (
                                          SELECT `id`
                                          FROM {$this->tablePrefix}blog_category
                                          WHERE `uid` = 0 OR `uid` = {$uid} 
                                      ) AND `uid` = {$uid} AND `status` = 1
                                          GROUP BY category
                    ";
            $result = $this->query( $sql );
            return $result;
        }


        /**
         * doDeleteBlog 
         * 删除Mili博客，检查配置DELETE=true,则真实删除。如果DELETE=false，则是状态为1;
         * @param array|string $map 删除条件
         * @access public
         * @return void
         */
        public function doDeleteBlog( $map = null,$uid=null ) {
            //获得配置信息
            $config    = $this->config->delete;

            //获得删除条件
            $condition = $this->merge( $map );

            //检测uid是否合法
            $mid = $this->where( $condition )->getField( 'uid' );
            if( $uid != $mid ){
                return false;
            }



            //判断是否合法。不允许删除整个表
            if( !isset( $condition ) && empty( $condition ) )
                throw new ThinkException( "不允许删除整个表" );
            //如果配置文件中delete的值为true则真是删除，如果delete=false,则设置status＝2;

            if( false == $config ){
                $result = $this->where( $condition )->setField( 'status',2 );
            }else{
                $reuslt = $this->where( $condition )->delete();
            }

            return $result;
        }

        /**
         * changeCount 
         * 修改日志的浏览数
         * @param mixed $blogid 
         * @access public
         * @return void
         */
        public function changeCount( $blogid ){
            $sql = "UPDATE {$this->tablePrefix}blog
                    SET readCount=readCount+1,hot = commentCount*readCount+round(readCount/(commentCount+1),0)
                    WHERE id='$blogid' LIMIT 1 ";
            $result = $this->execute($sql);
            if ( $result ){
                return true;

            }
            return false;
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
                    $paramTime = self::paramData( $findTime );
                    $start     = $paramTime[0];
                    $end       = $paramTime[1];
                }
                $this->cTime = array( 'between', array( $start,$end ) );
                //如果查询时没有设置其他查询条件，就只是按时间来进行归档查询
                $map = $this->merge( $condition );
                //查询条件赋值
                $result = $this->where( $map )->field( '*' )->order( 'cTime DESC' )->findPage( $this->config->limitpage);
                $result['data'] = $this->replace( $result['data'] );//追加内容

                //追加用户名
                return $result;
        }
        /**
         * doAddBlog 
         * 添加日志
         * @param mixed $map 日志内容
         * @param mixed $feed 是否发送动态
         * @access public
         * @return void
         */
        public function doAddBlog ($map,$feed = true) {
            $map['cTime'] = isset( $map['cTime'] )?$map['cTime']:time();
            $map['type']  = isset( $map['type'])?$map['type']:$this->_type;
            unset( $map['password'] );  //TODO 密码存储
            $friendsId = isset( $map['mention'] )?explode(',',$map['mention']):null;//解析提到的好友
            unset( $map['mention'] );

            $map    = $this->merge( $map );
            $addId  = $this->add( $map );
            $temp = array_filter( $friendsId );

            //添加日志提到的好友
            if( !empty( $friendsId ) && !empty($temp) ){
                $mention = self::factoryModel( 'mention' );
                $result  = $mention->addMention( $addId,$temp );

                //发送通知给提到的好友
                $title_data['title'] = $map['title'];
                $body['content']     = getBlogShort(t($map['content']),40);
                $url                 = sprintf( "%s/show/id/%s/mid/%s",__URL__,$addId,$map['uid'] );
                $this->doNotify( $temp,"blog_mention",$title_data,$body,$url );
            }
            dump( $addId ) ;
            dump( $this->getLastSql() );
            if( !$addId ){
                return false;
            }

            //发送动态
            if( $feed ){
                $title['title']   = sprintf("<a href=\"%s/Index/show/id/%s/mid/%s\">%s</a>",__APP__,$addId,$map['uid'],$map['title']);
                $body['content'] = getBlogShort(t($map['content']),40);
                $this->doFeed("blog",$title,$body);
            }
            return $addId;
        }

        public function doSaveBlog( $map,$blogid ){
            $map['mTime'] = isset( $map['cTime'] )?$map['cTime']:time();
            $map['type']  = isset( $map['type'])?$map['type']:$this->_type;
            unset( $map['password'] );  //TODO 密码存储
            //添加blog相关好友
            $friendsId = isset( $map['mention'] )?explode(',',$map['mention']):null;
            unset( $map['mention'] );
            $map    = $this->merge( $map );

            if( !empty( $friendsId ) ){
                $mention = self::factoryModel( 'mention' );
                $result  = $mention->updateMention( $blogid,$friendsId );
            }
            $addId  = $this->where( 'id = '.$blogid )->save( $map );

            
            if( !$result && !empty( $friendsId ) ){
                return false;
            }

            return $addId;

        }

        /**
         * updateAuto 
         * 更新日志的列表
         * @param mixed $map 
         * @param mixed $id 
         * @access public
         * @return void
         */
        public function updateAuto( $map,$id ){
            $outline = self::factoryModel( 'outline' );
            return $outline->doUpdateOutline( $map,$id );

        }
        /**
         * autosave 
         * 自动保存
         * @param mixed $map 
         * @access public
         * @return void
         */
        public function autosave( $map ){
            $outline = self::factoryModel( 'outline' );
            return $outline->doAddOutline( $map );
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

        public function getBlogTitle( $uid ){
            $map['uid'] = $uid;
            $map = $this->merge( $map );
            return $this->where( $map )->field( 'title,id' )->order( 'cTime desc' )->limit( "0,10" )->findAll();
        }

        /**
         * checkGetSubscribe 
         * 检查和返回以注册过的订阅源
         * @param mixed $uid 
         * @access public
         * @return void
         */
        public function checkGetSubscribe( $uid ){
            $subscribe  = $this->factoryModel( 'subscribe' );
            $map['uid'] = $uid;
            $source_id  = $subscribe->getSourceId( $map );

            unset( $map );

            $source    = $this->factoryModel( 'source' );
            if( empty($source_id))
                return false;
            $map['id'] = array( 'in',$source_id );
            $result    = $source->getSource( $map );

            //重组数据,根据服务名和用户名重组链接
            foreach ( $result as &$value ){
                switch( $value['service'] ) {
                    case "163":
                        $link = "http://%s.blog.163.com/rss/";
                        break;
                    case "sohu":
                        $link = "http://%s.blog.sohu.com/rss";
                        break;
                    case "baidu":
                        $link = "http://hi.baidu.com/%s/rss/";
                        break;
                    case "sina":
                        $link = "http://blog.sina.com.cn/rss/%s.xml";
                        break;
                    case "msn":
                        $link = "http://%s.spaces.live.com/feed.rss";
                        break;
                    default:
                        throw new ThinkException( "系统异常" );
                }
                $value['link'] = sprintf( $link,$value['username'] );
                //unset ( $value['service'] );
                //unset( $value['username'] );
            }
            return $result;
        }

        public function doIsHot( $map,$act ){
            if( empty($map) ){
                throw new ThinkException( "不允许空条件操作数据库" );
            }
            switch( $act ){
                case "recommend":   //推荐
                    $field = array( 'isHot','rTime' );
                    $val = array( 1,time() );
                    $result = $this->setField( $field,$val,$map );
                break;
                case "cancel":   //取消推荐
                    $field = array( 'isHot','rTime' );
                    $val = array( 0,0 );
                    $result = $this->setField( $field,$val,$map );
                    break;
            }
            return $result;
        }

        /**
         * replace 
         * 对数据集进行追加处理
         * @param array $data 数据集
         * @param array $mention 需要被追加的值
         * @access protected
         * @return void
         */
        protected function replace( $data,$mentiondata = null ){
            $result         = $data;
            $categoryname   = $this->getCategory(null);  //获取所有的分类
                 
            //如果$mention为空就需要从数据库中取出数据
            if ( empty( $mentiondata ) ) {
                $mention        = self::factoryModel( 'mention' );
                $mentioncontent = $mention->getUserMention();

            } 
            //TODO 配置信息,截取字数控制


            foreach ( $result as &$value ){
                $value['category'] = array( 
                                        "name" => $categoryname[$value['category']]['name'],
                                        "id"   => $value['category']); //替换日志类型

                 //追加日志中提到的内容
                $value['mention'] = !isset( $mentiondata )?
                                                        $mentioncontent[$value['id']]:
                                                        $mentiondata[$value['id']];
                //日志截断
                $short = $this->config->titleshort == 0 ? 4000: $this->config->titleshort;
                if( StrLenW( $value['content'] ) > $short ){
                    $value['content'] = getBlogShort( $value['content'], $short ).$this->config->suffix;
                }
            }
            return $result;
        }
    }
