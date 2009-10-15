<?php
    class MiniConfigModel extends Model{
        /**
         * getConfig 
         * 获得配置
         * @access public
         * @return void
         */
        public function getConfig($appname){
            $cache = ts_cache( $appname.'config' );
            if( $cache ){
                return $cache;
            }else{
                return $this->getConfigData(true);
            }
        }

        /**
         * getConfigData 
         * 获得数据库中的配置信息
         * @access public
         * @return void
         */
        public function getConfigData($cache = false){
                //查询所有配置
                $request = $this->findAll();

                //组装成标准数组
                foreach ( $request as $value ){
                    $result[$value['variable']] = $value['value'];
                }
                //缓存
                if($result && $cache && $this->setCache() )
                    return $result ;

                if( false == $cache ){
                    return $result;
                }
                return false;
        }

        /**
         * addConfig 
         * 添加配置
         * @param mixed $data 
         * @access public
         * @return void
         */
        public function addConfig($data){

            foreach ( $data as $key => $value ){
                $value = is_array( $value ) ? serialize( $value ):$value;
                $this->variable = $key;
                $this->value    = $value;
                $result = $this->add();
            }

            //缓存
            if( $result ) 
                if( $this->setCache()){
                    return true;
                }

            return false;
        }

        /**
         * editConfig 
         * 编辑配置
         * @param mixed $data 
         * @access public
         * @return void
         */
        public function editConfig($data){
            $cache = true; //修改配置是需要刷新缓存的
            if( !is_array( $data ) ){
                throw new ThinkException( "参数必须是数组" );
            }
            $config = $this->getFields( 'variable' ) ;
            //循环数组。如果有这个字段，则是修改。如果没有这个字段，添加新的配置
            foreach( $data as $key => $value ){
                $addConfig = array();  //添加配置的条件数组

                //如果没有这个字段，添加配置
                if( false == in_array($key,$config) || is_null( $config ) ){
                    $addConfig[$key]=$value;
                    if($this->addConfig( $addConfig )){
                        $cache = false;
                        continue;
                    }
                }
                
                //修改条件
                $condition['variable'] = $key;

                //数组需要被序列化存储
                if( is_array( $value ) ){
                    $value = serialize( $value );
                }

                //修改的值
                $map['value'] = $value;
                $result = $this->where( $condition )->save($map);
            }

            //如果配置成功，并且成功缓存成功
            if( $result && $cache && $this->setCache())
                    return true;

            //如果成功修改并且缓存为否；
            if( $result || !$cache )
                return true;

            return false;
        }

        private function setCache(){
            ts_cache( 'miniconfig',"" );
            $request = $this->findAll();
            if( !$request ){
                return false;
            }
            foreach ( $request as $value ){
               $result[$value['variable']] = $value['value'];
            }
            ts_cache( 'miniconfig',$result );
            return true;
        }

}
