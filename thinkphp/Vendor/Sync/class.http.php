<?php


class http
{

	var $target;
	var $host;
	var $port;
	var $path;
	var $schema;
	var $method;
	var $params;
	var $cookies;
	var $_cookies;
	var $timeout;
	var $useCurl;
	var $referrer;
	var $userAgent;
	var $cookiePath;
	var $useCookie;
	var $saveCookie;
	var $username;
	var $password;
	var $result;
	var $headers;
	var $status;
	var $redirect;
	var $maxRedirect;
	var $curRedirect;
	var $error;
	var $nextToken;
	var $debug;
	var $debugMsg;

	function http( )
	{
		$this->clear( );
	}

	function initialize( $config = array( ) )
	{
		$this->clear( );
		foreach ( $config as $key => $val )
		{
			if ( isset( $this->$key ) )
			{
				$method = "set".ucfirst( str_replace( "_", "", $key ) );
				if ( method_exists( $this, $method ) )
				{
					$this->$method( $val );
				}
				else
				{
					$this->$key = $val;
				}
			}
		}
	}

	function clear( )
	{
		$this->host = "";
		$this->port = 0;
		$this->path = "";
		$this->target = "";
		$this->method = "GET";
		$this->schema = "http";
		$this->params = array( );
		$this->headers = array( );
		$this->cookies = array( );
		$this->_cookies = array( );
		$this->debug = FALSE;
		$this->error = "";
		$this->status = 0;
		$this->timeout = "25";
		$this->useCurl = TRUE;
		$this->referrer = "";
		$this->username = "";
		$this->password = "";
		$this->redirect = TRUE;
		$this->nextToken = "";
		$this->useCookie = TRUE;
		$this->saveCookie = TRUE;
		$this->maxRedirect = 3;
		$this->cookiePath = "cookie.txt";
		$this->userAgent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.9";
	}

	function settarget( $url )
	{
		if ( $url )
		{
			$this->target = $url;
		}
	}

	function setmethod( $method )
	{
		if ( $method == "GET" || $method == "POST" )
		{
			$this->method = $method;
		}
	}

	function setreferrer( $referrer )
	{
		if ( $referrer )
		{
			$this->referrer = $referrer;
		}
	}

	function setuseragent( $agent )
	{
		if ( $agent )
		{
			$this->userAgent = $agent;
		}
	}

	function settimeout( $seconds )
	{
		if ( 0 < $seconds )
		{
			$this->timeout = $seconds;
		}
	}

	function setcookiepath( $path )
	{
		if ( $path )
		{
			$this->cookiePath = $path;
		}
	}

	function setparams( $dataArray )
	{
		if ( is_array( $dataArray ) )
		{
			$this->params = array_merge( $this->params, $dataArray );
		}
	}

	function setauth( $username, $password )
	{
		if ( !empty( $username ) || !empty( $password ) )
		{
			$this->username = $username;
			$this->password = $password;
		}
	}

	function setmaxredirect( $value )
	{
		if ( !empty( $value ) )
		{
			$this->maxRedirect = $value;
		}
	}

	function addparam( $name, $value )
	{
		if ( !empty( $name ) || !empty( $value ) )
		{
			$this->params[$name] = $value;
		}
	}

	function addcookie( $name, $value )
	{
		if ( !empty( $name ) || !empty( $value ) )
		{
			$this->cookies[$name] = $value;
		}
	}

	function usecurl( $value = TRUE )
	{
		if ( is_bool( $value ) )
		{
			$this->useCurl = $value;
		}
	}

	function usecookie( $value = TRUE )
	{
		if ( is_bool( $value ) )
		{
			$this->useCookie = $value;
		}
	}

	function savecookie( $value = TRUE )
	{
		if ( is_bool( $value ) )
		{
			$this->saveCookie = $value;
		}
	}

	function followredirects( $value = TRUE )
	{
		if ( is_bool( $value ) )
		{
			$this->redirect = $value;
		}
	}

	function getresult( )
	{
		return $this->result;
	}

	function getheaders( )
	{
		return $this->headers;
	}

	function getstatus( )
	{
		return $this->status;
	}

	function geterror( )
	{
		return $this->error;
	}

	function execute( $target = "", $referrer = "", $method = "", $data = array( ) )
	{
		$this->target = $target ? $target : $this->target;
		$this->method = $method ? $method : $this->method;
		$this->referrer = $referrer ? $referrer : $this->referrer;
		if ( is_array( $data ) && 0 < count( $data ) )
		{
			$this->params = array_merge( $this->params, $data );
		}
		if ( is_array( $this->params ) && 0 < count( $this->params ) )
		{
			$tempString = array( );
			foreach ( $this->params as $key => $value )
			{
				if ( 0 < strlen( trim( $value ) ) )
				{
					$tempString[] = $key."=".urlencode( $value );
				}
			}
			$queryString = join( "&", $tempString );
		}
		$this->useCurl = $this->useCurl && in_array( "curl", get_loaded_extensions( ) );
		if ( $this->method == "GET" && isset( $queryString ) )
		{
			$this->target = $this->target."?".$queryString;
		}
		$urlParsed = parse_url( $this->target );
		if ( $urlParsed['scheme'] == "https" )
		{
			$this->host = "ssl://".$urlParsed['host'];
			$this->port = $this->port != 0 ? $this->port : 443;
		}
		else
		{
			$this->host = $urlParsed['host'];
			$this->port = $this->port != 0 ? $this->port : 80;
		}
		$this->path = ( isset( $urlParsed['path'] ) ? $urlParsed['path'] : "/" ).( isset( $urlParsed['query'] ) ? "?".$urlParsed['query'] : "" );
		$this->schema = $urlParsed['scheme'];
		$this->_passcookies( );
		if ( is_array( $this->cookies ) && 0 < count( $this->cookies ) )
		{
			$tempString = array( );
			foreach ( $this->cookies as $key => $value )
			{
				if ( 0 < strlen( trim( $value ) ) )
				{
					$tempString[] = $key."=".urlencode( $value );
				}
			}
			$cookieString = join( "&", $tempString );
		}
		if ( $this->useCurl )
		{
			$ch = curl_init( );
			if ( $this->method == "GET" )
			{
				curl_setopt( $ch, CURLOPT_HTTPGET, TRUE );
				curl_setopt( $ch, CURLOPT_POST, FALSE );
			}
			else
			{
				if ( isset( $queryString ) )
				{
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $queryString );
				}
				curl_setopt( $ch, CURLOPT_POST, TRUE );
				curl_setopt( $ch, CURLOPT_HTTPGET, FALSE );
			}
			if ( $this->username && $this->password )
			{
				curl_setopt( $ch, CURLOPT_USERPWD, $this->username.":".$this->password );
			}
			if ( $this->useCookie && isset( $cookieString ) )
			{
				curl_setopt( $ch, CURLOPT_COOKIE, $cookieString );
			}
			curl_setopt( $ch, CURLOPT_HEADER, TRUE );
			curl_setopt( $ch, CURLOPT_NOBODY, FALSE );
			curl_setopt( $ch, CURLOPT_COOKIEJAR, $this->cookiePath );
			curl_setopt( $ch, CURLOPT_TIMEOUT, $this->timeout );
			curl_setopt( $ch, CURLOPT_USERAGENT, $this->userAgent );
			curl_setopt( $ch, CURLOPT_URL, $this->target );
			curl_setopt( $ch, CURLOPT_REFERER, $this->referrer );
			curl_setopt( $ch, CURLOPT_VERBOSE, FALSE );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, $this->redirect );
			curl_setopt( $ch, CURLOPT_MAXREDIRS, $this->maxRedirect );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			$content = curl_exec( $ch );
			$contentArray = explode( "\r\n\r\n", $content );
			$status = curl_getinfo( $ch );
			$resHeader = array_shift( $contentArray );
			$this->result = implode( $contentArray, "\r\n\r\n" );
			$this->_parseheaders( $resHeader );
			$this->_seterror( curl_error( $ch ) );
			curl_close( $ch );
		}
		else
		{
			$filePointer = fsockopen( $this->host, $this->port, $errorNumber, $errorString, $this->timeout );
			if ( !$filePointer )
			{
				$this->_seterror( "Failed opening http socket connection: ".$errorString." (".$errorNumber.")" );
				return FALSE;
			}
			$requestHeader = $this->method." ".$this->path."  HTTP/1.1\r\n";
			$requestHeader .= "Host: ".$urlParsed['host']."\r\n";
			$requestHeader .= "User-Agent: ".$this->userAgent."\r\n";
			$requestHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
			if ( $this->useCookie && $cookieString != "" )
			{
				$requestHeader .= "Cookie: ".$cookieString."\r\n";
			}
			if ( $this->method == "POST" )
			{
				$requestHeader .= "Content-Length: ".strlen( $queryString )."\r\n";
			}
			if ( $this->referrer != "" )
			{
				$requestHeader .= "Referer: ".$this->referrer."\r\n";
			}
			if ( $this->username && $this->password )
			{
				$requestHeader .= "Authorization: Basic ".base64_encode( $this->username.":".$this->password )."\r\n";
			}
			$requestHeader .= "Connection: close\r\n\r\n";
			if ( $this->method == "POST" )
			{
				$requestHeader .= $queryString;
			}
			fwrite( $filePointer, $requestHeader );
			$responseHeader = "";
			$responseContent = "";
			do
			{
				$responseHeader .= fread( $filePointer, 1 );
			} while ( !preg_match( "/\\r\\n\\r\\n\$/", $responseHeader ) );
			$this->_parseheaders( $responseHeader );
			if ( $this->status == "302" && $this->redirect == TRUE )
			{
				if ( $this->curRedirect < $this->maxRedirect )
				{
					$newUrlParsed = parse_url( $this->headers['location'] );
					if ( $newUrlParsed['host'] )
					{
						$newTarget = $this->headers['location'];
					}
					else
					{
						$newTarget = $this->schema."://".$this->host."/".$this->headers['location'];
					}
					$this->port = 0;
					$this->status = 0;
					$this->params = array( );
					$this->method = "GET";
					$this->referrer = $this->target;
					++$this->curRedirect;
					$this->result = $this->execute( $newTarget );
				}
				else
				{
					$this->_seterror( "Too many redirects." );
					return FALSE;
				}
			}
			else
			{
				if ( $this->headers['transfer-encoding'] != "chunked" )
				{
					while ( !feof( $filePointer ) )
					{
						$responseContent .= fgets( $filePointer, 128 );
					}
				}
				else
				{
					while ( $chunkLength = hexdec( fgets( $filePointer ) ) )
					{
						$responseContentChunk = "";
						$readLength = 0;
						while ( $readLength < $chunkLength )
						{
							$responseContentChunk .= fread( $filePointer, $chunkLength - $readLength );
							$readLength = strlen( $responseContentChunk );
						}
						$responseContent .= $responseContentChunk;
						fgets( $filePointer );
					}
				}
				$this->result = chop( $responseContent );
			}
		}
		return $this->result;
	}

	function _parseheaders( $responseHeader )
	{
		$headers = explode( "\r\n", $responseHeader );
		$this->_clearheaders( );
		if ( $this->status == 0 )
		{
			if ( !eregi( $match = "^http/[0-9]+\\.[0-9]+[ \t]+([0-9]+)[ \t]*(.*)\$", $headers[0], $matches ) )
			{
				$this->_seterror( "Unexpected HTTP response status" );
				return FALSE;
			}
			$this->status = $matches[1];
			array_shift( $headers );
		}
		foreach ( $headers as $header )
		{
			$headerName = strtolower( $this->_tokenize( $header, ":" ) );
			$headerValue = trim( chop( $this->_tokenize( "\r\n" ) ) );
			if ( isset( $this->headers[$headerName] ) )
			{
				if ( gettype( $this->headers[$headerName] ) == "string" )
				{
					$this->headers[$headerName] = array(
						$this->headers[$headerName]
					);
				}
				$this->headers[$headerName][] = $headerValue;
			}
			else
			{
				$this->headers[$headerName] = $headerValue;
			}
		}
		if ( $this->saveCookie && isset( $this->headers['set-cookie'] ) )
		{
			$this->_parsecookie( );
		}
	}

	function _clearheaders( )
	{
		$this->headers = array( );
	}

	function _parsecookie( )
	{
		if ( gettype( $this->headers['set-cookie'] ) == "array" )
		{
			$cookieHeaders = $this->headers['set-cookie'];
		}
		else
		{
			$cookieHeaders = array(
				$this->headers['set-cookie']
			);
		}
		$cookie = 0;
		for ( ;	$cookie < count( $cookieHeaders );	++$cookie	)
		{
			$cookieName = trim( $this->_tokenize( $cookieHeaders[$cookie], "=" ) );
			$cookieValue = $this->_tokenize( ";" );
			$urlParsed = parse_url( $this->target );
			$domain = $urlParsed['host'];
			$secure = "0";
			$path = "/";
			$expires = "";
		default :
			switch ( $name )
			{
				while ( ( $name = trim( urldecode( $this->_tokenize( "=" ) ) ) ) != "" )
				{
					$value = urldecode( $this->_tokenize( ";" ) );
				case "path" :
					$path = $value;
					continue;
				case "domain" :
					$domain = $value;
					continue;
				case "secure" :
					$secure = $value != "" ? "1" : "0";
				}
			}
			$this->_setcookie( $cookieName, $cookieValue, $expires, $path, $domain, $secure );
		}
	}

	function _setcookie( $name, $value, $expires = "", $path = "/", $domain = "", $secure = 0 )
	{
		if ( strlen( $name ) == 0 )
		{
			return $this->_seterror( "No valid cookie name was specified." );
		}
		if ( strlen( $path ) == 0 || strcmp( $path[0], "/" ) )
		{
			return $this->_seterror( $path." is not a valid path for setting cookie {$name}." );
		}
		if ( !( $domain == "" ) )
		{
			if ( !strpos( $domain, ".", $domain[0] == "." ? 1 : 0 ) )
			{
			}
		}
		else
		{
			return $this->_seterror( $domain." is not a valid domain for setting cookie {$name}." );
		}
		$domain = strtolower( $domain );
		if ( !strcmp( $domain[0], "." ) )
		{
			$domain = substr( $domain, 1 );
		}
		$name = $this->_encodecookie( $name, true );
		$value = $this->_encodecookie( $value, false );
		$secure = intval( $secure );
		$this->_cookies[] = array(
			"name" => $name,
			"value" => $value,
			"domain" => $domain,
			"path" => $path,
			"expires" => $expires,
			"secure" => $secure
		);
	}

	function _encodecookie( $value, $name )
	{
		if ( $name )
		{
			return str_replace( "=", "%25", $value );
		}
		return str_replace( ";", "%3B", $value );
	}

	function _passcookies( )
	{
		if ( is_array( $this->_cookies ) && 0 < count( $this->_cookies ) )
		{
			$urlParsed = parse_url( $this->target );
			$tempCookies = array( );
			foreach ( $this->_cookies as $cookie )
			{
				if ( !$this->_domainmatch( $urlParsed['host'], $cookie['domain'] ) && !( 0 === strpos( $urlParsed['path'], $cookie['path'] ) ) && !empty( $cookie['secure'] ) || !( $urlParsed['protocol'] == "https" ) )
				{
					$tempCookies[$cookie['name']][strlen( $cookie['path'] )] = $cookie['value'];
				}
			}
			foreach ( $tempCookies as $name => $values )
			{
				krsort( $values );
				foreach ( $values as $value )
				{
					$this->addcookie( $name, $value );
				}
			}
		}
	}

	function _domainmatch( $requestHost, $cookieDomain )
	{
		if ( "." != $cookieDomain[0] )
		{
			return $requestHost == $cookieDomain;
		}
		if ( substr_count( $cookieDomain, "." ) < 2 )
		{
			return false;
		}
		return substr( ".".$requestHost, 0 - strlen( $cookieDomain ) ) == $cookieDomain;
	}

	function _tokenize( $string, $separator = "" )
	{
		if ( !strcmp( $separator, "" ) )
		{
			$separator = $string;
			$string = $this->nextToken;
		}
		$character = 0;
		for ( ;	$character < strlen( $separator );	++$character	)
		{
			if ( gettype( $position = strpos( $string, $separator[$character] ) ) == "integer" )
			{
				$found = isset( $found ) ? min( $found, $position ) : $position;
			}
		}
		if ( isset( $found ) )
		{
			$this->nextToken = substr( $string, $found + 1 );
			return substr( $string, 0, $found );
		}
		$this->nextToken = "";
		return $string;
	}

	function _seterror( $error )
	{
		if ( $error != "" )
		{
			$this->error = $error;
			return $error;
		}
	}

}

?>
