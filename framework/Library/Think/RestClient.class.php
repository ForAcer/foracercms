<?php
namespace Think;

use Think\Curl;

/**
 * RestClient 类
 */
class RestClient
{
	protected $curl; // curl处理对象

	protected $supportedFormats = array(
		'xml'		=> 'application/xml',
		'json'		=> 'application/json',
		'serialize'	=> 'application/vnd.php.serialized',
		'php'		=> 'text/plain',
		'csv'		=> 'text/csv'
	);

	protected $autoDetectFormats = array(
		'application/xml'	=> 'xml',
		'text/xml'			=> 'xml',
		'application/json'	=> 'json',
		'text/json'			=> 'json',
		'text/csv'			=> 'csv',
		'application/csv'	=> 'csv',
		'application/vnd.php.serialized' => 'serialize'
	);

	protected $restServer = '';
	protected $format = 'json';
	protected $mimeType = '';

	protected $httpAuth = null;		// 授权方式，如 basic
	protected $httpUser = null;		// 用户名
	protected $httpPass = null;		// 用户密码

	protected $apiName = null;
	protected $apiKey = null;

	protected $sslVerifyPeer = null;
	protected $sslCainfo = null;

	protected $sendCookies = null;
	protected $responseStr;

	public function __construct($config = array())
	{
		$this->curl = Curl::getInstance();
		empty($config) || $this->init($config);
	}

	public function __destruct()
	{
		$this->curl->setDefaults();
	}

	/**
	 * 初始化
	 *
	 * @example
	 * $config = array(
	 * 		'restServer' => 'http://blog.snsgou.com',	// 注意：不要以“/”结尾
	 * 		'sendCookies' => '',

	 * 		'apiName' => 'X-API-KEY',
	 * 		'apiKey' => 'Setec_Astronomy',

	 * 		'httpAuth' => 'basic',
	 * 		'httpUser' => 'username',
	 * 		'httpPass' => 'password',

	 * 		'sslVerifyPeer' => TRUE,
	 * 		'sslCainfo' => '/certs/cert.pem',
	 * 	);
	 * @param array $config 配置信息
	 */
	public function init($config)
	{
		isset($config['restServer']) && $this->restServer = trim($config['restServer'], '/'); // 去掉结尾的 “/”
		isset($config['sendCookies']) && $this->sendCookies = $config['sendCookies'];

		isset($config['apiName']) && $this->apiName = $config['apiName'];
		isset($config['apiKey']) && $this->apiKey = $config['apiKey'];

		isset($config['httpAuth']) && $this->httpAuth = $config['httpAuth'];
		isset($config['httpUser']) && $this->httpUser = $config['httpUser'];
		isset($config['httpPass']) && $this->httpPass = $config['httpPass'];

		isset($config['sslVerifyPeer']) && $this->sslVerifyPeer = $config['sslVerifyPeer'];
		isset($config['sslCainfo']) && $this->sslCainfo = $config['sslCainfo'];
	}

	/**
	 * get 请求
	 *
	 * @param string $url 请求地址
	 * @param array|string $params 请求参数
	 * @param string $format 返回结果格式化方式
	 */
	public function get($url, $params = array(), $format = NULL)
	{
		if ($params)
		{
			$url .= '?' . (is_array($params) ? http_build_query($params) : $params);
		}

		return $this->_call('get', $url, NULL, $format);
	}

	/**
	 * post 请求
	 *
	 * @param string $url 请求地址
	 * @param array|string $params 请求参数
	 * @param string $format 返回结果格式化方式
	 */
	public function post($url, $params = array(), $format = NULL)
	{
		return $this->_call('post', $url, $params, $format);
	}

	/**
	 * put 请求
	 *
	 * @param string $url 请求地址
	 * @param array|string $params 请求参数
	 * @param string $format 返回结果格式化方式
	 */
	public function put($url, $params = array(), $format = NULL)
	{
		return $this->_call('put', $url, $params, $format);
	}

	/**
	 * delete 请求
	 *
	 * @param string $url 请求地址
	 * @param array|string $params 请求参数
	 * @param string $format 返回结果格式化方式
	 */
	public function delete($url, $params = array(), $format = NULL)
	{
		return $this->_call('delete', $url, $params, $format);
	}

	/**
	 * patch 请求
	 *
	 * @param string $url 请求地址
	 * @param array $params|string 请求参数
	 * @param string $format 返回结果格式化方式
	 */
	public function patch($url, $params = array(), $format = NULL)
	{
		return $this->_call('patch', $url, $params, $format);
	}

	/**
	 * 设置Api的key和name
	 *
	 * @param string $key 接口key
	 * @param string $name 接口name
	 */
	public function setApiKey($key, $name = false)
	{
		$this->apiKey = $key;

		if ($name !== false)
		{
			$this->apiName = $name;
		}
	}

	/**
	 * 设置接受的语言头
	 *
	 * @param string $language 设置语言
	 */
	public function setLang($lang)
	{
		if (is_array($lang))
		{
			$lang = implode(', ', $lang);
		}

		$this->curl->setHttpHeader('Accept-Language', $lang); // 如：Accept-Language: zh-CN,zh;q=0.8
	}

	/**
	 * 设置请求头
	 *
	 * @param string $header 请求头，如 Connection: keep-alive
	 */
	public function setHeader($header)
	{
		$this->curl->setHttpHeader($header);
	}

	/**
	 * 统一调用方法
	 *
	 * @param string $method 动作名，如 get, post, put, delete, patch 等
	 * @param string $url 请求url
	 * @param array|string $params 请求参数
	 * @param string $format 格式化方式
	 * @return mixed
	 */
	protected function _call($method, $url, $params = array(), $format = null)
	{
		if ($format !== null)
		{
			$this->setFormat($format);
		}

		$this->setHttpHeader('Accept', $this->mimeType);

		// url整理
		$this->restServer && $url = $this->restServer . '/' . $url;
		$this->curl->create($url);

		// 如果使用了ssl，则需要设置 ssl验证信息 和 cainfo
		if ($this->sslVerifyPeer === false)
		{
			$this->curl->ssl(false);
		}
		elseif ($this->sslVerifyPeer === true)
		{
			$this->sslCainfo = getcwd() . $this->sslCainfo;
			$this->curl->ssl(true, 2, $this->sslCainfo);
		}

		// 授权访问
		if ($this->httpAuth != '' && $this->httpUser != '')
		{
			$this->curl->httpLogin($this->httpUser, $this->httpPass, $this->httpAuth);
		}

		// 使用 API Key
		if ($this->apiKey != '')
		{
			$this->curl->setHttpHeader($this->apiName, $this->apiKey);
		}

		// 发送Cookies
		if ($this->sendCookies != '')
		{
			$this->curl->setCookies($_COOKIE);
		}

		// 设置 Content-Type
		$this->setHttpHeader('Content-type', $this->mimeType);

		// 同样也显示 error code 超过 400 的请求
		$this->curl->setOption('failonerror', false);

		// 调用动作方法 和 参数
		$this->curl->{$method}($params);

		// 执行请求，返回结果
		$response = $this->curl->execute();

		if ($response === false)
		{
			return false;
		}

		// 格式化结果
		return $this->_formatResponse($response);
	}

	/**
	 * 如果传过来的 type 不是系统支持的类型，则通过 mime type 来返回类型
	 *
	 * @param string $format 返回结果的格式方式
	 */
	public function setFormat($format)
	{
		if (array_key_exists($format, $this->supportedFormats))
		{
			$this->format = $format;
			$this->mimeType = $this->supportedFormats[$format];
		}
		else
		{
			$this->mimeType = $format;
		}

		return $this;
	}

	/**
	 * 调试
	 */
	public function debug()
	{
		$request = $this->curl->debugRequest();

		echo "=============================================<br/>\n";
		echo "<h2>REST Test</h2>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Request</h3>\n";
		echo $request['url'] . "<br/>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Response</h3>\n";

		if ($this->responseStr)
		{
			echo "<code>" . nl2br(htmlentities($this->responseStr)) . "</code><br/>\n\n";
		}
		else
		{
			echo "No response<br/>\n\n";
		}

		echo "=============================================<br/>\n";

		if ($this->curl->errorStr)
		{
			echo "<h3>Errors</h3>";
			echo "<strong>Code:</strong> " . $this->curl->errorCode . "<br/>\n";
			echo "<strong>Message:</strong> " . $this->curl->errorStr . "<br/>\n";
			echo "=============================================<br/>\n";
		}

		echo "<h3>Call details</h3>";
		echo "<pre>";
		print_r($this->curl->info);
		echo "</pre>";

	}

	/**
	 * 返回请求状态吗
	 */
	public function getStatus()
	{
		return $this->getInfo('http_code');
	}

	/**
	 * 返回指定key的info信息，如果没有指定key，则返回所有信息
	 *
	 * @param string $key 信息键名
	 */
	public function getInfo($key = null)
	{
		return $key === null ? $this->curl->info : @$this->curl->info[$key];
	}

	/**
	 * 获取curl中的lastResponse，用来获取TGT
	 *
	 * @param string $key 信息键名
	 */
	public function getLastResponse()
	{
		return @$this->curl->lastResponse;
	}

	/**
	 * 获取错误码
	 */
	public function getErrorCode()
	{
		return $this->curl->errorCode;
	}

	public function getErrorStr()
	{
		return $this->curl->errorStr;
	}

	/**
	 * 设置 curl options
	 *
	 * @param string $code 选项名
	 * @param string $value 选项值
	 */
	public function setOption($code, $value)
	{
		$this->curl->setOption($code, $value);
	}

	/**
	 * 设置http请求头
	 *
	 * @param string $header 消息头
	 * @param string $content 头内容
	 */
	public function setHttpHeader($header, $content = NULL)
	{
		$params = $content ? array($header, $content) : array($header);
		call_user_func_array(array($this->curl, 'setHttpHeader'), $params);
	}

	/**
	 * 格式化返回结果
	 *
	 * @param string $response 返回字符串
	 */
	protected function _formatResponse($response)
	{
		$this->responseStr = &$response;

		if (array_key_exists($this->format, $this->supportedFormats))
		{
			return $this->{"_" . $this->format}($response);
		}

		$returnMime = @$this->curl->info['content_type'];

		if (strpos($returnMime, ';'))
		{
			list($returnMime) = explode(';', $returnMime);
		}

		$returnMime = trim($returnMime);

		if (array_key_exists($returnMime, $this->autoDetectFormats))
		{
			return $this->{'_' . $this->autoDetectFormats[$returnMime]}($response);
		}

		return $response;
	}

	/**
	 * 格式化xml输出
	 *
	 * @param string $str 输入字符串
	 */
	protected function _xml($str)
	{
		return $str ? (array)simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
	}

	/**
	 * 格式化csv输出
	 *
	 * @param string $str 输入字符串
	 */
	protected function _csv($str)
	{
		$data = array();

		$rows = explode("\n", trim($str));
		$headings = explode(',', array_shift($rows));
		foreach( $rows as $row )
		{
			// 利用 substr 去掉 开始 与 结尾 的 "
			$data_fields = explode('","', trim(substr($row, 1, -1)));
			if (count($data_fields) === count($headings))
			{
				$data[] = array_combine($headings, $data_fields);
			}
		}

		return $data;
	}

	/**
	 * 格式化json输出
	 *
	 * @param string $str
	 */
	protected function _json($str)
	{
		return json_decode(trim($str), true);
	}

	/**
	 * 反序列化输出
	 *
	 * @param string $str 输入字符串
	 */
	protected function _serialize($str)
	{
		return unserialize(trim($str));
	}

	/**
	 * 执行PHP脚本输出
	 *
	 * @param string $str
	 */
	protected function _php($str)
	{
		$str = trim($str);
		$populated = array();
		eval("\$populated = \"$str\";");

		return $populated;
	}
}