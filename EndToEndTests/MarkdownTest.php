<?php

require_once('TestHelper.php');

/**
 * These are the Markdown tests as found in the test suite of PHPMarkdown.
 * Some formatting was changed:
 *  * removed blank lines before closing code tag
 *  * removed empty line at the end of the tests
 * 
 * Other changes have been documented before each test.
 */
class Vidola_EndToEndTests_MarkdownTest extends PHPUnit_Framework_TestCase
{
	public function createTestFor($name)
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . $name . '.html'))
		{
			unlink($dir . $name . '.html');
		}

		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Markdown.mdtest'
			. DIRECTORY_SEPARATOR . $name . '.text';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();
		$_SERVER['argv']['template'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		\Vidola\Vidola::run();
		
		$this->assertEquals(
			file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Markdown.mdtest'
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
	 * 
	 * Changed <p>6 > 5.</p> to <p>6 &gt; 5.</p> as expected outcome
	 */
	public function ampsAndAngleEncoding()
	{
		$this->createTestFor('AmpsAndAngleEncoding');
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
	 */
	public function blockquotesWithCodeBlocks()
	{
		$this->createTestFor('BlockquotesWithCodeBlocks');
	}

	/**
	 * @test
	 */
	public function codeBlocks()
	{
		$this->createTestFor('CodeBlocks');
	}

	/**
	 * @test
	 * 
	 * Changed expected outcome of `<test a="` content of attribute `">`
	 * from <p><code>&lt;test a="</code> content of attribute <code>"&gt;</cde></p>
	 * to <p><code>&lt;test a="` content of attribute `"&gt;</code></p>
	 */
	public function codeSpans()
	{
		$this->createTestFor('CodeSpans');
	}

	/**
	 * @test
	 */
	public function hardWrappedParagraphsWithListLikeLines()
	{
		$this->createTestFor('HardWrappedParagraphsWithListLikeLines');
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
	 * Changed place of src attribute.
	 */
	public function images()
	{
		$this->createTestFor('Images');
	}

	/**
	 * @test
	 */
	public function inlineHTMLAdvanced()
	{
		$this->createTestFor('InlineHTMLAdvanced');
	}

	/**
	 * @test
	 */
	public function inlineHTMLSimple()
	{
		$this->createTestFor('InlineHTMLSimple');
	}

	/**
	 * @test
	 */
	public function inlineHTMLComments()
	{
		$this->createTestFor('InlineHTMLComments');
	}

	/**
	 * @test
	 */
	public function linksInlineStyle()
	{
		$this->createTestFor('LinksInlineStyle');
	}
}