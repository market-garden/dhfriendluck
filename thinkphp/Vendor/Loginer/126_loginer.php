<?php
require_once './Include/findfriends/browser.php';
/**
 * 获取126邮箱好友
 *
 * 用例：
 *
 * $friends = get_126_friends('diogin', '123456');
 * if ($friends === false) {
 *     exit('获取失败');
 * }
 * foreach ($friends as $email => $nick) {
 *     echo $nick, $email;
 * }
 *
 * @param  string $user  - 用户名
 * @param  string $pass  - 密码
 * @return boolean|array - 成功返回一个数组，邮箱名做key，昵称做value。失败返回false
 * @author Jingcheng Zhang <diogin@gmail.com>
 */
function get_126_friends($user, $pass,$proxy_url = '') {
	$user = urlencode($user);
	$pass = urlencode($pass);
	$properties = array(
	'User-Agent'      => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
	'Accept'          => 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
	'Accept-Language' => 'en-us,en;q=0.5',
	'Accept-Charset'  => 'GB2312,utf-8;q=0.7,*;q=0.7',
	'Connection'      => 'close'
	);
	try {
		$proxy_host = 'entry.mail.126.com';
		$proxy_port = 80;
		if(!empty($proxy_url)){
			$proxy_url_ar = split(':',$proxy_url);
			$proxy_host = $proxy_url_ar[0];
			$proxy_port = (int)$proxy_url_ar[1];
		}


		$url = "https://reg.163.com/logins.jsp?type=1&product=mail126&url=http://entry.mail.126.com/cgi/ntesdoor?hid%3D10010102%26lightweight%3D1%26verifycookie%3D1%26language%3D0%26style%3D-1";
		$ch = curl_init($url);
		$curl_opts = array(
		CURLOPT_POST           => true,
		CURLOPT_HEADER         => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_AUTOREFERER    => true,
		CURLOPT_PROXY => $proxy_url,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
		CURLOPT_POSTFIELDS     => "domain=126.com&language=0&bCookie=&username=".urlencode($user."@126.com")."&user={$user}&password={$pass}&style=11&remUser=&secure=&enter.x=%B5%C7+%C2%BC"
		);

		foreach($curl_opts as $key=>$value){
			curl_setopt($ch, $key, $value);
		}
		$response = curl_exec($ch);
		curl_close($ch);
		preg_match_all("/Set-Cookie: (.*)\r\n/", $response, $matches);
		if(!isset($matches[1])){
			return false;
		}
		$cookies = array();
		foreach ($matches[1] as $match) {
			list($key, $value) = explode('=', $match, 2);
			$value = explode('; ', $value, 2);
			$value = $value[0];
			$cookies[trim($key)] = trim($value);
		}

		preg_match("/0;URL=(.*)\"/", $response, $matches);
		$url = $matches[1];

		// 浏览器对象
		$browser = new browser($properties, '.126.com');
		$browser->add_cookies($cookies);
		$parts = parse_url("http://entry.mail.126.com/cgi/ntesdoor?hid=10010102&lightweight=1&verifycookie=1&language=0&style=11&username={$user}@126.com");
		$host  = $parts['host'];
		$uri = $parts['path'].'?'.$parts['query'];
		$request = new request($browser, 'GET', $uri, $host);
		$socket = new socket($host, 80);
		$response = $browser->send($socket, $request);
		if ($response->get_status() !== 302) {
			throw new Exception('Failed on 1st request');
		}

		$location = $response->get_header('Location');
		$parts = parse_url($location);
		$host  = $parts['host'];
		$uri   = '/coremail/fcg/ldvcapp?funcid=prtsearchres&' . $parts['query'] . '&sortattr_N=&showlist=&listnum=-1&ifirstv=&tempname=address%2Faddrdata_ntes.htm';
		$sid_ar = split('&',$parts['query']);
		$sid_str = $sid_ar[1];

		// 第二个请求
		$request = new request($browser, 'GET', $uri, $host);
		$socket = new socket($host, 80);
		$response = $browser->send($socket, $request);

		$body = $response->get_body();
		if ($response->get_status() !== 200) {
			throw new Exception('Failed on 2nd request');
		}
		preg_match_all('/<xmp>(.*)<\/xmp>/', $body, $matches);
		if (!isset($matches[1])) {
			return array();
		}
		$friends = array();
		foreach ($matches[1] as $match) {
			list($nick, $email) = explode('<space>', $match);
			$nick = trim($nick);
			$email = trim($email);
			if (is_email($email)) {
				$friends[$email] = @iconv("GB2312//ignore","UTF-8//ignore",$nick);
			}
		}
		$first_num = count($friends);
		if($first_num >= 5){
			return $friends;
		}

		$hosts = array('g1a67.mail.126.com','g1a89.mail.126.com','g4a44.mail.126.com','g4a34.mail.126.com','g1a59.mail.126.com');
		shuffle($hosts);
		$host = $hosts[0];

		//请求Email列表页面
		$request = new request($browser, 'GET', "/a/s?{$sid_str}&func=mbox:listMessages", $host);
		$socket = new socket($host, 80);
		$response = $browser->send($socket, $request);
		if ($response->get_status() !== 200) {
			throw new Exception('Failed on 4nd request');
		}
		$body = $response->get_body();
		preg_match_all("/<string name=\"from\"\>\"(.*?)\" \&lt;([a-zA-Z0-9_\.]+@[a-zA-Z0-9_\.]+)?\&gt;<\/string\>/si", $body, $off);
		if(isset($off[2][0])){
			foreach ($off[2] as $key=>$email){
				if(preg_match("/service/i",$email) || preg_match("/kefu/i",$email) || preg_match("/report/i",$email) || preg_match("/notify/i",$email)){
					continue;
				}
				if(!empty($off[2][$key]) && !isset($friends[$email])){
					$friends[$email] = $off[2][$key];
				}
			}
		}
		$second_num = count($friends);
		$log_str = date("Y-m-d H:i:s")."|{$user}|fir|{$first_num}|sec|{$second_num}";
		return $friends;
	} catch (Exception $e) {
		return false;
	}
}
function is_email($email) {
	if (!is_string($email) || $email === '') {
		return false;
	}
	return preg_match('/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,6}$/', $email);
}
