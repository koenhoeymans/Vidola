<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_ImageTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->image = new \Vidola\Patterns\Image();
	}

	/**
	 * @test
	 */
	public function anInlineImageIsPlacedOnItsOwnLineWithImgItsLocationAndAltTextBetweenSquareBrackets()
	{
		$text = "Image follows: [img: http://example.com/image.jpg \"example image\"]\n\nSee image above.";
		$html = "Image follows: <img alt=\"example image\" src=\"http://example.com/image.jpg\">\n\nSee image above.";
		$this->assertEquals(
			$html, $this->image->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function alternateTextIsOptionalForInlineImages()
	{
		$text = "Image follows: [img: http://example.com/image.jpg]\n\nSee image above.";
		$html = "Image follows: <img src=\"http://example.com/image.jpg\">\n\nSee image above.";
		$this->assertEquals(
			$html, $this->image->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function referenceStyleUsesAltTextToPlaceTheLinkElsewhere()
	{
		$text = "Image follows: [img: \"example image\"]\n\nSee image above.\n[example image]: http://example.com/image.jpg\n";
		$html = "Image follows: <img alt=\"example image\" src=\"http://example.com/image.jpg\">\n\nSee image above.\n";
		$this->assertEquals(
			$html, $this->image->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function orderOfReferenceIsNotImportant()
	{
		$text = "Image1: [img: \"image1\"]\nImage2: [img: \"image2\"]\n[image2]: http://e.com/img2.jpg\n[image1]: http://e.com/img1.jpg\n";
		$html = "Image1: <img alt=\"image1\" src=\"http://e.com/img1.jpg\">\nImage2: <img alt=\"image2\" src=\"http://e.com/img2.jpg\">\n";
		$this->assertEquals(
			$html, $this->image->replace($text)
		);
	}
}