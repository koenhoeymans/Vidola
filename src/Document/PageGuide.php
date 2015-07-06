<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface PageGuide
{
    /**
     * Get the parsed content of a page as a string or as DomDocument
     *
     * @param  Page                $page
     * @param  bool                $dom
     * @return string|\DomDocument
     */
    public function getParsedContent(Page $page, $dom = false);

    /**
     * The title of a page.
     *
     * @param  Page   $page
     * @return string
     */
    public function getTitle(Page $page);

    /**
     * A DomElement containing the ToC of a page.
     *
     * @param  Page             $page
     * @param  int              $maxDepth
     * @return \DomElement|null
     */
    public function getToc(Page $page, $maxDepth = null);
}
