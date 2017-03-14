<?php
namespace Think;

defined('SITE_PATH') or die('Access Denied');

/**
 * 分页类
 *
 * @author blog.snsgou.com
 */
class Page
{
	public $totalRows = 0;		// 总记录数
	public $pageSize = 10;		// 每页记录数
	public $curPage = 1;		// 当前分页
	public $totalPages = 0;		// 总共能显示的（有效）页数 ----> 计算出来的结果
	public $maxPages = 0;		// 最大能显示的页数（比如说，本来总共有250页，但是想让它只显示前50页）
	public $columnPages = 5;	// 分页栏显示的页数
	public $pageUrl = '';		// 分页url地址
	public $ajaxDiv = '';		// ajax显示的div
	public $toDiv = '';			// 要跳转到的div


	/**
	 * 构造函数
	 *
	 * @param int $totalRows
	 * @param int $pageSize
	 * @param int $nowPage
	 * @param string $ajaxDiv
	 * @param string $toDiv
	 * @param string $pageUrl
	 * @param int $columnPages
	 * @param int $maxPages
	 */
	public function __construct($totalRows = 0, $pageSize = 0, $nowPage = 1,
									$ajaxDiv = '', $toDiv = '', $pageUrl = '',
									$columnPages = 5, $maxPages = 0,
									$waitId = null, $loading = null, $display = null, $callback = null
								)
	{
		$this->totalRows = $totalRows;
		$this->pageSize = $pageSize;
		$this->curPage = $nowPage;
		$this->ajaxDiv = $ajaxDiv;
		$this->toDiv = $toDiv;
		$this->waitId = $waitId;
		$this->loading = $loading;
		$this->display = $display;
		$this->callback = $callback;

		// 分页url
		$this->pageUrl = $pageUrl ? $pageUrl : get_page_url();
		$this->pageUrl = preg_replace("/&?page=[^&=]+/", '', $this->pageUrl);
		$this->pageUrl = preg_replace("/\?&/", '?', $this->pageUrl);
		$this->pageUrl = preg_replace("/\?$/", '', $this->pageUrl);
		$this->pageUrl .= strpos($this->pageUrl, '?') === false ? '?' : '&';

		$this->columnPages = $columnPages;
		$this->maxPages = $maxPages;

		$this->_init();
	}

	/**
	 * 初始化
	 */
	private function _init()
	{
		global $_G;

		if (empty($this->ajaxDiv) && $_G['inAjax'])
		{
			$this->ajaxDiv = get_gpc('ajaxDiv');
		}

		if ($_G['columnPages'])
		{
			$this->columnPages = get_gpc('columnPages');
		}

		$this->totalPages = 1; // 总页数

		if ($this->totalRows > $this->pageSize)
		{
			$this->totalPages = @ceil($this->totalRows / $this->pageSize);
			$this->totalPages = ($this->maxPages && ($this->maxPages < $this->totalPages)) ? $this->maxPages : $this->totalPages;

			// 校正当前页
			$this->curPage = intval($this->curPage);
			$this->curPage = max($this->curPage, 1);
			$this->curPage = min($this->curPage, $this->totalPages);
		}
	}

	/**
	 * 显示分页
	 */
	public function show()
	{
		global $_G;

		$pageStr = '';

		if ($this->totalRows > $this->pageSize)
		{
			$offset = @ceil($this->columnPages / 2) - 1;

			if ($this->totalPages < $this->columnPages)
			{
				$from = 1;
				$to = $this->totalPages;
			}
			else
			{
				$from = $this->curPage - $offset;
				$to = $from + $this->columnPages - 1;
				if ($from < 1)
				{
					$from = 1;
					$to = $this->columnPages;
				}
				elseif ($to > $this->totalPages)
				{
					$from = $this->totalPages - $this->columnPages + 1;
					$to = $this->totalPages;
				}
			}

			$urlPlus = $this->toDiv ? '#' . $this->toDiv : '';

			// 首页
			if ($from > 1)
			{
				$pageStr .= '<a ';
				if ($_G['inAjax'])
				{
					$pageStr .= 'href="javascript:;" onClick="ajaxGet(\'' . $this->pageUrl . 'page=1&ajaxDiv=' . $this->ajaxDiv . '\', \''
						. $this->ajaxDiv .  '\', \''
						. $this->waitId . '\', \''
						. $this->loading . '\', \''
						. $this->display . '\', \''
						. $this->callback . '\')"';
				}
				else
				{
					$pageStr .= 'href="' . $this->pageUrl . 'page=1' . $urlPlus . '"';
				}
				$pageStr .= ' class="first" title="首页">1 ...</a>';
			}

			// 上一页
			if ($this->curPage > 1)
			{
				$pageStr .= '<a ';
				if ($_G['inAjax'])
				{
					$pageStr .= 'href="javascript:;" onClick="ajaxGet(\'' . $this->pageUrl . 'page=' . ($this->curPage - 1) . '&ajaxDiv=' . $this->ajaxDiv . '\', \''
						. $this->ajaxDiv . '\', \''
						. $this->waitId . '\', \''
						. $this->loading . '\', \''
						. $this->display . '\', \''
						. $this->callback . '\')"';
				}
				else
				{
					$pageStr .= 'href="' . $this->pageUrl . 'page=' . ($this->curPage - 1) . $urlPlus .'"';
				}
				$pageStr .= ' class="prev" title="上一页">上一页</a>';
			}

			// 中间数字页
			for ($i = $from; $i <= $to; $i++)
			{
				if ($i == $this->curPage)
				{
					$pageStr .= '<a class="current"><strong title="第' . $i . '页">' . $i . '</strong></a>';
				}
				else
				{
					$pageStr .= '<a ';
					if ($_G['inAjax'])
					{
						$pageStr .= 'href="javascript:;" onClick="ajaxGet(\'' . $this->pageUrl . 'page=' . $i . '&ajaxDiv=' . $this->ajaxDiv . '\', \''
							. $this->ajaxDiv .  '\', \''
							. $this->waitId . '\', \''
							. $this->loading . '\', \''
							. $this->display . '\', \''
							. $this->callback . '\')"';
					}
					else
					{
						$pageStr .= 'href="' . $this->pageUrl . 'page=' . $i . $urlPlus . '"';
					}
					$pageStr .= ' title="第' . $i . '页">' . $i . '</a>';
				}
			}

			// 下一页
			if ($this->curPage < $this->totalPages)
			{
				$pageStr .= "<a ";
				if ($_G['inAjax'])
				{
					$pageStr .= 'href="javascript:;" onClick="ajaxGet(\'' . $this->pageUrl . 'page=' . ($this->curPage + 1) . '&ajaxDiv=' . $this->ajaxDiv . '\', \''
						. $this->ajaxDiv .  '\', \''
						. $this->waitId . '\', \''
						. $this->loading . '\', \''
						. $this->display . '\', \''
						. $this->callback . '\')"';
				}
				else
				{
					$pageStr .= 'href="' . $this->pageUrl . 'page=' . ($this->curPage + 1) . $urlPlus . '"';
				}
				$pageStr .= ' class="next" title="下一页">下一页</a>';
			}

			// 尾页
			if ($to < $this->totalPages)
			{
				$pageStr .= "<a ";
				if ($_G['inAjax'])
				{
					$pageStr .= 'href="javascript:;" onClick="ajaxGet(\'' . $this->pageUrl . 'page=' . $this->totalPages . '&ajaxDiv=' . $this->ajaxDiv . '\', \''
						. $this->ajaxDiv .  '\', \''
						. $this->waitId . '\', \''
						. $this->loading . '\', \''
						. $this->display . '\', \''
						. $this->callback . '\')"';
				}
				else
				{
					$pageStr .= 'href="' . $this->pageUrl . 'page=' . $this->totalPages . $urlPlus . '"';
				}
				$pageStr .= ' class="last" title="尾页">... ' . $this->totalPages . '</a>';
			}

			// 总记录数
			if ($pageStr)
			{
				$pageStr = '<div class="page">' . $pageStr . '</div>';
			}
		}

		return $pageStr;
	}
}