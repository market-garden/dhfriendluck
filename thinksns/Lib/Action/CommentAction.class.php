<?php
    /**
     * CommentAction 
     * 评论Action
     * @uses BaseAction
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class CommentAction extends BaseAction{
        /**
         * getComment 
         * 获取评论数据
         * @access public
         * @return void
         */
        public function getComment(){
            $page         = 100;
            if( $this->uid == intval($_POST['mid'])){
                $map['type']  = t($_POST['type']);
                $map['appid'] = intval($_POST['id']);
                $map['toId']  = 0;
            }else{
                $id = intval($_POST['id']);
                $type = t($_POST['type']);
                $map = "(uid = {$this->mid} OR (quietly <> 1 AND uid <>{$this->mid}))  AND toId = 0 AND appid = {$id} AND type = '{$type}'";
            }
            $comment = $this->getComments( $map,"cTime DESC",$page,'findPage' );
            foreach( $comment['data'] as $key=>&$value ){

                unset( $map );
                //子回复
                $map['toId']  = intval($value['id']);
                $map['type']  = t($_POST['type']);
                $map['appid'] = intval($_POST['id']);

                if( $subcomment = $this->getComments( $map,"id ASC",null,"findAll" ) ){
                    $value['subcomment'] = $subcomment;
                    unset( $value['subcomment']['data'] );
                    $value['isDelete'] = false;
                }
            }
            //$comment['prepage'] = $page;
            $this->assign( $comment );
            $this->display();
        }
            

        public function getCount(){
            $map['type'] = t($_POST['type']);
            $map['appid'] = intval($_POST['id']);
            $map['toId'] = 0;
            if( $count =D( 'Comment' )->where( $map )->count()  ){
                echo intval( $count );
            }else{
                echo -1;
            }
        }

        public function doAddComment(){
            $com = D( 'Comment' );
            $map['comment'] = trim(nl2br($_POST['comment']));
            $map['type']    = t($_POST['type']);
            $map['appid']   = intval($_POST['appid']);
            $map['cTime']   = time();
            $map['name']    = $this->my_name;
            $map['status']  = 0;
            $map['uid']     = $this->mid;
            $map['toId']    = intval($_POST['toId']);
            $map['quietly'] = $_POST['quietly'];

            $result = $map;
            $appid = $map['appid'];
            $addId  = $com->add($map);
            if( $addId ){
				//TODO 数据库评论数计算数
                $result['cTime']    = "刚刚";
                $result['id']       = $addId;
                $result['face']     = getUserFace( $result['uid'] );
                $result['comment']  = $this->replaceContent( $result['comment'],'mini' );//TODO 表情多应用化
                $result['isDelete'] = true;
                $result['toUid']    = $com->where('id='.$map['toId'])->getField('uid');
                echo json_encode( $result );
            }else{
                echo -1;
            }
        }

        /**
         * replaceContent 
         * 替换内容
         * @param mixed $content 
         * @access private
         * @return void
         */
        private function replaceContent( $content,$type ){
            //TODO 每一个应用可以应用一套表情
            $path = __PUBLIC__."/images/biaoqing/mini/";//路径
            //TODO 多应用表情
            $smile = ts_cache( "smile_mini" );
            //循环替换掉文本中所有ubb表情
            foreach( $smile as $value ){
                $img = sprintf("<img title='%s' src='%s%s'>",$value['title'],$path,$value['filename']);
                $content = str_replace( $value['emotion'],$img,$content );
            }
            $conten = nl2br($content);
            return $content;
        }

        public function doDeleteComment(  ){
            $id = intval($_POST['id']);

            $appid = intval($_POST['appid']);
            if( empty( $id ) || empty( $appid ) ){
                echo -1;
                exit;
            }
            $map['id']     = $id;
            $comment       = D( 'Comment' );
            $deleteComment = $comment->where( $map )->delete();
            //修改日志的评论数
            if( $deleteComment ){
                echo 1;
            }else{
                echo -1;
            }
            //热门度不需修改
        }

        /**
         * getComments 
         * 获取评论
         * @param mixed $map 
         * @access private
         * @return void
         */
        private function getComments( $map,$order,$page,$method ){
            $com     = D( 'Comment' );
            $comment = $com->getComment($map,$order,$page,$method);
            if( !$comment ){
                return false;
            }
            if( 'findAll' == $method ){
                $comment = $this->replace( $comment );
            }else{
                $comment['data'] = $this->replace($comment['data']);
            }
            return $comment;
        }
        
        private function replace( $data ){
            foreach( $data as $key=>&$value ){

                $value['face']       = getUserFace( $value['uid'] );
                $value['cTime']      = friendlyDate( $value['cTime'] );
                $value['comment']    = $this->replaceContent( $value['comment'],'mini' );//TODO 表情多应用化
                $value['isDelete']   = (( $this->mid == $value['uid'] )|| $this->mid == $_POST['mid'])?true:false;

                //悄悄话
            }
            return $data;
        }

//        /**
//         * doNotify 
//         * 发出通知
//         * @param mixed $type 
//         * @access private
//         * @return void
//         */
//        private function doNotify($type,$appid,$addId,$comment,$url,$toId = null,$filed = null){
//            $dao = D( 'Comment' ); //评论模块对象只是用来执行SQL语句，非真实模块对象
//            $type = strtolower( $type );
//            //获取被回复人的信息
//
//            $tablePrefix = $dao->gettablePrefix();
//            if( isset( $toId ) && !empty( $toId )){
//                $sql        = "SELECT `uid`
//                               FROM {$tablePrefix}comment
//                               WHERE `id` = {$toId}
//                               " ;
//                $data2  = $dao->query( $sql );
//                $toUid = $data2[0]['uid'];
//            }
//
//            $table = $tablePrefix.$type;
//            $filed = (isset($filed) && !empty($filed) )?$filed:'uid';
//            //获取回复的日志信息
//            $sql        = "SELECT `".$filed."`,`id`,`title`
//                           FROM {$table}
//                           WHERE `id` = {$appid}
//                           " ;
//            $data       = $dao->query( $sql );
//            $title_data['title'] = "<a href=".$url.">".$data[0]['title']."</a>";
//            $title_body['comment'] = $comment;
//            $uids       = $data[0][$filed];
//            $title_data['type']  = $type;
//
//            //发送两条消息
//            switch ( true ){
//                case ( $uids == $toUid ):  //发布者的回复的回复
//                    $this->api->notify_send( $toUid,'comment_comment',$title_data,$title_body,$url );
//                    break;
//                case ( $uids <> $toUid ) && !empty( $toUid ):  //回复的回复
//                    $this->api->notify_send( $toUid,'comment_comment',$title_data,$title_body,$url );
//                    $this->api->notify_send( $uids,$type.'_comment',$title_data,$title_body,$url ) ;
//                    break;
//                default://评论
//                    $this->api->notify_send( $uids,$type.'_comment',$title_data,$title_body,$url );
//            }
//            return true;
//        }
        }
