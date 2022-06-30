<?php namespace Motokraft\Pagination\Exception;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/pagination
 */

use \Motokraft\Pagination\Renderer\BaseRenderer;
use \Motokraft\Pagination\Pagination;

class RendererExtends extends \Exception
{
    private $obj;
    private $pagination;

    function __construct(object $obj, Pagination $pagination, int $code = 510)
    {
        $this->obj = $obj;
        $this->pagination = $pagination;

        $text = 'Class %s must be extends from %s';

        parent::__construct(sprintf($text,
            get_class($obj), BaseRenderer::class
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