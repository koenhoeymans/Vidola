<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\View\ViewApi;
/**
 * The supported API that can be used for templates.
 *
 * @package Vidola
 */
class MarkdownBasedDocumentationViewApi implements ViewApi
{
    const VIEW_ACCESS_NAME = 'document';

    private $currentPage;

    private $pageGuide;

    private $structure;

    public function __construct(
        Page $currentPage,
        PageGuide $pageGuide,
        Structure $structure
    ) {
        $this->currentPage = $currentPage;
        $this->pageGuide = $pageGuide;
        $this->structure = $structure;
    }

    /**
     * @see Vidola\View.ViewApi::getName()
     */
    public function getName()
    {
        return self::VIEW_ACCESS_NAME;
    }

    /**
     * The content for the current page after going through the parser.
     *
     * @return string
     */
    public function currentPageContent()
    {
        return $this->pageGuide->getParsedContent($this->currentPage);
    }

    /**
     * The url pointing to a given page relative to the current page.
     *
     * @param  string $page
     * @return string
     */
    public function getPageUrl(Page $page)
    {
        return $this->structure->getUrl($this->currentPage, $page);
    }

    /**
     * The url pointing to the previous page or null if there is no previous page.
     *
     * @return string|null
     */
    public function previousPageUrl()
    {
        $previousPage = $this->structure->getPreviousPage($this->currentPage);
        if ($previousPage) {
            return $this->structure->getUrl($this->currentPage, $previousPage);
        }

        return;
    }

    /**
     * The url pointing to the next page or null if there is no next page.
     *
     * @return string|null
     */
    public function nextPageUrl()
    {
        $nextPage = $this->structure->getNextPage($this->currentPage);
        if ($nextPage) {
            return $this->structure->getUrl($this->currentPage, $nextPage);
        }

        return;
    }

    /**
     * The url of the page that is the starting point of the documentation.
     *
     * @return string
     */
    public function startPageUrl()
    {
        return $this->structure->getUrl(
            $this->currentPage, $this->structure->getStartPage()
        );
    }

    /**
     * Link to a relative resource in the project such as a CSS file.
     *
     * @param  string $resource
     * @return string
     */
    public function urlTo($resource)
    {
        $resource = new \Vidola\Document\Resource($resource);

        return $this->structure->getUrl($this->currentPage, $resource);
    }

    /**
     * The title of the current page.
     *
     * @return string
     */
    public function currentPageTitle()
    {
        return $this->pageGuide->getTitle($this->currentPage);
    }

    /**
     * The title of the previous page or null if there is no previous page.
     *
     * @return string|null
     */
    public function previousPageTitle()
    {
        $page = $this->structure->getPreviousPage($this->currentPage);

        return $page ? $this->pageGuide->getTitle($page) : null;
    }

    /**
     * The title of the next page or null if there is no next page.
     *
     * @return string|null
     */
    public function nextPageTitle()
    {
        $page = $this->structure->getNextPage($this->currentPage);

        return $page ? $this->pageGuide->getTitle($page) : null;
    }

    /**
     * The title of a given page.
     *
     * @param  Page   $page
     * @return string
     */
    public function getPageTitle(Page $page)
    {
        return $this->pageGuide->getTitle($page);
    }

    /**
     * Answers the question 'Does this page has a table of contents?' If there
     * are no headers there won't be a table of contents.
     *
     * @return bool
     */
    public function pageHasToc()
    {
        return $this->toc() ? true : false;
    }

    /**
     * The table of contents for the current page, e.i. a HTML list of headers as a list
     * and sublists if there are subheaders. If there is no table of contents it will
     * return null.
     *
     * @param  int         $maxDepth
     * @return string|null The HTML list with headers or null if there is not toc.
     */
    public function toc($maxDepth = null)
    {
        $toc = $this->pageGuide->getToc($this->currentPage, $maxDepth);

        if ($toc) {
            return $toc->toString();
        }

        return;
    }

    /**
     * A list of files that lead to the current page.
     *
     * @return array
     */
    public function getBreadCrumbs()
    {
        return $this->structure->getBreadCrumbs($this->currentPage);
    }
}
