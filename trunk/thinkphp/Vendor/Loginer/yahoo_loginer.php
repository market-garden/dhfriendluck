<?php
require_once './Include/findfriends/browser.php';
// 测试用例
//var_dump(get_yahoo_friends('tiogin@yahoo.com.cn', '123456'));
/**
 * 获取yahoo中国邮箱好友列表
 */
function get_yahoo_friends($mail, $pass,$proxy_url = '') {
    $mail = urlencode($mail);
    $pass = urlencode($pass);
    $ch = curl_init("https://edit.bjs.yahoo.com/config/login");
    $curl_opts = array(
        CURLOPT_POST           => true,
        CURLOPT_HEADER         => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_PROXY => $proxy_url,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_POSTFIELDS     => "login={$mail}&passwd={$pass}"
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

    //第二步
    $browser = new browser(array(), '.yahoo.com');
    $browser->add_cookies($cookies);
    $socket = new socket('address.mail.yahoo.com', 80);
    $request = new request($browser, 'GET', '/', 'address.mail.yahoo.com');
    $response = $browser->send($socket, $request);
    $body = $response->get_body();
    preg_match_all('/<span class="contactname"><a href=(.*)>(.*)<\/a><\/span>/', $body, $matches_1);
    preg_match_all('/<a  href="http:\/\/mrd\.mail\.yahoo\.com\/compose\?To=(.*)">(.*)<\/a>/', $body, $matches_2);
    if(!isset($matches_1[2]) || !isset($matches_2[2])){//匹配为空
        return array();
    }
    $mails = $matches_2[2];
    $nicks = $matches_1[2];
    $count = count($mails);
    $friends = array();
    for ($i = 0; $i < $count; ++$i) {
        $friends[trim($mails[$i])] = trim($nicks[$i]);
    }
    return $friends;
}
