<?php namespace Motokraft\Pagination\Renderer;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/pagination
 */

use \Motokraft\Pagination\Pagination;
use \Motokraft\Pagination\PaginationItem;
use \Motokraft\Pagination\PaginationData;
use \Motokraft\Object\Traits\ObjectTrait;

abstract class BaseRenderer
{
	use ObjectTrait;

    private $pagination;

    function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    function getPagination() : Pagination
    {
        return $this->pagination;
    }

    protected function _prepareFirstItem() : PaginationItem
    {
        $padding = $this->getPagination();
		$current = $padding->getPageCurrent();

		return new PaginationItem('first', [
			'page' => 0, 'actrive' => false,
			'disabled' => ($current <= 1)
		]);
    }

	protected function _preparePrevItem() : PaginationItem
	{
		$padding = $this->getPagination();
		$current = $padding->getPageCurrent();

		$result = new PaginationItem('prev', [
			'page' => 0, 'disabled' => true
		]);

		if((int) $current > 1)
		{
			$result->setDisabled(false);

			if(($current - 1) > 1)
			{
				$result->setPage($current - 1);
			}
		}

		return $result;
	}

	protected function _getPages() : \ArrayIterator
	{
		$result = new \ArrayIterator;
		$padding = $this->getPagination();

		$current = $padding->getPageCurrent();
		$start = (int) $padding->getPageStart();
		$stop = (int) $padding->getPageStop();

		for ($i = $start; $i <= $stop; $i++)
		{
			$item = new PaginationItem('page', [
				'active' => ($current === $i),
				'title' => $i
			]);

			if($i > 1)
			{
				$item->setPage($i);
			}

			$result->append($item);
		}

		return $result;
	}

	protected function _prepareNextItem() : PaginationItem
	{
		$padding = $this->getPagination();

		$current = $padding->getPageCurrent();
		$total = $padding->getPageTotal();

		$result = new PaginationItem('next', [
			'page' => $current + 1, 'disabled' => true
		]);

		if ((int) $current < (int) $total)
		{
			$result->setDisabled(false);
		}

		return $result;
	}

	protected function _prepareLasttItem() : PaginationItem
	{
		$padding = $this->getPagination();

		$current = $padding->getPageCurrent();
		$total = $padding->getPageTotal();

		$result = new PaginationItem('last', [
			'page' => $total, 'disabled' => true
		]);

		if ((int) $current < (int) $total)
		{
			$result->setDisabled(false);
		}

		return $result;
	}
}