<?php

/**
 * @package
 */
namespace Vidola\Util;

/**
 * @package
 */
class FileCopy
{
    public function copy(
        $sourceDirectory, $targetDirectory, $exclude = array(), $include = array()
    ) {
        $sourceDirectory = $this->normalize($sourceDirectory);
        $targetDirectory = $this->normalize($targetDirectory);
        $exclude = (array) $exclude;
        $include = (array) $include;

        foreach ($exclude as $key => &$excluded) {
            if (is_dir($excluded)) {
                $excluded = $this->normalize($excluded);
            }
        }

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory);
        }

        $this->recurseCopy($sourceDirectory, $targetDirectory, $exclude, $include);

        foreach ($include as $included) {
            $relPart = explode($sourceDirectory, $included);
            if (is_dir($included)) {
                $included = $this->normalize($included);
                $this->copy(
                    $sourceDirectory.$relPart[1],
                    $targetDirectory.$relPart[1],
                    $exclude,
                    $include
                );
            } else {
                if (!is_dir(dirname($targetDirectory.$relPart[1]))) {
                    mkdir(dirname($targetDirectory.$relPart[1]));
                }
                copy($sourceDirectory.$relPart[1], $targetDirectory.$relPart[1]);
            }
        }
    }

    public function recurseCopy(
        $sourceDirectory, $targetDirectory, array $exclude, array $include
    ) {
        $dir = opendir($sourceDirectory);
        while (false !== ($file = readdir($dir))) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir($sourceDirectory.$file)) {
                $file = $this->normalize($file);
            }

            if (in_array($sourceDirectory.$file, $exclude)) {
                continue;
            }

            if (is_dir($sourceDirectory.$file)) {
                $this->copy($sourceDirectory.$file, $targetDirectory.$file, $exclude, $include);
            } else {
                copy($sourceDirectory.$file, $targetDirectory.$file);
            }
        }
    }

    private function normalize($dir)
    {
        return (substr($dir, -1) === DIRECTORY_SEPARATOR) ? $dir : $dir.DIRECTORY_SEPARATOR;
    }
}
