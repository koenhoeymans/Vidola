<?php

/**
 * @package Vidola
 */
namespace Vidola\OutputBuilder;

use Vidola\Util\Writer;

/**
 * @package Vidola
 */
class TemplateOutputBuilder implements OutputBuilder
{
	private $writer;

	private $template;

	private $content;

	private $fileName;

	public function __construct(Writer $writer)
	{
		$this->writer = $writer;
	}

	public function setTemplate($template)
	{
		$this->template = $template;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}

	public function build()
	{
		ob_start();
		include $this->template;
		$text = ob_get_clean();

		$this->writer->write($text, $this->fileName);
	}
}