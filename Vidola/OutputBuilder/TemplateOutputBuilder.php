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

	private $content = '';

	private $fileName = '';

	private $title = '';

	public function __construct(Writer $writer)
	{
		$this->writer = $writer;
	}

	/**
	 * @see Vidola\OutputBuilder.OutputBuilder::build()
	 */
	public function build()
	{
		ob_start();
		include $this->template;
		$text = ob_get_clean();
	
		$this->writer->write($text, $this->fileName);
	}

	/**
	 * Set path to the template file.
	 * 
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}

	/**
	 * @see Vidola\OutputBuilder.OutputBuilder::setContent()
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @see Vidola\OutputBuilder.OutputBuilder::setFileName()
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}

	/**
	 * @see Vidola\OutputBuilder.OutputBuilder::setTitle()
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
}