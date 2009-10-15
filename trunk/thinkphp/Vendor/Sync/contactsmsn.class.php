<?php
/**
 * 获得MSN好友列表通道文件 -- contactsmsn.class.php
 */

include_once( "msn.class.php" );
class contactsmsn extends contacts
{

	function checklogin( $user, $password )
	{
		return true;
	}

	function getcontacts( $user, $password, &$result )
	{
		$msn = new msn( );
		//echo "AAA".$user.$msn->connect( $user, $password ).$password."AAA";exit();
		if ( !$msn->connect( $user, $password ) )
		{
			return false;
		}
		$msn->rx_data( );
		$msn->process_emails( );
		$returned_emails = $msn->email_output;
		foreach ( $returned_emails as $value )
		{
			$result[$value[0]] = $value[1];
		}
		return true;
	}

}

?>
