<?php
namespace Common\Service;

use Think\Base;
use Think\RestClient;

/**
 * 服务基类
 *
 * @author blog.snsgou.com
 */
abstract class BaseService extends Base
{
	protected $accessToken = "";
	protected $needOAuth = 0;

	/**
	 * 获取方法键名
	 */
	protected abstract function getMethodKey($method);

	/**
	 * 获取接口URL地址
	 *
	 * @param string $methodKey 方法关键字
	 */
	protected function getServiceUrl($methodKey = '')
	{
		if (empty($GLOBALS['_G']['_service'][$methodKey]))
		{
			return array(
				'code' => 0,
				'msg' => '服务名“' . $methodKey . '”的远程地址未找到',
				'data' => ''
			);
		}

		return trim($GLOBALS['_G']['_service'][$methodKey], '/'); // 统一去掉 结尾的 “/”
	}

	/**
	 * get请求
	 *
	 * @param string $url 请求url
	 * @param array|string $params 请求参数
	 * @param string $format
	 * @param int $needOAuth 是否需要授权
	 */
	protected function doGet($url, $params = array(), $format = 'json', $needOAuth = 1)
	{
		return $this->_call('get', $url, $params, $format, $needOAuth);
	}

	/**
	 * post请求
	 *
	 * @param string $url 请求url
	 * @param array|string $params 请求参数
	 * @param string $format
	 * @param int $needOAuth 是否需要授权
	 */
	protected function doPost($url, $params = array(), $format = 'json', $needOAuth = 1)
	{
		return $this->_call('post', $url, $params, $format, $needOAuth);
	}

	/**
	 * put请求
	 *
	 * @param string $url 请求url
	 * @param array|string $params 请求参数
	 * @param string $format
	 * @param int $needOAuth 是否需要授权
	 */
	protected function doPut($url, $params = array(), $format = 'json', $needOAuth = 1)
	{
		return $this->_call('put', $url, $params, $format, $needOAuth);
	}

	/**
	 * delete请求
	 *
	 * @param string $url 请求url
	 * @param array|string $params 请求参数
	 * @param string $format
	 * @param int $needOAuth 是否需要授权
	 */
	protected function doDelete($url, $params = array(), $format = 'json', $needOAuth = 1)
	{
		return $this->_call('delete', $url, $params, $format, $needOAuth);
	}

	/**
	 * patch请求
	 *
	 * @param string $url 请求url
	 * @param array|string $params 请求参数
	 * @param string $format
	 * @param int $needOAuth 是否需要授权
	 */
	protected function doPatch($url, $params = array(), $format = 'json', $needOAuth = 1)
	{
		return $this->_call('patch', $url, $params, $format, $needOAuth);
	}

	/**
	 * 统一的 http请求
	 *
	 * @param string $method 请求方法/动作
	 * @param string $url 请求url
	 * @param array|string $params 请求参数
	 * @param string $format 返回结果格式化方式
	 * @param int $needOAuth 是否需要OAuth授权，1是 0否
	 * @return array
	 */
	protected function _call($method, $url, $params = array(), $format = 'json', $needOAuth = 1)
	{

		$response = $client->{$method}($url, $params, $format);

		// HTTP请求没有发送成功
		if ($response === false)
		{
			return array(
				'code' => false,
				'msg' => $client->getErrorStr(),
				'data' => ''
			);
		}

		// HTTP请求失败，返回错误
		if ($client->getStatus() != '200')
		{
			return array(
				'code' => false,
				'msg' => '服务器请求发生' . $clientStatus . '错误',
				'data' => ''
			);
		}

		return $response;
	}

	/**
	 * 获取oAuth的accessToken
	 *
	 * @return string $accessToken
	 */
	protected function getAccessToken()
	{
		$client = new RestClient();

		// 授权服务地址
		$accessTokenUrl = $this->getServiceUrl('oAuth.getAccessToken');

		// 授权服务请求参数
		$accessTokenParams = array(
			'grant_type' => 'client_credentials',
		);
		$accessTokenFormat = null;

		// 设置请求http头
		$client->setHttpHeader('Authorization', 'Basic ' . base64_encode(get_config('oAuth/client_id') . ':' . get_config('oAuth/client_secret')));
		$response = $client->post($accessTokenUrl, $accessTokenParams, $accessTokenFormat);

		// http请求没有发送成功
		if ($response === false)
		{
			return '';
		}

		// 没有正确请求到数据
		if ($client->getStatus() != '200')
		{
			return '';
		}

		// 没有获取到值
		if (!isset($response['access_token']) || empty($response['access_token']))
		{
			return '';
		}
		return $response['access_token'];
	}

	/**
	 * 写日志（包括 数字、字符串、数组）
	 *
	 * @param string $text 要写入的文本字符串
	 * @param string $type 文本写入类型（'w':覆盖重写，'a':文本追加）
	 */
	protected function writeLog($text, $type = 'a')
	{
		$filePath = LOG_PATH . '/service/' . date('Y.m.d') . '_service.php';

		$text = "<?php exit;?>++++++++++++++++++++++++++++++++++++++++++\r\n"
				. date('Y-m-d H:i:s') . "\r\n"
				. print_r($text, true) . "\r\n";

		return write_file($filePath, $text, $type);
	}
}