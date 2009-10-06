<?php
/**
 * 获得sina邮箱通讯录列表 -- contactssina.class.php
 */

class contactssina extends contacts
{

	var $host = "";

	function checklogin( $user, $password )
	{
		if ( empty( $user ) || empty( $password ) )
		{
			return 0;
		}
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_REFERER, "http://mail.sina.com.cn/index.html" );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, USERAGENT );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		curl_setopt( $ch, CURLOPT_URL, "http://mail.sina.com.cn/cgi-bin/login.cgi" );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "&logintype=uid&u=".urlencode( $user )."&psw=".$password );
		$contents = curl_exec( $ch );
		curl_close( $ch );
		if ( !preg_match( "/Location: (.*)\\/cgi\\/index\\.php\\?check_time=(.*)\n/", $contents, $matches ) )
		{
			return 0;
		}
		$this->host = $matches[1];
		return 1;
	}

	function getcontacts( $user, $password, &$result )
	{
		if ( !$this->checklogin( $user, $password ) )
		{
			return 0;
		}
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, USERAGENT );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		curl_setopt( $ch, CURLOPT_URL, "http://mail.sina.com.cn/cgi-bin/login.cgi" );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "&logintype=uid&u=".urlencode( $user )."&psw=".$password );
		curl_exec( $ch );
		curl_close( $ch );
		$cookies = array( );
		$bRet = $this->readcookies( COOKIEJAR, $cookies );
		if ( !$bRet && !$cookies['SWEBAPPSESSID'] )
		{
			return 0;
		}
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		curl_setopt( $ch, CURLOPT_URL, $this->host."/classic/addr_member.php" );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "&act=list&sort_item=letter&sort_type=desc" );
		$content = curl_exec( $ch );
		curl_close( $ch );
		$bRet = $this->_parsedata( $content, $result );
		if ( !$bRet )
		{
			return 0;
		}
		return 1;
	}

	function _parsedata( $content, &$ar )
	{
		$ar = array( );
		if ( !$content )
		{
			return 0;
		}
		$data = json_decode( $content );
		unset( $content );
		foreach ( $data->data->contact as $value )
		{
			if ( preg_match_all( "/[a-z0-9_\\.\\-]+@[a-z0-9\\-]+\\.[a-z]{2,6}/i", $value->email, $matches ) )
			{
				$emails = array_unique( $matches[0] );
				unset( $matches );
				foreach ( $emails as $email )
				{
					$ar[$email] = $value->name;
				}
			}
		}
		return 1;
	}

}

?>
