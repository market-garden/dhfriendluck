<?php
class SearchModel extends Model{
	public $tableName	=	'user_search';
    private $uid         = 0;

    public function setUid($uid){
        $this->uid = $uid;
    }

	public function getInfo($mustConfig=false){
        if( false == $mustConfig ) {
		   $config  = ts_cache( $this->uid.'_info' );
		}else{
		   $config	= $this->getInfoData(true);
		}

        return $config;
	}

	/**
	 * getConfigData
	 * 获得数据库中的配置信息
	 * @access public
	 * @return array
	 */
	public function getInfoData($cache = false){

		//查询所有配置
		$request = $this->where("uid=$this->uid")->findAll();
        //没有就返回
        if( empty( $result ) ) return false;
		//组装成标准数组
		foreach ( $request as $value ){
            if( false !== strpos( $value['name'],'other' ) ){
                $temp_other = unserialize( $value['value'] );
                $result[$value['name']][$temp_other[0]] = $temp_other[1];
            }else{
			    $result[$value['name']] = $value['value'];
            }
		}

		//重建缓存
		$this->rebuildCache();

		return $result;
	}

	/**
	 * addConfig
	 * 添加配置
	 * @param mixed $data
	 * @access public
	 * @return void
	 */
	public function addInfo($data){
		foreach ( $data as $key => $value ){
			$value = is_array( $value ) ? serialize( $value ):$value;
			$map['name']  = $key;
			$map['value'] = $value;
			$map['uid']   = $this->uid;
			$result = $this->add($map);
		}
		return $result;
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
		$config = $this->where("uid=$this->uid")->getFields( 'name' ) ;
		//循环数组。如果有这个字段，则是修改。如果没有这个字段，添加新的配置
		foreach( $data as $key => $value ){
			$addConfig = array();  //添加配置的条件数组

			//如果没有这个字段，添加配置
			if( false == in_array($key,$config) || is_null( $config ) ){
				$addConfig[$key]=$value;
				if($this->addInfo( $addConfig )) continue;
			}

			//修改条件
			$condition['name']		=	$key;
			$condition['uid']  =	$this->uid;

			//数组需要被序列化存储
			if( is_array( $value ) ){
				$value = serialize( $value );
			}

			//修改的值
			$map['value'] = $value;
			$result = $this->where( $condition )->save($map);
		}

		//重建缓存
		$this->rebuildCache();
		return true;
	}

	//重建缓存
	private function rebuildCache(){
		ts_cache( $this->uid.'_info',"" );

		$request = $this->where("uid='$this->uid'")->findAll();
		if( !$request ){
			return false;
		}

		foreach ( $request as $value ){
		   $result[$value['name']] = $value['value'];
		}

		ts_cache( $this->uid.'_info',$result );
		return true;
	}

}
