<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_View_FileViewTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function rendersGivenTemplate()
	{
		$api = new \Vidola\UnitTests\Support\TestApi();
		$api->set('name', 'bar');

		$template = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Template.html';
 
		$view = new \Vidola\View\FileView\FileView();
		$view->setTemplate($template);
		$view->setFilename('foo');
		$view->setExtension('test');
		$view->setOutputDir(sys_get_temp_dir());
		$view->addApi($api);

		$view->render($template);
		$this->assertEquals(
			'foo bar',
			file_get_contents(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foo.test')
		);
	}
}