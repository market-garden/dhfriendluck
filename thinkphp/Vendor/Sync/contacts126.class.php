<?php
error_reporting(E_ALL);

class contacts126 extends  contacts{
  private $userName="";
  private $cookie=COOKIEJAR;
  private $timeout=TIMEOUT;
  private $result_login="";
  
  private $url1= 'http://reg.163.com/login.jsp?type=1&product=mail126&url=http://entry.mail.126.com/cgi/ntesdoor?hid%3D10010102%26lightweight%3D1%26language%3D0%26style%3D-1';

  public function login($user, $password){
   $ch= curl_init($this->url1);
  
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   //$this->userName= = $user."@126.com"; 
   $fields_post = array(
         'domain'    => '126.com' ,
         'language'  => 0 ,
         'bCookie'   => '' ,
         'username'  => $user."@126.com",
         'savelogin' => '',
         'user'      => $user ,
         'remUser'   => '',
         'secure'    => '',
         'password'  => $password
   );

   $fields_string = '';
   foreach($fields_post as $key => $value){
       $fields_string .= $key . '=' . $value . '&';
   }
   $fields_string = rtrim($fields_string , '&');
   curl_setopt($ch, CURLOPT_COOKIESESSION, true);
   curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
   curl_setopt($ch, CURLOPT_POST,1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
   $this->result_login = curl_exec($ch);
   curl_close($ch);
   if ( strpos( $this->result_login, "��??��?��?��" ) !== false ){
    return 0;
   }
   return 1;
  }
 function getcontacts( $user, $password, &$result){
   $this->login($user,$password); 
   preg_match('/http:\/\/passport.126.com.*loginyoudao=0/', $this->result_login, $url_c_1);
   $ch = curl_init($url_c_1[0]);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
   curl_setopt($ch, CURLOPT_POST, true);
   $referer_check_1 = $this->url1;
   curl_setopt($ch, CURLOPT_REFERER, $referer_check_1);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
   curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
   $result_check_1 = curl_exec($ch);
   curl_close($ch);
   preg_match('/http:\/\/entry.mail.126.com.*@126.com/', $result_check_1, $url_c_2);
   $ch = curl_init($url_c_2[0]);

   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_HEADER, true);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
   curl_setopt($ch, CURLOPT_POST, true);

   $referer_check_2 = $url_c_1[0];
   curl_setopt($ch, CURLOPT_REFERER, $referer_check_2);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
   curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
   $result_check_2 = curl_exec($ch);
   curl_close($ch);
   preg_match('/sid=[^\"].*/', $result_check_2, $location);
   $sid = substr($location[0], 4, -1);
   $ch = curl_init();
   curl_setopt( $ch, CURLOPT_URL, "http://g1a65.mail.126.com/a/s?sid=".$sid."&func=global:sequential" );
   curl_setopt( $ch, CURLOPT_COOKIEFILE, $this->cookie );
   curl_setopt( $ch, CURLOPT_HTTPHEADER, array( "Content-Type: application/xml" ) );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array( "Content-Type: application/xml" ) );
		$str = "<?xml version=\"1.0\"?><object><array name=\"items\"><object><string name=\"func\">pab:searchContacts</string><object name=\"var\"><array name=\"order\"><object><string name=\"field\">FN</string><boolean name=\"ignoreCase\">true</boolean></object></array></object></object><object><string name=\"func\">user:getSignatures</string></object><object><string name=\"func\">pab:getAllGroups</string></object></array></object>";
   curl_setopt( $ch, CURLOPT_POST, 1 );
   curl_setopt( $ch, CURLOPT_POSTFIELDS, $str );
   curl_setopt( $ch, CURLOPT_TIMEOUT, $this->timeout );
   ob_start( );
   curl_exec( $ch );
   $contents = ob_get_contents( );
   ob_end_clean( );
   curl_close( $ch );
   $pattern = "/<string name=\"EMAIL;PREF\">(.*)<\/string>/";
   if ( preg_match_all( $pattern, $contents, $tmpres, PREG_PATTERN_ORDER ) ){
    $result1 = array_unique( $tmpres[1] );
    $pattern = "/<string name=\"FN\">(.*)<\/string>/";
    if ( preg_match_all( $pattern, $contents, $tmpres, PREG_PATTERN_ORDER ) ){
     $result2 = array_unique( $tmpres[1] );
    }
    $count=count($result1);
    for($i=0;$i<$count;$i++){
     //$result[$i][]=$result1[$i];
     //$result[$i][]=$result2[$i];
	 $result[$i] = $result1[$i];
    }
   }
   if($result)
    return $result;
   unlink($this->cookie);
  }
    
 }


//$contacts126 = new contacts126();
//$value = $contacts126->getcontacts('song_0803','174620274',$result);
 ?>