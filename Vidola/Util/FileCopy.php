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
	 * @param string|array $filesOrDirsToCopy Eg `SubDir` or `array('lion.jpg', 'scripts/script.js')`
	 * @param string|array $exclude Directory(ies) or file(s) not to copy.
	 */
	public function copy($sourceDir, $destinationDir, $filesOrDirsToCopy, $exclude = array())
	{
		$destinationDir = $this->normalize($destinationDir);
		$sourceDir = $this->normalize($sourceDir);
	
		foreach ((array) $filesOrDirsToCopy as $fileOrDirToCopy)
		{
			if ($this->isExcluded($fileOrDirToCopy, (array) $exclude, $sourceDir))
			{
				continue;
			}

			if (is_dir($sourceDir . $fileOrDirToCopy))
			{
				if (!is_dir($destinationDir . $fileOrDirToCopy))
				{
					mkdir($destinationDir . $fileOrDirToCopy);
				}
				$dir = opendir($sourceDir . $fileOrDirToCopy);
				while(false !== ($file = readdir($dir)))
				{
					if ($file === '.' || $file === '..')
					{
						continue;
					}
					$this->copy(
						$sourceDir,
						$destinationDir,
						$this->normalize($fileOrDirToCopy) . $file,
						$exclude
					);
				}
			}
			else
			{
				if (!is_dir(dirname($destinationDir . $fileOrDirToCopy)))
				{
					mkdir(dirname($destinationDir . $fileOrDirToCopy));
				}

				copy($sourceDir . $fileOrDirToCopy, $destinationDir . $fileOrDirToCopy);
			}
		}
	}

	private function isExcluded($fileOrDir, array $excluded, $sourceDir)
	{
		$fileOrDir = $sourceDir . $fileOrDir;
		if (is_dir($fileOrDir))
		{
			$fileOrDir = $this->normalize($fileOrDir);
		}

		foreach ($excluded as $excludedFileOrDir)
		{
			$excludedFileOrDir = $sourceDir . $excludedFileOrDir;
			if (is_dir($excludedFileOrDir))
			{
				$excludedFileOrDir = $this->normalize($excludedFileOrDir);
			}
			if ($fileOrDir == $excludedFileOrDir)
			{
				return true;
			}
		}

		return false;
	}

	private function normalize($dir)
	{
		return (substr($dir, -1) === DIRECTORY_SEPARATOR) ? $dir : $dir . DIRECTORY_SEPARATOR;
	}
}