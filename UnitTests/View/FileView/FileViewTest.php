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
		$target = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foo.test';

		if (file_exists($target))
		{
			unlink($target);
		}

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
		$view->setExtension('.test');
		$view->setOutputDir(sys_get_temp_dir());
		$view->addApi($api);

		$view->render($template);

		$this->assertEquals('foo bar', file_get_contents($target));
	}

	/**
	 * @test
	 */
	public function targetDirMustExist()
	{
		$template = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Template.html';
		$api = new \Vidola\UnitTests\Support\TestApi();
		$api->set('name', 'bar');

		$view = new \Vidola\View\FileView\FileView();
		$view->setTemplate($template);
		$view->setFilename('foo');
		$view->setExtension('test');
		$view->setOutputDir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sfsdsdglkdjsdf');
		$view->addApi($api);

		try {
			$view->render($template);
			$this->fail();
		} catch (\Exception $e) {
			return;
		}
	}
}