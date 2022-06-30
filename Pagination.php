<?php namespace Motokraft\Pagination;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/pagination
 */

use \Motokraft\HtmlElement\HtmlElement;
use \Motokraft\Object\Traits\ObjectTrait;
use \Motokraft\Pagination\Renderer\BaseRenderer;
use \Motokraft\Pagination\Renderer\RendererInterface;
use \Motokraft\Pagination\Exception\RendererNotFound;
use \Motokraft\Pagination\Exception\RendererClassNotFound;
use \Motokraft\Pagination\Exception\RendererImplement;
use \Motokraft\Pagination\Exception\RendererExtends;

class Pagination
{
	use ObjectTrait;

	private static $renderers = [];

	private $limitstart = 0;
	private $limit = 0;
	private $total = 0;
	private $pageStart = 0;
	private $pageStop = 0;
	private $pageDisplay = 0;
	private $pageTotal = 0;
	private $pageCurrent = 0;

	function __construct(int $total, int $limitstart, int $limit, int $display = 5)
	{
		$this->total = (int) $total;
		$this->limitstart = (int) max($limitstart, 0);
		$this->limit = (int) max($limit, 0);
		$this->pageDisplay = (int) max($display, 5);

		if ($this->limit > $this->total)
		{
			$this->limitstart = 0;
		}

		if (!$this->limit)
		{
			$this->limit = $total;
			$this->limitstart = 0;
		}

		if ($this->limitstart > $this->total - $this->limit)
		{
			$page = (ceil($this->total / $this->limit) - 1);
			$this->limitstart = max(0, (int) $page * $this->limit);
		}

		if ($this->limit > 0)
		{
			$this->pageTotal = (int) ceil($this->total / $this->limit);
			$this->pageCurrent = (int) ceil(($this->limitstart + 1) / $this->limit);
		}

		$this->pageStart = ceil($this->pageCurrent - ($this->pageDisplay / 2));

		if ($this->pageStart < 1)
		{
			$this->pageStart = 1;
		}

		if ($this->pageStart + $this->pageDisplay > $this->pageTotal)
		{
			$this->pageStop = $this->pageTotal;

			if ($this->pageTotal < $this->pageDisplay)
			{
				$this->pageStart = 1;
			}
			else
			{
				$this->pageStart = $this->pageTotal - $this->pageDisplay + 1;
			}
		}
		else
		{
			$this->pageStop = $this->pageStart + $this->pageDisplay - 1;
		}
	}

	static function addRenderer(string $name, string $class)
    {
        self::$renderers[$name] = $class;
    }

    static function getRenderer(string $name) : bool|string
    {
        if(!self::hasRenderer($name))
        {
            return false;
        }

        return self::$renderers[$name];
    }

    static function removeRenderer(string $name) : bool
    {
        if(!self::hasRenderer($name))
        {
            return false;
        }

        unset(self::$renderers[$name]);
        return true;
    }

    static function hasRenderer(string $name) : bool
    {
        return isset(self::$renderers[$name]);
    }

	function getLimitStart() : int
	{
		return (int) $this->get('limitstart');
	}

	function getLimit() : int
	{
		return (int) $this->get('limit');
	}

    function getTotal() : int
	{
		return (int) $this->get('total');
	}

	function getPageStart() : int
	{
		return (int) $this->get('pageStart');
	}

	function getPageStop() : int
	{
		return (int) $this->get('pageStop');
	}

	function getPageDisplay() : int
	{
		return (int) $this->get('pageDisplay');
	}

	function getPageTotal() : int
	{
		return (int) $this->get('pageTotal');
	}

	function getPageCurrent() : int
	{
		return (int) $this->get('pageCurrent');
	}

	function render(HtmlElement $element,
        string $name, array $options = []) : HtmlElement
	{
        $renderer = $this->_loadRenderer($name);

		if(!empty($options))
		{
			$renderer->loadArray($options);
		}

        return $renderer->render($element);
	}

	private function _loadRenderer(string $name) : RendererInterface
	{
		if(!$renderer = self::getRenderer($name))
        {
            throw new RendererNotFound($name, $this);
        }

        if(!class_exists($renderer))
        {
            throw new RendererClassNotFound($renderer, $this);
        }

        $renderer = new $renderer($this);

        if(!$renderer instanceof RendererInterface)
        {
            throw new RendererImplement($renderer, $this);
        }

        if(!$renderer instanceof BaseRenderer)
        {
            throw new RendererExtends($renderer, $this);
        }

        return $renderer;
	}
}