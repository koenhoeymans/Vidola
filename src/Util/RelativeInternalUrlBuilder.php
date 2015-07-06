<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

interface RelativeInternalUrlBuilder extends \AnyMark\Util\InternalUrlBuilder
{
    /**
     * Creates the url pointing to a resource relative to a given
     * resource.. The resources must be specified
     * as a relative path.
     *
     * @param  string $resource
     * @param  string $relative
     * @return string
     */
    public function urlToFrom($resource, $relative);
}
