<?php namespace Motokraft\Pagination\Renderer;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/pagination
 */

use \Motokraft\HtmlElement\HtmlElement;

interface RendererInterface
{
    function render(HtmlElement $element): HtmlElement;
}