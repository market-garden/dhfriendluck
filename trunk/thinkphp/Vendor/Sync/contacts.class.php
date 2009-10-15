<?php


class contacts
{

	function checklogin( $user, $password )
	{
		return 1;
	}

	function getcontacts( $user, $password, &$result )
	{
		return 1;
	}

	function readcookies( $file, &$result )
	{
		
		$fp = fopen( $file, "r" );
		while ( !feof( $fp ) )
		{
			$buffer = fgets( $fp, 4096 );
			$tmp = split( "\t", $buffer );
			$result[trim( $tmp[5] )] = trim( $tmp[6] );
		}
		return 1;
	}

}

define( "USERAGENT", $_SERVER['HTTP_USER_AGENT'] );
define( "COOKIEJAR", tempnam( ini_get( "upload_tmp_dir" ), "cookie" ) );
define( "TIMEOUT", 10 );
?>
