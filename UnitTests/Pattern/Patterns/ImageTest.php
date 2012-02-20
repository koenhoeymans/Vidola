<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_ImageTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->linkDefinitions = $this->getMock(
			'\\Vidola\\Processor\\Processors\\LinkDefinitionCollector'
		);
		$this->image = new \Vidola\Pattern\Patterns\Image($this->linkDefinitions);
	}

	public function getPattern()
	{
		return $this->image;
	}

	public function createImgDom($alt, $title = null, $url)
	{
		$domDoc = new \DOMDocument();
		$domEl = $domDoc->appendChild(new \DOMElement('img'));
		$domEl->setAttribute('alt', $alt);
		if ($title)
		{
			$domEl->setAttribute('title', $title);
		}
		$domEl->setAttribute('src', $url);

		return $domEl;
	}

	/**
	 * @test
	 */
	public function anInlineImageStartsWithAnExclamationMarkAndHasAltTextBetweenSquareBracketsFollowedByPathToImgBetweenRoundBrackets()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg) in between.";
		$domEl = $this->createImgDom('alt text', null, 'http://example.com/image.jpg');
		$this->assertCreatesDomFromText($domEl, $text);
	}

	/**
	 * @test
	 */
	public function titleTextIsOptionalInSingleQuotes()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg 'title text') in between.";
		$domEl = $this->createImgDom('alt text', 'title text', 'http://example.com/image.jpg');
		$this->assertCreatesDomFromText($domEl, $text);
	}

	/**
	 * @test
	 */
	public function titleTextIsOptionalInDoubleQuotes()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg \"title text\") in between.";
		$domEl = $this->createImgDom('alt text', 'title text', 'http://example.com/image.jpg');
		$this->assertCreatesDomFromText($domEl, $text);
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
		$domEl = $this->createImgDom('alt text', null, 'http://example.com/image.jpg');
		$this->assertCreatesDomFromText($domEl, $text);
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
		$domEl = $this->createImgDom('alt text', 'title', 'http://example.com/image.jpg');
		$this->assertCreatesDomFromText($domEl, $text);
	}
}