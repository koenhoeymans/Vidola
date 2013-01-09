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
	/**
	 * Copies the file or files from a template in the source to the destination
	 * directory. If a file/dir is in a subdirectory a subdirectory will be
	 * created in the destination.
	 * 
	 * @param string $sourceDir Eg `/tmp`
	 * @param string $destinationDir
	 * @param string|array $filesOrDir Eg `SubDir` or `array('lion.jpg', 'scripts/script.js')`
	 */
	public function copy($sourceDir, $destinationDir, $filesOrDirToCopy)
	{
		if (substr($sourceDir, -1) !== DIRECTORY_SEPARATOR)
		{
			$sourceDir .= DIRECTORY_SEPARATOR;
		}
		if (substr($destinationDir, -1) !== DIRECTORY_SEPARATOR)
		{
			$destinationDir .= DIRECTORY_SEPARATOR;
		}

		if (is_string($filesOrDirToCopy) && is_dir($sourceDir . $filesOrDirToCopy))
		{
			$this->recurseCopy(
				$sourceDir . $filesOrDirToCopy, $destinationDir . $filesOrDirToCopy
			);
		}
		else
		{
			$files = (array) $filesOrDirToCopy;
			foreach ($files as $file)
			{
				if (!is_dir(dirname($destinationDir . $file)))
				{
					mkdir(dirname($destinationDir . $file));
				}
				copy($sourceDir . $file, $destinationDir . $file);
			}
		}
	}

	// http://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php/2050909#2050909
	public function recurseCopy($src,$dst)
	{
		$dir = opendir($src);

		if (!is_dir($dst))
		{
			mkdir($dst);
		}

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) {
					recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
}