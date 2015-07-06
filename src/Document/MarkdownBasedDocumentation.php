<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Util\RelativeInternalUrlBuilder;

/**
 * @package Vidola
 */
class MarkdownBasedDocumentation implements FilenameCreator, Structure
{
    private $internalUrlBuilder;

    /**
     * List of Page objects.
     *
     * @var array
     */
    private $pages = array();

    /**
     * $pageUrl => ParentPage
     *
     * @var array
     */
    private $parentPages = array();

    public function __construct(RelativeInternalUrlBuilder $internalUrlBuilder)
    {
        $this->internalUrlBuilder = $internalUrlBuilder;
    }

    /**
     * @see Vidola\Document.PageList::add()
     */
    public function add(Page $page, Page $parentPage = null)
    {
        $this->pages[] = $page;
        if ($parentPage) {
            $this->parentPages[$page->getUrl()] = $parentPage;
        }
    }

    /**
     * @see Vidola\Document.PageList::getPages()
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @see Vidola\Document.Structure::getPreviousPage()
     */
    public function getPreviousPage(Page $page)
    {
        $pages = $this->getPages();
        $pageKey = array_search($page, $pages);
        if ($pageKey > 0) {
            return $pages[$pageKey-1];
        }

        return;
    }

    /**
     * @see Vidola\Document.Structure::getNextPage()
     */
    public function getNextPage(Page $page)
    {
        $pages = $this->getPages();
        $pageKey = array_search($page, $pages);
        $pageKey++;
        if ($pageKey !== count($pages)) {
            return $pages[$pageKey];
        }

        return;
    }

    /**
     * @see Vidola\Document.Structure::getStartPage()
     */
    public function getStartPage()
    {
        if (isset($this->pages[0])) {
            return $this->pages[0];
        }

        return;
    }

    /**
     * @see Vidola\Document.Structure::getUrl()
     */
    public function getUrl(Page $from, Linkable $to)
    {
        return $this->internalUrlBuilder->urlToFrom($to->getUrl(), $from->getUrl());
    }

    /**
     * @see Vidola\Document.Structure::getBreadCrumbs()
     */
    public function getBreadCrumbs(Page $page)
    {
        $startPage = $this->getStartPage();
        $breadCrumbs = $this->getPagesThatLeadTo($startPage, $page);
        array_unshift($breadCrumbs, $startPage);
        if ($page != $startPage) {
            $breadCrumbs[] = $page;
        }

        return $breadCrumbs;
    }

    private function getPagesThatLeadTo(Page $from, Page $to)
    {
        if ($from == $to) {
            return array();
        }

        if (!isset($this->parentPages[$to->getUrl()])) {
            return array();
        }

        $parentPage = $this->parentPages[$to->getUrl()];
        if ($parentPage == $from) {
            return array();
        }

        $parentPages = $this->getPagesThatLeadTo($from, $parentPage);
        array_unshift($parentPages, $parentPage);

        return $parentPages;
    }

    /**
     * @see Vidola\Document.FilenameCreator::createFilename()
     */
    public function createFilename(Page $page)
    {
        $fileParts = pathinfo($page->getUrl());

        return $fileParts['dirname'].DIRECTORY_SEPARATOR.$fileParts['filename'];
    }
}
