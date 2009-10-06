<?php
/**
 * 
 */ 

class contactsgrab
{

	var $mtype = "";

	function contactsgrab( $mtype = 0 )
	{
		$this->mtype = $mtype;
	}

	function getcontacts( $user, $password, &$result )
	{
		$errno = $errstr = "";
		$sock = fsockopen( "u.yue360.com", 80, $errno, $errstr, 30 );
		if ( !$sock )
		{
			return 0;
		}
		$data = "account=".urlencode( $user )."&passwd=".$password."&mtype=".$this->mtype;
		$out = "POST /widget/grabbers/grabbers.php HTTP/1.0\r\n";
		$out .= "Host: u.yue360.com\r\n";
		$out .= "Content-type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: Manyou API PHP Client 0.1 (NON-CURL) ".phpversion( )."\r\n";
		$out .= "Content-length: ".strlen( $data )."\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "\r\n";
		$out .= $data."\r\n";
		$out .= "\r\n";
		if ( !fwrite( $sock, $out ) )
		{
			echo "asdsads";
			return 0;
		}
		$ret = "";
		while ( !feof( $sock ) )
		{
			$ret .= fgets( $sock, 4096 );
		}
		fclose( $sock );
		$ret_arr = explode( "\r\n\r\n", $ret );
		$result = unserialize( trim( $ret_arr[1] ) );
		unset( $ret );
		unset( $ret_arr );
		return 1;
	}

}

?>
