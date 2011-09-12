<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_ImageTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->linkDefinitions = $this->getMock(
			'\\Vidola\\Processor\\Processors\\LinkDefinitionCollector'
		);
		$this->image = new \Vidola\Pattern\Patterns\Image($this->linkDefinitions);
	}

	/**
	 * @test
	 */
	public function anInlineImageStartsWithAnExclamationMarkAndHasAltTextBetweenSquareBracketsFollowedByPathToImgBetweenRoundBrackets()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg) in between.";
		$html = "Image is {{img alt=\"alt text\" src=\"http://example.com/image.jpg\"}} in between.";
		$this->assertEquals($html, $this->image->replace($text));
	}

	/**
	 * @test
	 */
	public function titleTextIsOptionalInSingleQuotes()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg 'title text') in between.";
		$html = "Image is {{img alt=\"alt text\" title=\"title text\" src=\"http://example.com/image.jpg\"}} in between.";
		$this->assertEquals($html, $this->image->replace($text));
	}

	/**
	 * @test
	 */
	public function titleTextIsOptionalInDoubleQuotes()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg \"title text\") in between.";
		$html = "Image is {{img alt=\"alt text\" title=\"title text\" src=\"http://example.com/image.jpg\"}} in between.";
		$this->assertEquals($html, $this->image->replace($text));
	}

	/**
	 * @test
	 */
	public function referenceStyleHasSameAltTextButWithLinkReferenceBetweenSquareBrackets()
	{
		$this->linkDefinitions
			->expects($this->once())
			->method('get')->with('id')
			->will($this->returnValue(
				new \Vidola\Pattern\Patterns\LinkDefinition('id', 'http://example.com/image.jpg')));

		$text = "Image is ![alt text][id] in between.";
		$html = "Image is {{img alt=\"alt text\" src=\"http://example.com/image.jpg\"}} in between.";
		$this->assertEquals($html, $this->image->replace($text));
	}

	/**
	 * @test
	 */
	public function referenceStyleCanContainOptionalTitle()
	{
		$this->linkDefinitions
			->expects($this->once())
			->method('get')->with('id')
			->will($this->returnValue(
				new \Vidola\Pattern\Patterns\LinkDefinition('id', 'http://example.com/image.jpg', 'title')));

		$text = "Image is ![alt text][id] in between.";
		$html = "Image is {{img alt=\"alt text\" title=\"title\" src=\"http://example.com/image.jpg\"}} in between.";
		$this->assertEquals($html, $this->image->replace($text));
	}
}