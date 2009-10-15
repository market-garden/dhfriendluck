<?php
    /**
     * CommentModel 
     * 评论愿望的model
     * @uses BaseModel
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class CommentModel extends BaseModel{

        public function getAllComment( $uid ){
            $map['uid'] = $uid;
            $result = $this->where( $map )->findPage( 20 );
            return $result;
        }
        public function addComment( $data ){
            foreach( $data as $key => $value ){
                    $map[$key] = $value;
            }

            if( $add = $this->add($map) ){
                $map2['appid'] = $map['appid'] ;
                $map2['type']  = $map['type'];

                $result['count'] = $this->where($map2)->count();
                $result['id']    = $add;
                return $result;
            }else{
                return false;
            }
        }

        public function deleteComment( $data ){
            //排除空条件清空整个表
            if( empty( $data ) ){
                throw new ThinkException( "删除数据必须有条件" );
            }

            return $this->where($data)->delete();
        }

        public function getComment( $type,$wishId,$odd = false, $count = null,$time=null ){
            $map['type']  = $type;
            $map['appid'] = $wishId;
            //根据参数决定返回什么样的评论集合
            if( true == $odd ){
                //如果设置了时间。。
                if( isset( $time ) )
                    $map['cTime'] = array( "lt",$time );
                $result = $this->where( $map )->findAll();
            }else{
                $result['first'] = $this->where($map)->find();

                if( $count>1 ){
                    $result['last'] = $this->where( $map )->order( 'cTime desc' )->find();
                    $result['count']=$count;
                }

                $result['id'] = $wishId;
            }
            return $result;
        }
    }
