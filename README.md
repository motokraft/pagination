# Пагинация по страницам с использованием html-element

![Package version](https://img.shields.io/github/v/release/motokraft/pagination)
![Total Downloads](https://img.shields.io/packagist/dt/motokraft/pagination)
![PHP Version](https://img.shields.io/packagist/php-v/motokraft/pagination)
![Repository Size](https://img.shields.io/github/repo-size/motokraft/pagination)
![License](https://img.shields.io/packagist/l/motokraft/pagination)

## Установка

Библиотека устанавливается с помощью пакетного менеджера [**Composer**](https://getcomposer.org/)

Добавьте библиотеку в файл `composer.json` вашего проекта:

```json
{
    "require": {
        "motokraft/pagination": "^1.0"
    }
}
```

или выполните команду в терминале

```
$ php composer require motokraft/pagination
```

Включите автозагрузчик Composer в код проекта:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Привер класса вывода пагинации

```php
use \Motokraft\HtmlElement\HtmlElement;
use \Motokraft\Pagination\Renderer\BaseRenderer;
use \Motokraft\Pagination\Renderer\RendererInterface;
use \Motokraft\Uri\Uri;

class DemoPagination extends BaseRenderer implements RendererInterface
{
    function render(HtmlElement $element) : HtmlElement
    {
        $result = $element->appendCreateHtml('div', [
            'class' => 'category-pagination-inner'
        ]);

        // Вывод счетчика навигации
        $this->renderCounter($result);

        // Вывод контейнера навигация
        $this->renderPagination($result);

        return $result;
    }

    private function renderCounter(HtmlElement $element)
    {
        $padding = $this->getPagination();

        $div = $element->appendCreateHtml('div', [
            'class' => 'pagination-legend-inner'
        ]);

        $total = $padding->getTotal();
        $limitstart = $padding->getLimitStart();
        $limit = $padding->getLimit();
        $toResult = $total;

        if (($limitstart + $limit) < $total)
        {
            $toResult = $limitstart + $limit;
        }

        if ($total > 0)
        {
            $div->html(sprintf(
                'Showing %s to %s of %s entries',
                ($limitstart + 1), $toResult, $total
            ));
        }
        else
        {
            $div->html('No matching records found');
        }
    }

    private function renderPagination(HtmlElement $element)
    {
        $uri = clone Uri::getInstance();

        $nav = $element->appendCreateHtml('nav', [
            'class' => 'pagination-nav-inner'
        ]);

        // Кнопка 'В начало'
        $first = $this->_prepareFirstItem();
        $first = $first->render($nav, clone $uri);

        $first->addClass('nav-item-inner');
        $first->addAttrAria('label', 'First');

        // Кнопка 'Назад'
        $prev = $this->_preparePrevItem();
        $prev = $prev->render($nav, clone $uri);

        $prev->addClass('nav-item-inner');
        $prev->addAttrAria('label', 'Prev');

        foreach($this->_getPages() as $page)
        {
            // Нумерация страниц
            $page = $page->render($nav, clone $uri);
            $page->addClass('nav-item-inner');
        }

        // Кнопка 'Вперед'
        $next = $this->_prepareNextItem();
        $next = $next->render($nav, clone $uri);

        $next->addClass('nav-item-inner');
        $next->addAttrAria('label', 'Next');

        // Кнопка 'В конец'
        $last = $this->_prepareLasttItem();
        $last = $last->render($nav, clone $uri);

        $last->addClass('nav-item-inner');
        $last->addAttrAria('label', 'Last');
    }
}
```

## Примеры инициализации

```php
use \Motokraft\Pagination\Pagination;
use \Motokraft\HtmlElement\HtmlElement;

// Добавляем класс из примера выще
Pagination::addRenderer('demo', DemoPagination::class);

$div = new HtmlElement('div');

$pagination = new Pagination(367, 0, 10, 9);
$pagination->render($div, 'demo');

echo $div;
```

## Полученный результат

```html
<div data-level="0">
    <div class="category-pagination-inner" data-level="1">
        <div class="pagination-legend-inner" data-level="2">
            Showing 1 to 10 of 367 entries
        </div>
        <nav class="pagination-nav-inner" data-level="2">
            <span class="disabled page-first nav-item-inner" aria-label="First" data-level="3"></span>
            <span class="disabled page-prev nav-item-inner" aria-label="Prev" data-level="3"></span>
            <span class="active nav-item-inner" data-level="3">
                1
            </span>
            <a href="/pagination.php?page=2" class="nav-item-inner" data-level="3">
                2
            </a>
            <a href="/pagination.php?page=3" class="nav-item-inner" data-level="3">
                3
            </a>
            <a href="/pagination.php?page=4" class="nav-item-inner" data-level="3">
                4
            </a>
            <a href="/pagination.php?page=5" class="nav-item-inner" data-level="3">
                5
            </a>
            <a href="/pagination.php?page=6" class="nav-item-inner" data-level="3">
                6
            </a>
            <a href="/pagination.php?page=7" class="nav-item-inner" data-level="3">
                7
            </a>
            <a href="/pagination.php?page=8" class="nav-item-inner" data-level="3">
                8
            </a>
            <a href="/pagination.php?page=9" class="nav-item-inner" data-level="3">
                9
            </a>
            <a href="/pagination.php?page=2" class="page-next nav-item-inner" aria-label="Next" data-level="3"></a>
            <a href="/pagination.php?page=37" class="page-last nav-item-inner" aria-label="Last" data-level="3"></a>
        </nav>
    </div>
</div>
```

## Лицензия

Эта библиотека находится под лицензией MIT License.