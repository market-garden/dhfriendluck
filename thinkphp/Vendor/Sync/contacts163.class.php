<?php
/**
 * 获得163邮箱通讯录列表 -- contacts163.class.php
 */

class contacts163 extends contacts
{

	function checklogin( $user, $password )
	{
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_URL, "https://reg.163.com/logins.jsp" );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "username=".$user."&password=".$password."&type=1" );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		ob_start( );
		curl_exec( $ch );
		$contents = ob_get_contents( );
		ob_end_clean( );
		curl_close( $ch );
		if ( strpos( $contents, "��??��?��?��" ) !== false )
		{
			//return 0;
		}
		return 1;
	}

	function getcontacts( $user, $password, &$result )
	{
		if ( !$this->checklogin( $user, $password ) )
		{
			return 0;
		}
		$bRet = $this->_getcookie( );
		
		print_r($cookies);
		$bRet = $this->readcookies( COOKIEJAR, $cookies );
		$cookieid = substr( trim( $cookies['Coremail'] ), -32 );
		if ( !$cookieid )
		{
			return 0;
		}
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_URL, "http://g1a126.mail.163.com/a/s?sid=".$cookieid."&func=global:sequential" );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( "Content-Type: application/xml" ) );
		$str = "<?xml version=\"1.0\"?><object><array name=\"items\"><object><string name=\"func\">pab:searchContacts</string><object name=\"var\"><array name=\"order\"><object><string name=\"field\">FN</string><boolean name=\"ignoreCase\">true</boolean></object></array></object></object><object><string name=\"func\">user:getSignatures</string></object><object><string name=\"func\">pab:getAllGroups</string></object></array></object>";
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $str );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		ob_start( );
		curl_exec( $ch );
		$contents = ob_get_contents( );
		ob_end_clean( );
		curl_close( $ch );
		$pattern = "/([\\w_-])+@([\\w])+([\\w.]+)/";
		if ( preg_match_all( $pattern, $contents, $tmpres, PREG_PATTERN_ORDER ) )
		{
			$result = array_unique( $tmpres[0] );
		}
		return 1;
	}

	function _getcookie( )
	{
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_URL, "http://entry.mail.163.com/coremail/fcg/ntesdoor2?lightweight=1&verifycookie=1&language=-1" );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, COOKIEJAR );
		ob_start( );
		curl_exec( $ch );
		$contents = ob_get_contents( );
		
		ob_end_clean( );
		curl_close( $ch );
	}

}

?>
