<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_View_StoredTemplatableFileViewTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->extensionProvider = $this->getMock('\\Vidola\\Util\\FileExtensionProvider');
	}

	/**
	 * @test
	 */
	public function rendersGivenTemplate()
	{
		$target = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foo.html';

		if (file_exists($target))
		{
			unlink($target);
		}

		$this->extensionProvider
			->expects($this->any())
			->method('addExtension')
			->with('/tmp/foo')
			->will($this->returnValue('/tmp/foo.html'));

		$api = new \Vidola\UnitTests\Support\TestApi();
		$api->set('name', 'bar');

		$template = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Template.html';
 
		$view = new \Vidola\View\StoredTemplatableFileView($this->extensionProvider);
		$view->setTemplate($template);
		$view->setFilename('foo');
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
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Template.html';
		$api = new \Vidola\UnitTests\Support\TestApi();
		$api->set('name', 'bar');

		$view = new \Vidola\View\StoredTemplatableFileView($this->extensionProvider);
		$view->setTemplate($template);
		$view->setFilename('foo');
		$view->setOutputDir('/doesnotexist');
		$view->addApi($api);

		try {
			$view->render($template);
			$this->fail();
		} catch (\Exception $e) {
			return;
		}
	}
}