<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_RelativeUrlBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->relativeUrlBuilder = new \Vidola\Util\RelativeUrlBuilder();
	}

	/**
	 * @test
	 */
	public function buildsUrlWithGivenExtension()
	{
		$this->relativeUrlBuilder->setExtension('html');
		$this->assertEquals('x.html', $this->relativeUrlBuilder->buildUrl('x'));
	}

	/**
	 * @test
	 */
	public function extensionIsPlacedBeforeDoubleColon()
	{
		$this->relativeUrlBuilder->setExtension('html');
		$this->assertEquals('x.html#y', $this->relativeUrlBuilder->buildUrl('x#y'));
	}
}