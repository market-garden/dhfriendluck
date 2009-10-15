<?php
/**
 * 获得sohu邮箱通讯录列表 -- contactssohu.class.php
 */

class contactssohu extends contacts
{

	function checklogin( $user, $password )
	{
		$ch = curl_init( );
		$url = "http://passport.sohu.com/sso/login.jsp";
		$url = $url."?userid=".urlencode( $user );
		$url = $url."&password=".md5( $password );
		$url = $url."&appid=1000&persistentcookie=0&s=".time( )."&b=1&w=1024&pwdtype=1";
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		ob_start( );
		curl_exec( $ch );
		$contents = ob_get_contents( );
		ob_end_clean( );
		curl_close( $ch );
		if ( strpos( $contents, "success" ) === false )
		{
			return 0;
		}
		return 1;
	}

	function getcontacts( $user, $password, &$result )
	{
		if ( !$this->checklogin( $user, $password ) )
		{
			return 0;
		}
		
		$cookies = array( );
	
		$bRet = $this->readcookies( COOKIEJAR, $cookies );
		

		if ( !$bRet && !$cookies['JSESSIONID'] )
		{
			return 0;
		}


		
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		curl_setopt( $ch, CURLOPT_URL, "http://mail.sohu.com/address/export" );
	
		ob_start( );
		curl_exec( $ch );
		$content = ob_get_contents( );
		ob_end_clean( );
		curl_close( $ch );
		$pattern = "/([\\w_-])+@([\\w])+([\\w.]+)/";
		if ( preg_match_all( $pattern, $content, $tmpres, PREG_PATTERN_ORDER ) )
		{
			$result = array_unique( $tmpres[0] );
		}
		return 1;
	}
}

?>
