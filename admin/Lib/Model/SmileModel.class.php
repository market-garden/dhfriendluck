<?php
    /**
     * SmileModel
     * 表情数据库
     * @uses BaseModel
     * @package
     * @version $id$
     * @copyright 2009-2011 SamPeng
     * @author SamPeng <sampeng87@gmail.com>
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class SmileModel extends BaseModel{
        private static $olddata=array();

        /**
         * getSmileList
         * 获取数据库中的表情列表
         * @param mixed $appname
         * @access public
         * @return void
         */
        public function getSmileList( $type ){
            $map['type'] = $type;
            return $this->where($map)->findAll();
        }

        public function getSmileType(){
            $result = $this->field( 'distinct(type)' )->findAll();
            return $result;
        }

        public function addSmile($data,$cache=null){
            //重组数据
            $map['type'] = $data['type'];
            unset( $data['type'] );
            if( is_array( $data{1} ) ){
                foreach( $data as $value ){
                    $addMap['title']    = $value['title'];
                    $addMap['emotion']  = $value['emotion'];
                    $addMap['filename'] = $value['filename'];
                    $addMap['type']     = $map['type'];
                    $result = $this->add($addMap);
                }
            }else{

                //检查合法性
                $map['filename'] = $data['filename'];
                if( $result = $this->where( $map )->find() ){
                    return "has_file";
                }

                if( !is_file(PUBLIC_PATH."/images/biaoqing/".$map['type']."/".$data['filename']) )
                    return false;
                $data['type']=$map['type'];
                $result = $this->add($data);
            }
            unset( $data );
            //缓存起来
            if( $result && isset( $cache ) ){
               $data = $this->where("type='".$map['type']."'")->findAll();
               $result = $this->setCache( $data,$map['type'] );
               return true;
            }
            return true;

        }

         /**
         * getSmile
         * 获得smail数组
         * @param mixed $appname
         * @access public
         * @return void
         */
        public function getSmile( $type ){
            $smile = ts_cache( "smile_".$type );
            if( $smile ){
                return $smile;
            }else{
               $data = $this->where()->findAll();
               if ($data) {
               	return $this->setCache($data);
               }else{
               	return addSmail(C($type.'ico'));
               }
            }
        }

        public function deleteSmile( $id,$type ){
               $data = $this->where()->findAll();
               $this->setCache( $data,$type );
            $smile = ts_cache( "smile_".$type );
            $map['id'] = array( 'in',$id );
            $map   = $this->merge( $map );
            if( empty( $map ) ){
                return false;
            }
            if ( $result = $this->where( $map )->delete()){
                foreach( $smile as $key=>&$value ){
                    if( is_array( $id ) ){

                        if( in_array( $value['id'],$id ) )
                            unset( $smile[$key] );

                    }else{
                        if( $id == $value['id'] )
                            unset( $smile[$key] );
                    }
                    $value['type'] = $type;
                }
                return $this->setCache( $smile);
            }

        }

        public function editSmile( $emotion,$title,$type ){
            if( empty( $type ) ){
                return false;
            }
            $id = array_keys( $emotion );
            $setvalue = "";
            for ($i = 0; $i < count( $emotion ); $i++) {
                $temp_id = $id[$i];
                $temp_emotion = $emotion[$temp_id];
                $temp_title = $title[$temp_id];
                $setvalue .= " WHEN id = {$temp_id} THEN '{$temp_emotion}' ";
                $settitle .= " WHEN id = {$temp_id} THEN '{$temp_title}' ";

            }
            $sql = "UPDATE {$this->tablePrefix}smile
                    SET emotion = ( CASE {$setvalue} END),
                        title =   ( CASE {$settitle} END )
                    WHERE type = '{$type}'
                ";
            if( $reust = $this->execute( $sql )){
                $map['type'] = $type;
                $data = $this->where( $map )->findAll();
                return $this->setCache( $data,$type );
            }
        }

        /**

         * getSmileFileList
         * 获得表情文件列表
         * @param mixed $appname
         * @access public
         * @return void
         */
        public function getSmileFileList($appname){
            $icopath =  PUBLIC_PATH."/images/biaoqing/".$appname;
            if( !is_dir( $icopath ) )
                return false;
            return $this->traversalDir( $icopath );
        }

        public function ChangeEm($map,$options){
            $map = $this->merge( $map );
            if( empty($map) ){
                throw new ThinkException( "不允许空条件修改该" );
            }
            return $this->where( $map )->field( $options )->save();
        }

        /**
         * filterData
         * 过滤并添加新的表情
         * @param mixed $oldData
         * @param mixed $newData
         * @param mixed $type
         * @access public
         * @return void
         */
        public function filterData( $oldData,$newData,$type ){
            self::$olddata   = $oldData;
            $newData         = array_filter( $newData,array( $this,'filter' ) );
            $newData['type'] = $type;

            if( isset( $newData{1}) ){
                $result = $this->addSmile( $newData );
            }
            return $this->getSmileList( $type );
        }

        public function filter( $value,$key ){
            return !$this->_checkInOld( $value['filename'] );
        }
        /**
         * traversalDir
         * 遍历目录获得表情.能迭代目录
         * @param mixed $path 目录
         * @access private
         * @return void
         */
        private function traversalDir ( $path ){
            $result = array();
            $file   = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(($path)));
            $i = 0 ;
            foreach ( $file as $key=>$value ) {
                //排除.svn目录的文件
                if( !strpos( $value->getPathname(),".svn" ) && strpos( $value->getFilename(),".gif" ) ){
                    $result[$i]['title'] = '表情'.$i;
                    $result[$i]['filename'] = $value->getFilename();
                    $result[$i]['emotion'] = '';
                    $i++;
                }
            }
            return $result ;
        }

        /**
         * _checkInOld
         * 检查是否在旧的数据中存在
         * @param mixed $filename if(
         * @access private
         * @return void
         */
        private function _checkInOld( $filename ){
            foreach( self::$olddata as $value ){
                if( $filename == $value['filename'] )
                    return true;
            }
            return false;
        }
        private function setCache( $data,$type ){
             $cache = array();
               foreach( $data as $value ){
                   $temp_type[] = $value['type'];
                    $cache[$value['type']][$value['id']]['id'] = $value['id'];
                    $cache[$value['type']][$value['id']]['title'] = $value['title'];
                    $cache[$value['type']][$value['id']]['emotion'] = $value['emotion'];
                    $cache[$value['type']][$value['id']]['filename'] = $value['filename'];
               }
             foreach ( $temp_type as $value ){
                $result = ts_cache( 'smile_'.$value,$cache[$value]);
             }
             if( $result  ){
                 return $cache[$type];
             }
             return true;
        }
    }
