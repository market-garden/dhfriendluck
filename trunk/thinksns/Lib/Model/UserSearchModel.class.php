<?php

class UserSearchModel extends Model {

	private $uid         = 0;
	private $mid         = 0;
	private $home        = 0;

	public function setUid($uid) {
		$this->uid = $uid;
	}

	public function getInfo($mustConfig=false,$map=null,$order=null) {
		if( false == $mustConfig ) {
			$config  = ts_cache( $this->uid.'_info' );
			if( !$config ) {
				$config = $this->getInfoData( $map,$mustConfig,$order );
			}
		}else {
			$config	= $this->getInfoData($map,$mustConfig,$order);
		}
		return $config;
	}

	/**
	 * getConfigData
	 * 获得数据库中的配置信息
	 * @access public
	 * @return array
	 */
	public function getInfoData($map = null,$cache = false,$order= null) {
		$map['uid'] = $this->uid;
		//查询所有配置
		$request = $this->where($map)->order( $order )->findAll();
		//没有就返回
		if( empty( $request ) ) return false;
		//组装成标准数组
		$result = array();
		$temp_array = array( 'ts_areaval','ts_hometown' );
		foreach ( $request as $key=>$value ) {
			if( in_array( $value['name'],$temp_array ) ) {
				$result[$value['name']][] = is_array( $value['value'] )?unserialize( $value['value'] ):$value['value'];
				$result[$value['name']][] = $value['privacy'];
				$result[$value['name']][] = $value['home'];
				$result[$value['name']][] = $value['id'];
				isset( $value['extra1'] ) && $result[$value['name']][] = $value['extra1'];
				isset( $value['extra2'] ) && $result[$value['name']][]= $value['extra2'];
				isset( $value['extra3'] ) && $result[$value['name']][] = $value['extra3'];
				isset( $value['extra4'] ) && $result[$value['name']][] = $value['extra4'];
				isset( $value['extra5'] ) && $result[$value['name']][] = $value['extra5'];
			}else {
				$result[$value['name']] = array($value['value'],$value['privacy'],$value['home']);
			}
		}

		//重建缓存
		$cache && $this->rebuildCache();

		return $result;
	}

	/**
	 * addConfig
	 * 添加配置
	 * @param mixed $data
	 * @access public
	 * @return void
	 */
	public function addInfo($data) {
	//处理额外的数据集合
		foreach ( $data as $key => $value ) {
			$temp_value = is_array( $value[2] ) ? serialize( $value[2] ):$value[2];
			$map['name']    = $key;
			$map['value']   = $temp_value;
			$map['uid']     = $this->uid;
			$map['privacy'] = $value[0];
			$map['home']    = $value[1];
			//如果有额外的值
			$temp_value = array_values($value[3] );
			isset( $value[3] ) && list( $map['extra1'],$map['extra2'],$map['extra3'],$map['extra4'],$map['extra5'] ) = $temp_value;
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
	public function editInfo($data) {
		$cache = true; //修改配置是需要刷新缓存的
		if( !is_array( $data ) ) {
			throw new ThinkException( "参数必须是数组" );
		}
		$config = $this->where("uid=$this->uid")->getFields( 'name' ) ;
		//循环数组。如果有这个字段，则是修改。如果没有这个字段，添加新的配置
		foreach( $data as $key => $value ) {
			$addConfig = array();  //添加配置的条件数组

			//如果没有这个字段，添加配置
			if( false == in_array($key,$config) || is_null( $config ) ) {
				$addConfig[$key]=$value;
				if($this->addInfo( $addConfig )) continue;
			}

			//修改条件
			$condition['name']		=	$key;
			$condition['uid']  =	$this->uid;

			//修改的值
			$map['privacy'] = $value[0];
			$map['home']  = $value[1];
			$map['value'] = is_array( $value[2] )?serialize( $value[2] ):$value[2];
			isset( $value[3] ) && list( $map['extra1'],$map['extra2'],$map['extra3'],$map['extra4'],$map['extra5'] ) = $value[3];
			$result = $this->where( $condition )->save($map);
		}

		//重建缓存 TODO 需要可配置
		$this->rebuildCache();
		return true;
	}

	//重建缓存
	private function rebuildCache() {
		ts_cache( $this->uid.'_info',"" );

		$request = $this->where("uid='$this->uid'")->findAll();
		if( !$request ) {
			return false;
		}

		foreach ( $request as $value ) {
			$result[$value['name']] = $value['value'];
		}

		ts_cache( $this->uid.'_info',$result );
		return true;
	}
	public function getHomeInfo( $mid,$uid ) {
		$this->home = 1;
		return $this->getPersonalIntro( $mid,$uid);
	}

	public function getGroupInfo( $mid,$uid=null ) {
		$this->home = 0;
		$data = $this->getPersonalIntro( $mid,isset( $uid )?$uid:$mid );
		$userInfo = $data?$data:array();
		$links    = array("地址","邮编","电话","手机","QQ","MSN");
		$work_key = array( '工作信息' );
		$edus_key = array( '教育信息' );
		$geren    = array( "我想结交","兴趣爱好","喜欢的书","偶像","座右铭","最近心愿","我的简介","喜欢的电影" );
		$zh       = array( '性别','生日','血型','家乡','居住地区' );
		foreach ( $userInfo as $key=>$value ) {
			if( in_array( $key,$links ) ) {
				$result["userInfo_links"][$key]=$value;
				continue;
			}
			if( in_array( $key,$geren ) ) {
				$result["userInfo_geren"][$key]=$value;
				continue;
			}
			if( in_array( $key,$work_key ) ) {
				$result["works"]=$value;
				continue;
			}
			if( in_array( $key,$edus_key ) ) {
				$result["edus"]=$value;
				continue;
			}
			if( in_array( $key,$zh ) ) {
				$result["userInfo_zh"][$key]=$value;
				continue;
			}
		}
		return $result;
	}


	public function getPersonalIntro( $mid,$uid,$field = null ) {
	//TODO 换成后台配置的信息
		$item["address"]       = "地址";
		$item["postcode"]      = "邮编";
		$item["phone"]         = "电话";
		$item["cellphone"]     = "手机";
		$item["qq"]            = "QQ";
		$item["msn"]           = "MSN";
		$item["birthday"]      = "生日";
		$item["jiejiao"]       = "我想结交";
		$item["interest"]      = "兴趣爱好";
		$item["book"]          = "喜欢的书";
		$item["film"]          = "喜欢的电影";
		$item["idol"]          = "偶像";
		$item["motto"]         = "座右铭";
		$item["wish"]          = "最近心愿";
		$item["summary"]       = "我的简介";
		$item["education"]     = "教育信息";
		$item["career"]        = "工作信息";
		$item["ts_areaval"]    = "居住地区";
		$item["ts_hometown"]   = "家乡";
		$item["sex"]           = "性别";
		$item["bloodtype"]     = "血型";
		$item["birthday_stro"] = "星座";

		$result = array();


		$request = $this->__getData( $mid,$uid );
		if( isset( $field ) ) {
			$intro = unserialize( $request[$field] );
			//过滤隐私
			$temp_result = array_filter( $intro,array( $this,'filterPrivacy' ) );
			//重组数据
			foreach ( $temp_result as $key=>$value) {
				$result[$item[$key]] = $value[2];
			}
		}else {
			$temp_field  = array( 'intro','contact');
			$temp_field2 = array('education','career' );

			foreach ( $temp_field as $value ) {
				$info = unserialize( $request[$value] );
				$temp_result = array_filter( $info,array( $this,'filterPrivacy' ) );

				//个人情况中的更多
				if( 'intro' == $value ) {
					if( isset( $temp_result['more']) ) {
						$temp = $temp_result['more'];
						unset( $temp_result['more'] );
						$more = unserialize( $temp[2] );
						$temp_more = array_filter( $more,array( $this,'filterPrivacy' ) );
						foreach ( $temp_more as $key=>$v ) {
							$result[$v['name']] = $v['value'];
						}
					}
				}

				//重组数据
				foreach ( $temp_result as $key=>$v) {
					$result[$item[$key]] = $v[2];
				}


			}

			foreach ( $temp_field2 as $value ) {
				$info = unserialize( $request[$value] );
				$temp_result2[$value] = array_filter( $info,array( $this,'filterPrivacy' ) );
				if( "career" == $value ) {
					foreach ( $temp_result2[$value] as $key=>$v ) {
						$result[$item[$value]][] = sprintf( '%s %s %s-%s',$v['company'],$v['position'],date( 'Y年m月',$v['begin'] ),date( 'Y年m月',0==$v['end']?time():$v['end'] ) );
					}
				}else {
					foreach ( $temp_result2[$value] as $key=>$v ) {
						$result[$item[$value]][] = sprintf( '%s %s %s年入学',$v['school'],$v['class'],$v['year'] );
					}

				}

			}

			//个人信息
			$info = unserialize( $request['info'] );
			$temp_result3 = array_filter( $info,array( $this,'filterPrivacy' ) );

			$areaval  = $this->__paramAddress( $temp_result3['ts_areaval'][2] );
			$hometown = $this->__paramAddress( $temp_result3['ts_hometown'][2] );
			isset( $areaval ) && $temp_result3['ts_areaval'][2] = $areaval;
			isset( $hometown ) && $temp_result3['ts_hometown'][2] = $hometown;


			foreach( $temp_result3 as $key=>$v ) {
				$result[$item[$key]] = $v[2];
			}

		}
		return $result;
	}
	private function __getData( $mid,$uid ) {
		$this->uid = $uid;
		$this->mid = $mid;
		$map['uid']  = $uid;
		$request = D( 'UserInfo' )->where( $map )->find();
		return $request;
	}

	public function filterPrivacy($value) {
		$api = new TS_API();
		//基本信息过滤
		if( isset( $value['privacy'] ) ) {
			$privacy = $value['privacy'];
			$home    = isset( $value['display'] )?$value['display']:$value['home'];
		}else {
			$privacy = $value[0];
			$home    = $value[1];
		}
		if( $this->uid != $this->mid && (1 == $privacy && false == $api->friend_areFriends( $this->uid,$this->mid )) ) return false;
		if( 1 == $this->home && 1 != $home )  return false;
		if( $this->uid != $this->mid && $privacy == 2 ) return false;

		return true;
	}

	private function __paramAddress( $param ) {
		$result = $input =  $param;
		if( isset( $input ) ) {
			list( $province,$city ) = explode( ',',$input );
			isset( $input ) && $result = getAreaInfo( $province)." ".getAreaInfo($city);
		}
		return $result;
	}


}
?>
