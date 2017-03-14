<?php
namespace Think;

/**
 * Widget抽象类
 */
abstract class Widget
{
	protected static $rand = 1;		// 自增数，一个页面挂多个此Widget用到，区分前端多个ID用

	/**
	 * 渲染输出 render方法是Widget唯一的接口
	 * 使用字符串返回，不能有任何输出
	 *
	 * @param array $data 要渲染的数据
	 * @return string
	 */
	abstract public function render($data);

	/**
	 * 渲染视图输出，供render方法内部调用
	 *
	 * @param array $vars 视图变量
	 * @param string $viewFile 视图文件
	 * @return string
	 */
	protected function renderView($vars = '', $viewFile = '')
	{
		// 计算视图文件路径
		if (isset($vars['viewFile']) && empty($viewFile))
		{
			$viewFile = $vars['viewFile'];
		}

		if (strrchr($viewFile, '.') != VIEW_EXT)
		{
			$classFullName = get_class($this);
			$tail = strrchr($classFullName, '\\');
			$widgetPath = SITE_PATH . '/' . str_replace(array($tail, '\\',), array('', '/'), $classFullName);
			$viewFile = $viewFile ? $viewFile : lcfirst(str_replace(array('\\', 'Widget'), '', $tail));
			$viewFile = $widgetPath . '/' . $viewFile . VIEW_EXT;
		}

		// 自增
		$vars['rand'] = self::$rand++;

		return render($viewFile, $vars);
	}
}