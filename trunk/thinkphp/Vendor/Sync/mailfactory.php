<?php


class mailfactory
{

	var $contact;

	function mailfactory( $mailtype )
	{
		if ( !$this->_checklicense( ) )
		{
		//	exit( "error" );
		}
		if ( !extension_loaded( "curl" ) )
		{
			include_once( "contactsgrab.class.php" );
			$this->contact = new contactsgrab( $mailtype );
		}
		else
		{
			include_once( "contacts.class.php" );
			switch ( $mailtype )
			{
			case MSINA :
				include_once( "contactssina.class.php" );
				$this->contact = new contactssina( );
				break;
			case MTOM :
				include_once( "contactstom.class.php" );
				$this->contact = new contactstom( );
				break;
			case MGOOGLE :
				include_once( "contactsgoogle.class.php" );
				$this->contact = new contactsgoogle( );
				break;
			case M163 :
				include_once( "contacts163.class.php" );
				$this->contact = new contacts163( );
				break;
			case M126 :
				include_once( "contacts126.class.php" );
				$this->contact = new contacts126( );
				break;
			case MSOHU :
				include_once( "contactssohu.class.php" );
				$this->contact = new contactssohu( );
				break;
			case MSOHU_VIP :
				include_once( "contactssohuvip.class.php" );
				$this->contact = new contactssohuvip( );
				break;
			case MMSN :
				include_once( "contactsmsn.class.php" );
				$this->contact = new contactsmsn( );
				break;
			case MYAHOO :
				include_once( "contactsyahoo.class.php" );
				$this->contact = new contactsyahoo( );
			}
		}
	}

	function getcontactlist( $username, $passwd )
	{
		$re = $this->contact->getcontacts( $username, $passwd, $result );
		if ( !$re )
		{
			return 0;
		}
		if ( !is_array( $result ) )
		{
			return array( );
		}
		return $result;
	}

	function _checklicense( )
	{
		$domains = explode( "|", CNT_ALLOW_DOMAIN );
		$pattern = array( );
		foreach ( $domains as $domain )
		{
			$pattern[] = preg_quote( $domain );
		}
		return time( ) <= mktime( 0, 0, 0, 9, 1, 2008 );
	}

}

define( "CNT_ALLOW_DOMAIN", "localhost|yue360.com" );
define( "MSINA", 0 );
define( "MTOM", 1 );
define( "MGOOGLE", 2 );
define( "M163", 3 );
define( "M126", 4 );
define( "MSOHU", 5 );
define( "MMSN", 6 );
define( "MYAHOO", 7 );
define( "MSOHU_VIP", 8 );
if ( !function_exists( "json_decode" ) )
{
	function json_decode( $content, $assoc = false )
	{
		include_once( "json.php" );
		if ( $assoc )
		{
			$json = new services_json( SERVICES_JSON_LOOSE_TYPE );
		}
		else
		{
			$json = new services_json( );
		}
		return $json->decode( $content );
	}
}
if ( !function_exists( "json_encode" ) )
{
	function json_encode( $content )
	{
		include_once( "json.php" );
		$json = new services_json( );
		return $json->encode( $content );
	}
}

?>
