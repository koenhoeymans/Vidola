<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class HtmlFileUrlBuilder implements RelativeInternalUrlBuilder
{
    public function urlTo($resource)
    {
        return $this->urlToFrom($resource, null);
    }

    /**
     * @see Vidola\Util.RelativeInternalUrlBuilder::urlToFrom()
     */
    public function urlToFrom($to, $relativeTo = null)
    {
        $numberSignPos = strpos($to, "#");

        if ($numberSignPos === false) {
            $filePart = $to;
            $relPart = '';
        } else {
            $filePart = substr($to, 0, $numberSignPos);
            $relPart = substr($to, $numberSignPos);
        }

        $levelsUp = $this->howManyLevelsDeep($relativeTo);
        while ($levelsUp>0) {
            $filePart = '../'.$filePart;
            $levelsUp--;
        }

        $info = pathinfo($filePart);
        if (!isset($info['extension'])) {
            $filePart = $this->addExtension($filePart);
        }

        return $filePart.$relPart;
    }

    private function howManyLevelsDeep($resource)
    {
        return count(explode(DIRECTORY_SEPARATOR, $resource)) -1;
    }

    private function addExtension($resource)
    {
        return $resource.'.html';
    }
}
