<?php
    /**
     * BlogCategoryModel 
     * 日志分类model
     * @uses Model
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class FriendGroupModel extends BaseModel{

        /**
         * getCategory 
         * 获取所有分类
         * @access public
         * @return void
         */
        public function getCategory(  ){
             $result = $this->where('uid=0')->field( 'name,uid,id' )->findAll();

                //重组数据集结构
                $newresult = array();
                foreach ( $result as $value ){
                    $newresult[$value['id']]['name'] = $value['name'];
                    $newresult[$value['id']]['uid']  = $value['uid'];
                }

                //dump( $newresult );
                //ts_cache( 'blog_category',$newresult );
                return $newresult;
        }

        /**
         * addCategory 
         * 增加分类
         * @param mixed $map 
         * @access public
         * @return void
         */
        public function addCategory( $map ){
            $map = $this->merge( $map );
            return $this->add( $map );
        }

        /**
         * deleteCategory 
         * 删除分类
         * @param mixed $map 
         * @access public
         * @return void
         */
        public function deleteCategory( $map ){
            //先判断合法性
            if( empty( $map ) )
                throw new ThinkException( "不能是空条件删除" );
            //转移被删分类下的日志到默认分类
            //删除分类下的所有日志
            //  $this->where( 'category = '.$map['id'] )->delete();
            $map2['gid'] = $map['id'];
            $condition['gid'] = $map['id'];
            $map2['gid'] = 1;
            D( 'Fg' )->where( $condition )->save( $map2 );

            //删除分类
            return $this->where( $map )->delete();
        }

        /**
         * editCategory 
         * 编辑分类
         * @param mixed $map 
         * @access public
         * @return void
         */
        public function editCategory( $data ){
            foreach( $data as $key=>$value ){
                $map1[] = "`id` = $key";
                $map2[] = "WHEN `id` = $key THEN '$value'";
            }
            $case = implode( ' ', $map2 );
            $where = implode( ' or ',$map1 );


            $sql = "UPDATE `ts_friend_group`
                    SET `name` = (case {$case} end)
                    WHERE {$where} ";
            $query = $this->execute( $sql );
            return $query;

        }

        /**
         * getCategoryName 
         * 通过id获得名字
         * @param mixed $id 
         * @access public
         * @return void
         */
        public function getCategoryName( $id ){
            $map['id'] = $id;
            $result = $this->where( $map )->field('name')->find();
            return $result['name'];
        }


        /**
         * getUserCategory 
         * 获得用户的分类
         * @param mixed $uid 
         * @access public
         * @return void
         */
        public function getUserCategory( $uid ){
            $map['uid'] = array( 'in',"$uid,0" );
            $result     = $this->where( $map )->field( 'name,id,uid' )->findAll();
            return $result;
        }

    } 
