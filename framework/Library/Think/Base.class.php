<?php

namespace Think;

/**
 * 业务基类
 *
 * @package framework
 */
class Base
{
	/**
	 * 类实例化（单例模式）
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public static function instance()
	{
		static $_instance = array();

		$classFullName = get_called_class();
		if (!isset($_instance[$classFullName]))
		{
			if (!class_exists($classFullName, false))
			{
				throw new \Exception('"\\' . $classFullName . '" was not found !');
			}
			$_instance[$classFullName] = new $classFullName();
		}

		return $_instance[$classFullName];
	}

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		if (method_exists($this, '_init'))
		{
			$this->_init();
		}
	}
}