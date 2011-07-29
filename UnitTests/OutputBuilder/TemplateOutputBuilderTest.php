<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_OutputBuilder_TemplateOutputBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->writer = $this->getMock('\\Vidola\\Util\\Writer');
		$this->outputBuilder = new \Vidola\OutputBuilder\TemplateOutputBuilder(
			$this->writer
		);

		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'output.x'))
		{
			unlink($dir . 'output.x');
		}
	}

	/**
	 * @test
	 */
	public function loadsTemplate()
	{
		$template = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR .'Support'
			. DIRECTORY_SEPARATOR . 'Template.php';
		$target = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'output.x';

		$this->writer
			->expects($this->once())
			->method('write')
			->with("header\ncontent\nfooter", $target);

		$this->outputBuilder->setTemplate($template);
		$this->outputBuilder->setContent('content');
		$this->outputBuilder->setFileName($target);
		$this->outputBuilder->build();
	}
}