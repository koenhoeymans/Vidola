<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface Structure extends PageList
{
    /**
     * Get the previous page.
     *
     * @param  Page      $page
     * @return Page|null
     */
    public function getPreviousPage(Page $page);

    /**
     * Get the next page.
     *
     * @param  Page      $page
     * @return Page|null
     */
    public function getNextPage(Page $page);

    /**
     * Get the first page of the project.
     *
     * @return Page
     */
    public function getStartPage();

    /**
     * Get the relative URL from one page to a resource.
     *
     * @param  Page   $from
     * @param  Page   $to
     * @return string
     */
    public function getUrl(Page $from, Linkable $to);

    /**
     * A list of the files that lead to `$file` as subfile.
     *
     * @param  Page  $page
     * @return array
     */
    public function getBreadCrumbs(Page $page);
}
