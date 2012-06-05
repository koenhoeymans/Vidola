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
		$template = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Template.html'; 
		$view = new \Vidola\View\FileView\FileView();
		$api = new \Vidola\UnitTests\Support\TestApi();
		$api->set('name', 'bar');
		$view->addApi($api);
		$this->assertEquals('foo bar', $view->render($template));
	}
}