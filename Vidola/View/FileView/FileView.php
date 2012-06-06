<?php

/**
 * @package Vidola
 */
namespace Vidola\View\FileView;

use Vidola\View\TemplateBasedView;
use Vidola\View\ViewApi;

/**
 * @package Vidola
 * 
 * Writes content of a template to a file.
 */
class FileView implements TemplateBasedView
{
	private $api = array();

	private $extension = '';

	private $outputDir;

	private $template;

	private $filename;

	/**
	 * The extension for the file that will be written.
	 * 
	 * @param string $ext
	 */
	public function setExtension($ext)
	{
		$this->extension = $ext;
	}

	/**
	 * Set output directory to write files to.
	 *
	 * @param string $dir
	 */
	public function setOutputDir($dir)
	{
		$this->outputDir = $dir;
	}

	private function getOutputDir()
	{
		if (isset($this->outputDir))
		{
			return $this->outputDir; 
		}
		return sys_get_temp_dir();
	}

	/**
	 * The name of the file, without directory and extension.
	 * 
	 * @param string $name
	 */
	public function setFilename($name)
	{
		$this->filename = $name;
	}

	public function addApi(ViewApi $api)
	{
		$this->api[$api->getName()] = $api;
	}

	public function render()
 	{
		extract($this->api);
		ob_start();
		require($this->getTemplate());
		$output = ob_get_clean();

		$this->write($output, $this->filename);
	}

	public function setTemplate($template)
	{
		$this->template = $template;
	}

	private function getTemplate()
	{
		if (isset($this->template))
		{
			return $this->template;
		}
		return __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Templates'
			. DIRECTORY_SEPARATOR . 'Default'
			. DIRECTORY_SEPARATOR . 'Index.php';
	}

	/**
	 * Writes text to a specified file.
	 *
	 * @param string $text
	 * @param string $fileName Relative to output directory.
	 * @throws \Exception
	 */
	private function write($text, $fileName)
	{
		$dir = $this->outputDir . DIRECTORY_SEPARATOR . substr(
			$fileName, 0, strrpos($fileName, DIRECTORY_SEPARATOR)
		);
		$fileName = substr($fileName, strrpos($fileName, DIRECTORY_SEPARATOR));

		if (!is_dir($dir))
		{
			mkdir($dir);
		}

		$file = $dir . DIRECTORY_SEPARATOR . $fileName . '.' . $this->extension;

		$fileHandle = fopen($file, 'w');

		if (!$fileHandle)
		{
			throw new \Exception('Writer::write() was unable to open ' . $file);
		}

		if(fwrite($fileHandle, $text) === false)
		{
			throw new \Exception('Writer::write() was unable to write to ' . $file);
		}

		fclose($fileHandle);
	}
}