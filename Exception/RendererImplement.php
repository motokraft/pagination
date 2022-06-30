<?php namespace Motokraft\Pagination\Exception;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/pagination
 */

use \Motokraft\Pagination\Renderer\RendererInterface;
use \Motokraft\Pagination\Pagination;

class RendererImplement extends \Exception
{
    private $obj;
    private $pagination;

    function __construct(object $obj, Pagination $pagination, int $code = 501)
    {
        $this->obj = $obj;
        $this->pagination = $pagination;

        $text = 'The %s class must implement the %s interface';

        parent::__construct(sprintf($text,
            get_class($obj), RendererInterface::class
        ), $code);
    }

    function getObject() : object
    {
        return $this->obj;
    }

    function getPagination() : Pagination
    {
        return $this->pagination;
    }
}