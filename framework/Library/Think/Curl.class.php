<?php
namespace Think;

/**
 * cURL请求类
 * 对PHP函数 curl_init(), curl_exec(), curl_getinfo(), curl_errno(), curl_error(), curl_close() 等的简单封装
 * 用 RestClient类对Curl类再次包装一下
 */
class Curl
{
	protected $response = '';		// 请求执行的结果，成功时返回 TRUE，失败时返回 FALSE
	protected $session = null;		// 请求的curl处理对象，即 cURL 句柄
	protected $url = '';			// 请求的url
	protected $headers = array();	// 请求头选项值
	protected $options = array();	// 请求选项值
	public $errorCode = '';			// 请求错误时，返回的code值
	public $errorStr = '';			// 请求错误时，返回的字符串值
	public $info = array();			// 获取最后一次传输的相关信息（数组信息）

	/**
	 * 获取类实例
	 */
	public static function &getInstance()
	{
		static $_instance;
		if (empty($_instance))
		{
			$_instance = new self();
		}

		return $_instance;
	}

	/**
	 * 构造函数
	 *
	 * @param string $url 请求url
	 */
	public function __construct($url = '')
	{
		if (!$this->isEnabled())
		{
			throw new \Exception('PHP was not built with cURL enabled, Rebuild PHP with --with-curl to use cURL.');
		}

		$url && $this->create($url);
	}

	/**
	 * 简单请求方法调用
	 *
	 * @param string $method 方法名，如 'simpleGet', 'simplePost', 'simplePut', 'simpleDelete', 'simplePatch'
	 * @param array $arguments 方法参数
	 * @return mixed
	 */
	public function __call($method, $arguments)
	{
		if (in_array($method, array('simpleGet', 'simplePost', 'simplePut', 'simpleDelete', 'simplePatch')))
		{
			// 去掉方法前缀 "simple" 并且传递 get/post/put/delete/patch 到方法 _simple_call()
			$verb = str_replace('simple', '', $method);
			array_unshift($arguments, $verb);

			return call_user_func_array(array($this, '_simpleCall'), $arguments);
		}
	}

	/**
	 * 简单请求方法调用
	 * 使用这些方法，你可以用一行代码快速发起curl请求
	 *
	 * @param string $method 请求方法
	 * @param string $url 请求url
	 * @param array $params|string 请求参数
	 * @param array $options 请求选项
	 */
	public function _simpleCall($method, $url, $params = array(), $options = array())
	{
		if ($method === 'get')
		{
			$this->create($url . ($params ? '?' . http_build_query($params, null, '&') : ''));
		}
		else
		{
			$this->create($url);
			$this->{$method}($params);
		}

		// 添加指定的 options参数
		$this->setOptions($options);

		return $this->execute();
	}

	/**
	 * 简单的 ftp get 请求
	 *
	 * @param string $url 请求url
	 * @param string $filePath 文件路径
	 * @param string $username 用户名
	 * @param string $password 用户密码
	 * @return bool|mixed|string
	 */
	public function simpleFtpGet($url, $filePath, $username = '', $password = '')
	{
		// 假如 没有提供 ftp:// 等协议，则默认追加 ftp://
		if (!preg_match('!^(ftp|sftp)://! i', $url))
		{
			$url = 'ftp://' . $url;
		}

		// 使用 ftp 登录
		if ($username != '')
		{
			$authStr = $username;

			if ($password != '')
			{
				$authStr .= ':' . $password;
			}

			// 添加 用户授权字符串到协议的后面
			$url = str_replace('://', '://' . $authStr . '@', $url);
		}

		// 添加文件路径
		$url .= $filePath;

		$this->setOption(CURLOPT_BINARYTRANSFER, true);
		$this->setOption(CURLOPT_VERBOSE, true);

		return $this->execute();
	}

	/**
	 * post请求（添加）
	 *
	 * @param array|string $params 请求参数
	 * @param $options 请求选项
	 */
	public function post($params = array(), $options = array())
	{
		if (is_array($params))
		{
			$params = http_build_query($params, null, '&');
		}

		// 添加 options值
		$this->setOptions($options);

		$this->setHttpMethod('post');

		$this->setOption(CURLOPT_POST, true);
		$this->setOption(CURLOPT_POSTFIELDS, $params);
	}

	/**
	 * put请求（修改）
	 *
	 * @param array|string $params 请求参数
	 * @param array $options 请求选项
	 */
	public function put($params = array(), $options = array())
	{
		if (is_array($params))
		{
			$params = http_build_query($params, null, '&');
		}

		// 添加 options值
		$this->setOptions($options);

		$this->setHttpMethod('put');
		$this->setOption(CURLOPT_POSTFIELDS, $params);

		// 重写方法，用 $_POST 代替 PUT 数据
		$this->setOption(CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
	}

	/**
	 * patch 请求
	 *
	 * @param array|string $params 请求参数
	 * @param array $options
	 */
	public function patch($params = array(), $options = array())
	{
		if (is_array($params))
		{
			$params = http_build_query($params, null, '&');
		}

		// 添加 options值
		$this->setOptions($options);

		$this->setHttpMethod('patch');
		$this->setOption(CURLOPT_POSTFIELDS, $params);

		// 重写方法，用 $_POST 代替 PATCH 数据
		$this->setOption(CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PATCH'));
	}

	/**
	 * delelte 请求
	 *
	 * @param array|string $params 请求参数
	 * @param array $options 请求选项
	 */
	public function delete($params = array(), $options = array())
	{
		if (is_array($params))
		{
			$params = http_build_query($params, null, '&');
		}

		// 添加 options值
		$this->setOptions($options);

		$this->setHttpMethod('delete');
		$this->setOption(CURLOPT_POSTFIELDS, $params);
	}

	/**
	 * 设置cookie
	 *
	 * @param array|string $params cookie数组
	 * @return $this
	 */
	public function setCookies($params = array())
	{
		if (is_array($params))
		{
			$params = http_build_query($params, null, '&');
		}

		$this->setOption(CURLOPT_COOKIE, $params);

		return $this;
	}

	/**
	 * 设置请求头
	 *
	 * @param string $header 请求头
	 * @param string $content 头内容
	 * @return $this
	 */
	public function setHttpHeader($header, $content = null)
	{
		$this->headers[] = $content ? $header . ': ' . $content : $header;
		return $this;
	}

	/**
	 * 设置http请求方式
	 *
	 * @param string $method 请求方式
	 * @return $this
	 */
	public function setHttpMethod($method)
	{
		$this->options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
		return $this;
	}

	/**
	 * 设置http登录
	 *
	 * @param string $username 用户名
	 * @param string $password 用户秘密
	 * @param string $type 授权方式
	 * @return $this
	 */
	public function httpLogin($username = '', $password = '', $type = 'any')
	{
		$this->setOption(CURLOPT_HTTPAUTH, constant('CURLAUTH_' . strtoupper($type)));
		$this->setOption(CURLOPT_USERPWD, $username . ':' . $password);

		return $this;
	}

	/**
	 * 设置代理
	 *
	 * @param string $url 代理url
	 * @param int $port 代理端口
	 * @return $this
	 */
	public function proxy($url = '', $port = 80)
	{
		$this->setOption(CURLOPT_HTTPPROXYTUNNEL, true);
		$this->setOption(CURLOPT_PROXY, $url . ':' . $port);

		return $this;
	}

	/**
	 * 代理登录
	 *
	 * @param string $username 用户名
	 * @param string $password 用户密码
	 * @return $this
	 */
	public function proxyLogin($username = '', $password = '')
	{
		$this->setOption(CURLOPT_PROXYUSERPWD, $username . ':' . $password);
		return $this;
	}

	/**
	 * 设置SSL
	 *
	 * @param bool $verifyPeer
	 * @param int $verifyHost
	 * @param string $pathToCert
	 * @return $this
	 */
	public function ssl($verifyPeer = true, $verifyHost = 2, $pathToCert = null)
	{
		if ($verifyPeer)
		{
			$this->setOption(CURLOPT_SSL_VERIFYPEER, true);
			$this->setOption(CURLOPT_SSL_VERIFYHOST, $verifyHost);
			if (isset($pathToCert))
			{
				$pathToCert = realpath($pathToCert);
				$this->setOption(CURLOPT_CAINFO, $pathToCert);
			}
		}
		else
		{
			$this->setOption(CURLOPT_SSL_VERIFYPEER, false);
		}

		return $this;
	}

	/**
	 * 批量设置curl的option
	 *
	 * @param array $options 选项
	 * @return $this
	 */
	public function setOptions($options = array())
	{
		foreach ($options as $option_code => $option_value)
		{
			$this->setOption($option_code, $option_value);
		}

		curl_setopt_array($this->session, $this->options);

		return $this;
	}

	/**
	 * 设置curl的option
	 *
	 * @param string $code 选项名
	 * @param string $value 选项值
	 * @param string $prefix 选项名前缀
	 * @return $this
	 */
	public function setOption($code, $value, $prefix = 'opt')
	{
		if (is_string($code) && !is_numeric($code))
		{
			$code = constant('CURL' . strtoupper($prefix) . '_' . strtoupper($code));
		}

		$this->options[$code] = $value;

		return $this;
	}

	/**
	 * 创建一个新的curl请求
	 *
	 * @param string $url 请求url
	 */
	public function create($url)
	{
		$this->url = $url;
		$this->session = curl_init($this->url);

		return $this;
	}

	/**
	 * 执行请求，并返回结果
	 *
	 * @return bool|mixed|string
	 */
	public function execute()
	{
		// 设置默认值
		if (!isset($this->options[CURLOPT_TIMEOUT]))
		{
			$this->options[CURLOPT_TIMEOUT] = 30; // 超时时间
		}
		if (!isset($this->options[CURLOPT_RETURNTRANSFER]))
		{
			$this->options[CURLOPT_RETURNTRANSFER] = true;
		}
		if (!isset($this->options[CURLOPT_FAILONERROR]))
		{
			$this->options[CURLOPT_FAILONERROR] = true;
		}

		// 非安全模式下，设置下以下选项
		if (!ini_get('safe_mode') && !ini_get('open_basedir'))
		{
			// 如果 选项follow location 没有被设置，则设置为 true
			if (!isset($this->options[CURLOPT_FOLLOWLOCATION]))
			{
				$this->options[CURLOPT_FOLLOWLOCATION] = true;
			}
		}

		if (!empty($this->headers))
		{
			// 批量设置http头信息
			$this->setOption(CURLOPT_HTTPHEADER, $this->headers);
		}

		// 批量设置 curl 的 option 选项值
		$this->setOptions();

		// 执行请求 并 返回结果
		// >> 成功时返回 TRUE， 或者在失败时返回 FALSE。
		// >> 然而，如果 CURLOPT_RETURNTRANSFER 选项被设置，函数执行成功时会返回执行的结果，失败时返回 FALSE 。
		$this->response = curl_exec($this->session);

		// 获取最后一次传输的相关信息
		$this->info = curl_getinfo($this->session);

		// 请求失败
		if ($this->response === false)
		{
			$errno = curl_errno($this->session);
			$error = curl_error($this->session);

			curl_close($this->session);
			$this->setDefaults();

			$this->errorCode = $errno;
			$this->errorStr = $error;

			return false;
		}
		else // 请求成功，返回执行的结果
		{
			curl_close($this->session);
			$this->lastResponse = $this->response;
			$this->setDefaults();

			return $this->lastResponse;
		}
	}

	/**
	 * 判断 curl 是否可用
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return function_exists('curl_init');
	}

	/**
	 * 请求后调试
	 */
	public function debug()
	{
		echo "=============================================<br/>\n";
		echo "<h2>CURL Test</h2>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Response</h3>\n";
		echo "<code>" . nl2br(htmlentities($this->lastResponse)) . "</code><br/>\n\n";

		if ($this->errorStr)
		{
			echo "=============================================<br/>\n";
			echo "<h3>Errors</h3>";
			echo "<strong>Code:</strong> " . $this->errorCode . "<br/>\n";
			echo "<strong>Message:</strong> " . $this->errorStr . "<br/>\n";
		}

		echo "=============================================<br/>\n";
		echo "<h3>Info</h3>";
		echo "<pre>";
		print_r($this->info);
		echo "</pre>";
	}

	/**
	 * 请求前调试
	 *
	 * @return array
	 */
	public function debugRequest()
	{
		return array(
			'url' => $this->url
		);
	}

	/**
	 * 清空数据
	 */
	public function setDefaults()
	{
		$this->response = '';
		$this->headers = array();
		$this->options = array();
		$this->errorCode = null;
		$this->errorStr = '';
		$this->session = null;
	}
}