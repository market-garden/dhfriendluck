    <?php
    /**
     * getIsVote 
     * 返回是否已经投票
     * @param mixed $vid 
     * @param mixed $mid 
     * @access public
     * @return void
     */
	function getIsVote($vid,$mid){
		//2009-4-2 增加空值判断
		if(!$vid || !$mid) return false;

         $voteUserDao = D("VoteUser");
         $vote_id = intval($vid);
         $count = $voteUserDao->count("vote_id='$vote_id' AND user_id='$mid'");
         if($count>0){
             return "，你已经投过票了！";
         }else{
			 return "";
		 }
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

	