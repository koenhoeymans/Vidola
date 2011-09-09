<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_VidolaTagsToHtmlTagsTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\VidolaTagsToHtmlTags();
	}

	/**
	 * @test
	 */
	public function replacesVidolaTagsToHtmlTags()
	{
		$viTags = "This is {{i}}italic{{/i}} text.";
		$htmlTags = "This is <i>italic</i> text.";
		$this->assertEquals($htmlTags, $this->pattern->process($viTags));
	}
}