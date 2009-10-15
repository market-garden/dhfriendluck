<?php
/**
 * array_my_diff 
 * 把$array中的$arr的key去掉
 * @param mixed $arr 
 * @param mixed $array 
 * @access public
 * @return void
 */
function array_my_diff( $arr,$array ){
    $result = array(  );
    foreach ( $arr as $value ){
        if( is_array( $value ) ){
            $array_my_diff( $arr,$value );
        }
        $temp_array = array_diff_key($value, array_flip($array));
        $result[]=array_diff_key($value,$temp_array);
    
    }
    return $result;
}

    /**
     * mergeArray 
     * 迭代合并数组
     * <code>
     * $a = array( "a"=>"123","b"=>"321", "c"=>array( "ddd"=>"d","ccc"=>"d" ) );
     * $b = array( "c"=>array( "123"=>"123123123","333"=>"dsfsdfsdf" ),"d"=>"444" );
     * var_dump( mergeOptions( $a,$b ) );
     * </code>
     * @param array $array1 
     * @param mixed $array2 
     * @access public
     * @return void
     */
    function mergeArray(array $array1,$array2 = null  ){
        if ( is_array( $array1 ) ){
            foreach( $array2 as $key => $val ){
                if (is_array( $val )) {
                    $array1[$key] = ( array_key_exists( $key,$array1 ) && is_array( $array1[$key] ) )? mergeArray( $array1[$key],$val ) : $array2[$key] ;
                }else{
                    $array1[$key] = $val;
                }
            }
        }
        return $array1;
    }

/**
 * fb 
 * SamPeng封装的FirPHP调试单个变量函数
 * @param mixed $var 
 * @access public
 * @return void
 */
function fd( $var,$null = true ,$filter = null,$res = null,$name = null){

        Import( "@.Unit.FirePHPCore.FirePHP" );

        $firephp = FirePHP::getInstance(true);
    //设置过滤器
    if ( isset ( $filter ) ){
        $filter = is_array( $filter ) ? $filter : array( $filter );
        $temp1 = ( array ) $var;
        $temp2 = array_keys( $temp1 );
        $filt = array_diff( $temp2,$filter );
        $firephp->setObjectFilter('stdClass',$filt); 

    }

    if ( $null == true  && (!isset($var) || empty($var) )) {
            $firephp->warn($var,$name."是空值");
            return $firephp;
        }
    $type = gettype( $var );
    $firephp->info($var,$name."[".$type."]");
    return $firephp;
}
/**
 * fs 
 * SamPeng封装的FirePHP得到最后一个sql语句
 * @param mixed $sql 
 * @access public
 * @return void
 */
function fs( $dao ){
    //require_once( "FirePHPCore/FirePHP.class.php" );

        Import( "@.Unit.FirePHPCore.FirePHP" );
    $firephp = FirePHP::getInstance(true);

    if (is_instance_of ($dao,"Model") ){
        $firephp->log($dao->getLastSql());
    }else{
        $firephp->error( "并不是一个Model的方法" );
    }
}

/**
 * fda 
 * SamPeng封装的FirePHP批量调试
 * @access public
 * @return void
 */
function fda( $var_array,$null = true,$filter = null,$all = false){
    Import( "@.Unit.FirePHPCore.FirePHP" );
    $firephp = FirePHP::getInstance(true);
    if ( is_array( $var_array ) ){
        foreach( $var_array as $key=>$value ){
            if ( is_string( $key ) ){
                !is_object( $value ) && $filter == null;
                fd( $value,$null,$filter,$firephp,$key);
            }
        }
        if( $all == true ){
            $firephp->logo( debug_backtrace() );
        }
        $firephp->trace("Trace Label");
        $firephp->group( "全部系统变量" );
        $firephp->fb( $_SERVER,"SERVER变量" );
        $firephp->groupEnd();

        $firephp->group( "全部Session" );
        $firephp->fb( $_SESSION,"SESSION变量" );
        $firephp->groupEnd();
        $firephp->group( "全部请求变量" );
        isset( $_GET ) && $firephp->fb( $_GET,"GET传值" );
        isset( $_POST ) && $firephp->fb( $_POST,"POST传值" );
        isset( $_REQUEST ) && $firephp->fb( $_REQUEST,"REQUEST请求" );
        $firephp->groupEnd(  );
        return;
    }
    fd( $value,$null,$filter,$firephp );
        $firephp->group( "全部系统变量" );
        $firephp->fb( $_SERVER,"SERVER变量" );
        $firephp->groupEnd();

        $firephp->group( "全部Session" );
        $firephp->fb( $_SESSION,"SESSION变量" );
        $firephp->groupEnd();
        $firephp->group( "全部请求变量" );
        isset( $_GET ) && $firephp->fb( $_GET,"GET传值" );
        isset( $_POST ) && $firephp->fb( $_POST,"POST传值" );
        isset( $_REQUEST ) && $firephp->fb( $_REQUEST,"REQUEST请求" );
        $firephp->groupEnd(  );
    if( $all == true ){
        $firephp->log( debug_backtrace() );
    }
    $firephp->trace("Trace Label");
}


/**
 * getBlogShort 
 * 截取blog的长度
 * @param mixed $content 
 * @param mixed $length 
 * @access public
 * @return void
 */
function getBlogShort($content,$length = 60) {
	$content	=	stripslashes($content);
	$content	=	strip_tags($content);
	$content	=	getShort($content,$length);
	return $content;
}

//根据存储路径，获取照片真实URL
function get_photo_url($savepath) {
	$path	=	str_ireplace(UPLOAD_PATH,'',$savepath);
	$path	=	UPLOAD_URL.$path;
	return $path;
}
