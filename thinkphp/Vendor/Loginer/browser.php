<?php
/**
 * 浏览器
 */
class browser {
    public function __construct(array $properties, $cookie_domain) {
        $this->properties = $properties;
        $this->cookie_domain = $cookie_domain;
    }
    public function go($url) {
        $urls = parse_url($url);
        if ($urls === false || !isset($urls['host'])) {
            throw new Exception("Invalid url: '{$url}'");
        }
        $host = $urls['host'];
        $port = isset($urls['port']) ? (int)$urls['port'] : 80;
        $path = isset($urls['path']) ? $urls['path'] : '/';
        if (isset($urls['query'])) {
            $path .= '?' . $urls['query'];
        }
        $socket = new socket($host, $port);
        $request = new request($this, 'GET', $path, $host);
        $response = $this->send($socket, $request);
        return $response->get_content();
    }
    public function go_https($url) {
        $curl = curl_init();
    }
    public function send(socket $socket, request $request) {
        if ($this->has_cookie()) {
            $request->add_header('Cookie', $this->get_cookie_header());
        }
        $request_message = $request->fetch();
        // echo $request_message;
        $socket->write($request_message);
        $response = new response($socket->read());
        $cookies = $response->get_header('Set-Cookie');
        if ($cookies !== null) {
            if (is_array($cookies)) {
                foreach ($cookies as $cookie) {
                    $this->parse_cookie($cookie);
                }
            } else {
                $this->parse_cookie($cookies);
            }
        }
        return $response;
    }
    public function add_cookie($key, $value) {
        $this->cookies[$key] = $value;
    }
    public function del_cookie($key) {
        unset($this->cookies[$key]);
    }
    public function add_cookies(array $cookies) {
        $this->cookies = array_merge($this->cookies, $cookies);
    }
    public function get_cookie_header() {
        $header = array();
        foreach ($this->cookies as $key => $value) {
            $header[] = $key . '=' . $value;
        }
        return implode('; ', $header);
    }
    public function set_property($key, $value) {
        $this->properties[$key] = $value;
    }
    public function get_properties() {
        return $this->properties;
    }
    private function has_cookie() {
        return $this->cookies !== array();
    }
    private function parse_cookie($cookie) {
        $cookie_domain = $this->cookie_domain;
        if ($cookie_domain[0] === '.') {
            $cookie_domain = substr($cookie_domain, 1);
        }
        if (preg_match("/domain=(.*){$cookie_domain}/", $cookie)) {
            $pair = substr($cookie, 0, strpos($cookie, '; '));
            list($key, $value) = explode('=', $pair);
            $key = trim($key);
            $value = trim($value);
            if ($value === 'deleted') {
                $this->del_cookie($key);
            } else {
                $this->add_cookie($key, $value);
            }
        }
    }
    private $properties = array();
    private $cookie_domain = '';
    private $cookies = array();
}
/**
 * HTTP请求
 */
class request {
    public function __construct(browser $browser, $method, $uri, $host) {
        $this->browser = $browser;
        $this->method = $method;
        $this->uri = $uri;
        $this->host = $host;
    }
    public function add_header($key, $value) {
        $this->headers[$key] = $value;
    }
    public function set_body($body) {
        $this->body = $body;
    }
    public function fetch() {
        $ret = $this->method . ' ' . $this->uri . ' HTTP/1.1' . "\r\n";
        $ret .= 'Host: ' . $this->host . "\r\n";
        foreach ($this->browser->get_properties() as $key => $value) {
            $ret .= $key . ': ' . $value . "\r\n";
        }
        foreach ($this->headers as $key => $value) {
            $ret .= $key . ': ' . $value . "\r\n";
        }
        $ret .= "\r\n";
        if ($this->body !== '') {
            $ret .= $this->body;
        }
        return $ret;
    }
    private $browser = null;
    private $method = '';
    private $uri = '';
    private $host = '';
    private $headers = array();
    private $body = '';
}
/**
 * HTTP响应
 */
class response {
    public function __construct($content) {
        $separator = strpos($content, "\r\n\r\n");
        if ($separator === false) {
            throw new Exception("Invalid response content");
        }
        $this->content = $content;
        $this->body = substr($content, $separator + 4);
        $this->header = $header = substr($content, 0, $separator);
        $heads  = explode("\r\n", $header);
        $titles = explode(' ', array_shift($heads));
        $this->status = (int)$titles[1];
        foreach ($heads as $head) {
            list($key, $value) = explode(': ', $head);
            $key   = trim($key);
            $value = trim($value);
            if (isset($this->headers[$key])) {
                if (is_array($this->headers[$key])) {
                    $this->headers[$key][] = $value;
                } else {
                    $this->headers[$key] = array($this->headers[$key]);
                    $this->headers[$key][] = $value;
                }
            } else {
                $this->headers[$key] = $value;
            }
        }
    }
    public function get_content() {
        return $this->content;
    }
    public function get_status() {
        return $this->status;
    }
    public function get_headers() {
        return $this->headers;
    }
    public function get_header($key = '') {
        if ($key === '') {
            return $this->header;
        }
        if (!isset($this->headers[$key])) {
            return null;
        }
        return $this->headers[$key];
    }
    public function get_body() {
        return $this->body;
    }
    public function fetch() {
        return $this->content;
    }
    private $content = '';
    private $status = 0;
    private $headers = array();
    private $header = '';
    private $body = '';
}
/**
 * 套接口
 */
class socket {
    public function __construct($host, $port) {
        $ip = $host;
        if (ip2long($ip) === false) {
            $ip = gethostbyname($host);
            if ($ip === $host) {
                throw new Exception("Cannot resolve host: '{$host}'");
            }
        }
        $port = (int)$port;
        $fp = fsockopen($ip, $port);
        if ($fp === false) {
            throw new Exception("Cannot connect to '{$host}:{$port}'");
        }
        $this->fp = $fp;
        $this->ip = $ip;
        $this->port = $port;
    }
    public function __destruct() {
        if ($this->fp !== null) {
            fclose($this->fp);
        }
    }
    public function read() {
        $ret = '';
        while (!feof($this->fp)) {
            $buffer = fread($this->fp, 1024);
            if ($buffer === false) {
                throw new Exception("Cannot read on '{$this->ip}:{$this->port}'");
            }
            $ret .= $buffer;
        }
        return $ret;
    }
    public function write($content) {
        $len = strlen($content);
        $sent = 0;
        $left = $len;
        do {
            $num = fwrite($this->fp, substr($content, $sent, $left));
            if ($num === false) {
                throw new Exception("Cannot write on '{$this->ip}:{$this->port}'");
            }
            $left -= $num;
            $sent += $num;
        } while ($left > 0);
    }
    private $fp = null;
    private $ip = '';
    private $port = 0;
}

