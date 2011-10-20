<?php

require_once('TestHelper.php');

/**
 * These are the PHPMarkdown tests as found in the test suite of PHPMarkdown.
 */
class Vidola_EndToEndTests_PhpMarkdownTest extends PHPUnit_Framework_TestCase
{
	public function createTestFor($name)
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . $name . '.html'))
		{
			unlink($dir . $name . '.html');
		}

		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'PhpMarkdown.mdtest'
			. DIRECTORY_SEPARATOR . $name . '.text';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();
		$_SERVER['argv']['template'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		\Vidola\Vidola::run();
		
		$this->assertEquals(
			file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'PhpMarkdown.mdtest'
				. DIRECTORY_SEPARATOR . $name . '.html'
				),
			file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . $name . '.html'
			)
		);

		if (file_exists($dir . $name . '.html'))
		{
			unlink($dir . $name . '.html');
		}
	}

	/**
	 * @test
	 */
	public function autoLinks()
	{
		$this->createTestFor('AutoLinks');
	}

	/**
	 * @test
	 */
	public function backslashEscapes()
	{
		$this->createTestFor('BackslashEscapes');
	}

	/**
	 * @test
	 * 
	 * Changed some whitespace expectations.
	 * 
	 * Changed *	    code block
	 * 					as first element of a list item
	 * to using a tab instead of the first four spaces, as in the first list item.
	 */
	public function codeBlockInAListItem()
	{
		$this->createTestFor('CodeBlockInAListItem');
	}

	/**
	 * @test
	 */
	public function codeBlockOnSecondLine()
	{
		$this->createTestFor('CodeBlockOnSecondLine');
	}

	/**
	 * @test
	 */
	public function codeSpans()
	{
		$this->createTestFor('CodeSpans');
	}

	/**
	 * @test
	 */
	public function emailAutoLinks()
	{
		$this->createTestFor('EmailAutoLinks');
	}

	/**
	 * @test
	 */
	public function emphasis()
	{
		$this->createTestFor('Emphasis');
	}

	/**
	 * @test
	 */
	public function emptyListItem()
	{
		$this->createTestFor('EmptyListItem');
	}

	/**
	 * @test
	 */
	public function headers()
	{
		$this->createTestFor('Headers');
	}

	/**
	 * @test
	 */
	public function horizontalRules()
	{
		$this->createTestFor('HorizontalRules');
	}

	/**
	 * @test
	 * 
	 * Changed transformation to spaces to tabs (as in text).
	 */
	public function inlineHTMLSimple()
	{
		$this->createTestFor('InlineHTMLSimple');
	}

	/**
	 * @test
	 */
	public function inlineHTMLSpan()
	{
		$this->createTestFor('InlineHTMLSpan');
	}

	/**
	 * @test
	 */
	public function inlineHTMLComments()
	{
		$this->createTestFor('InlineHTMLComments');
	}
}