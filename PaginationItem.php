<?php namespace Motokraft\Pagination;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/pagination
 */

use \Motokraft\HtmlElement\HtmlElement;
use \Motokraft\Object\Traits\ObjectTrait;
use \Motokraft\Uri\Uri;

class PaginationItem
{
    use ObjectTrait;

    const ACTIVE = 'active';
    const DISABLED = 'disabled';

    private $name;
    private $active = false;
    private $disabled = false;
    private $title;
    private $page = 0;

    function __construct(string $name, array $data = [])
    {
        $this->setName($name);
        
        if(!empty($data))
        {
            $this->loadArray($data);
        }
    }

    function setName(string $name) : static
    {
        $this->set('name', $name);
        return $this;
    }

    function setActive(bool $state) : static
    {
        $this->set('active', (bool) $state);

        if((bool) $state === true)
        {
            $this->setDisabled(false);
        }

        return $this;
    }

    function setDisabled(bool $state) : static
    {
        $this->set('disabled', (bool) $state);

        if((bool) $state === true)
        {
            $this->setActive(false);
        }

        return $this;
    }

    function setTitle(string $title) : static
    {
        $this->set('title', $title);
        return $this;
    }

    function setPage(int $page) : static
    {
        $this->set('page', (int) $page);
        return $this;
    }

    function getName() : null|string
    {
        return $this->get('name');
    }

    function getActive() : bool
    {
        return $this->get('active');
    }
    
    function getDisabled() : bool
    {
        return $this->get('disabled');
    }

    function getTitle() : null|string
    {
        return $this->get('title');
    }

    function getPage() : int
    {
        return $this->get('page');
    }

    function render(HtmlElement $element, Uri $uri) : HtmlElement
    {
        $result = $element->appendCreateHtml('a');

        if((bool) $this->active)
        {
            $result->setType('span');
            $result->addClass(self::ACTIVE);
        }
        else if((bool) $this->disabled)
        {
            $result->setType('span');
            $result->addClass(self::DISABLED);
        }

        if(!$this->active && !$this->disabled)
        {
            if($page = (int) $this->getPage())
            {
                $uri->set('page', $page);
            }
            else
            {
                $uri->remove('page');
            }

            $href = $uri->toString([
                Uri::PATH, Uri::QUERY, Uri::FRAGMENT
            ]);

            $result->addAttribute('href', $href);
        }

        if(strtolower($this->name) !== 'page')
        {
            $result->addClass('page-' . $this->name);
        }
        else
        {
            $result->html(trim($this->title));
        }

        return $result;
    }
}