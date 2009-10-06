<?php
require_once './Include/findfriends/browser.php';
/**
 * 获取163邮箱好友
 *
 * 用例：
 *
 * $friends = get_163_friends('diogin', '123456');
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
function get_163_friends($user, $pass,$proxy_url = '') {
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
		// 浏览器对象
		$browser = new browser($properties, '.163.com');
		$browser->add_cookies(array('Province' => '010', 'City' => '010', 'ntes_mail_firstpage' => 'normal', 'ntes_mail_noremember' => 'true'));

		$first_host = 'reg.163.com';
		$second_host = 'entry.mail.163.com';
		$proxy_port = 80;
		if(!empty($proxy_url)){
			$proxy_url_ar = split(':',$proxy_url);
			$proxy_host = $proxy_url_ar[0];
			$proxy_port = (int)$proxy_url_ar[1];
			$first_host = $proxy_host;
			$second_host = $proxy_host;
		}
		// 第一个请求
		$request = new request($browser, 'POST', '/login.jsp?type=1&url=http://fm163.163.com/coremail/fcg/ntesdoor2?lightweight%3D1%26verifycookie%3D1%26language%3D-1%26style%3D16', 'reg.163.com');
		$request->add_header('Referer', 'http://mail.163.com/');
		$request->add_header('Content-Type', 'application/x-www-form-urlencoded');
		$body = "verifycookie=1&style=16&product=mail163&username={$user}&password={$pass}&selType=jy&%B5%C7%C2%BC%D3%CA%CF%E4=%B5%C7%C2%BC%D3%CA%CF%E4";
		$request->add_header('Content-Length', strlen($body));
		$request->set_body($body);
		$socket = new socket($first_host, $proxy_port);
		$response = $browser->send($socket, $request);
		$body = $response->get_body();
		if ($response->get_status() !== 200) {
			throw new Exception('Failed on 1st request');
		}

		// 第二个请求
		$request = new request($browser, 'GET', "/coremail/fcg/ntesdoor2?lightweight=1&verifycookie=1&language=-1&style=16&username={$user}", 'entry.mail.163.com');
		$socket = new socket($second_host, $proxy_port);
		$response = $browser->send($socket, $request);
		$body = $response->get_body();
		if ($response->get_status() !== 302) {
			throw new Exception('Failed on 2nd request');
		}
		$location = $response->get_header('Location');
		$parts = parse_url($location);
		$host  = $parts['host'];
		$uri   = '/coremail/fcg/ldvcapp?funcid=prtsearchres&' . $parts['query'] . '&sort=attr_N=&showlist=&listnum=-1&ifirstv=&tempname=address%2Faddrdata_ntes.htm';
		$sid_ar = split('&',$parts['query']);
		$sid_str = $sid_ar[1];

		// 第三个请求
		$request = new request($browser, 'GET', $uri, $host);
		$socket = new socket($host, 80);
		$response = $browser->send($socket, $request);
		$body = $response->get_body();
		if ($response->get_status() !== 200) {
			throw new Exception('Failed on 3rd request');
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
			if (!is_string($email) || $email === '') {
				continue;
			}
			if(preg_match('/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,6}$/', $email)){
				$friends[$email] = @iconv("GB2312//ignore","UTF-8//ignore",$nick);
			}
		}
		$first_num = count($friends);
		if($first_num >= 5){
			return $friends;
		}

		$hosts = array('g7a58.mail.163.com','g6a67.mail.163.com','g3a28.mail.163.com','g3a64.mail.163.com','g6a53.mail.163.com','g1a97.mail.163.com','g3a23.mail.163.com');
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
