<?php namespace Motokraft\Pagination\Exception;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/pagination
 */

use \Motokraft\Pagination\Pagination;

class RendererNotFound extends \Exception
{
    private $name;
    private $pagination;

    function __construct(string $name, Pagination $pagination, int $code = 404)
    {
        $this->name = $name;
        $this->pagination = $pagination;

        $text = 'Renderer name "%s" not found!';
        $message = sprintf($text, $name);

        parent::__construct($message, $code);
    }

    function getName() : string
    {
        return $this->name;
    }

    function getPagination() : Pagination
    {
        return $this->pagination;
    }
}