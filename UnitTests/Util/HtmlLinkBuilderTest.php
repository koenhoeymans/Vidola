<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_HtmlFileUrlBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->linkBuilder = new \Vidola\Util\HtmlFileUrlBuilder();
	}

	/**
	 * @test
	 */
	public function buildsUrlWithGivenExtension()
	{
		$this->assertEquals('x.html', $this->linkBuilder->buildFrom('x'));
	}

	/**
	 * @test
	 */
	public function extensionIsPlacedBeforeDoubleColon()
	{
		$this->assertEquals('x.html#y', $this->linkBuilder->buildFrom('x#y'));
	}
}