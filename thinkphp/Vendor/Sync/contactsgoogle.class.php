<?php
/**
 * 获得gmail邮箱通讯录列表 -- contactsyahoo.class.php
 */

class contactsgoogle extends contacts
{

	function checklogin( $user, $password )
	{
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$url = "https://www.google.com/accounts/ServiceLoginAuth?service=mail";
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		$fileds = "continue=http://mail.google.com/mail?&Email=".$user."&hl=en&Passwd={$password}";
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $fileds );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, COOKIEJAR );
		ob_start( );
		curl_exec( $ch );
		$result = ob_get_contents( );
		ob_end_clean( );
		curl_close( $ch );
		if ( preg_match( "/errormsg|��??��??o��?��??2??��??|sername and password do not match/", $result ) )
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
		$ch = curl_init( );
		$url = "http://mail.google.com/mail/contacts/data/contacts?thumb=true&groups=true&show=ALL&enums=true&psort=Name&max=300&out=js&rf=&jsx=true";
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, COOKIEJAR );
		curl_setopt( $ch, CURLOPT_TIMEOUT, TIMEOUT );
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
