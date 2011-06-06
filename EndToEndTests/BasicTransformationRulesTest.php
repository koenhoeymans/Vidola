<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_BasicTransformationRulesTest extends \PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->setupCreator = new \Vidola\EndToEndTests\Support\SetupCreator();
	}

	/**
	 * @test
	 */
	public function orderOfAddingPatternsDeterminesOrderOfProcessing()
	{
		// given
		$sc = $this->setupCreator;
		$vi = $sc->createSetup(
			$sc->paragraph(),
			$sc->header()
		);

		$text = "\n\nparagraph\n\nheader\n===\n\nparagraph\n\n";

		// when
		$html = $vi->replace($text);

		// then
		$this->assertNotEquals(
			"\n\n<p>paragraph</p>\n\n<h1>header</h1>\n\n<p>paragraph</p>\n\n",
			$html
		);
	}

	/**
	 * @test
	 */
	public function patternsCanContainSubpatterns()
	{
		// given
		$sc = $this->setupCreator;
		$vi = $sc->createSetup(
			$sc->header(),
			$sc->paragraph(
				$sc->bold()
			)
		);

		$text = "\n*bold*\n\nparagraph with *bold* text\n\n";

		// when
		$html = $vi->replace($text);

		// then
		$this->assertEquals(
			"\n*bold*\n\n<p>paragraph with <b>bold</b> text</p>\n\n", $html
		);
	}

	/**
	 * @test
	 */
	public function aSubpatternTransformsOnlyInMatchesFromParentPattern()
	{
		// given
		$sc = $this->setupCreator;
		$vi = $sc->createSetup(
			$sc->header(),
			$sc->paragraph(
				$sc->bold()
			)
		);

		$text = "\n*bold*\n\nparagraph with *bold* text\n\n";

		// when
		$html = $vi->replace($text);

		// then
		$this->assertEquals(
			"\n*bold*\n\n<p>paragraph with <b>bold</b> text</p>\n\n", $html
		);
	}
}