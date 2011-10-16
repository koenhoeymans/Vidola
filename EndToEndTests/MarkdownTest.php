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

	/**
	 * @test
	 */
	public function linksReferenceStyle()
	{
		$this->createTestFor('LinksReferenceStyle');
	}

	/**
	 * @test
	 */
	public function linksShortcutReferences()
	{
		$this->createTestFor('LinksShortcutReferences');
	}

	/**
	 * @test
	 */
	public function literalQuotesInTitles()
	{
		$this->createTestFor('LiteralQuotesInTitles');
	}

	/**
	 * @test
	 * 
	 * Added two header id's to expected output.
	 */
	public function markdownDocumentationBasics()
	{
		$this->createTestFor('MarkdownDocumentationBasics');
	}

	/**
	 * @test
	 * 
	 * Removed some empty lines in text. Changed some tabs to spaces. Most notable
	 * change though is that an extra space is added for nested lists that have no
	 * blank line before it.
	 */
	public function markdownDocumentationSyntax()
	{
		$this->createTestFor('MarkdownDocumentationSyntax');
	}

	/**
	 * @test
	 * 
	 * Removed indentation within the blockquote.
	 */
	public function nestedBlockquotes()
	{
		$this->createTestFor('NestedBlockquotes');
	}

	/**
	 * @test
	 * 
	 * Changed:
	 * *	Tab
	 *		*	Tab
	 *	 		*	Tab
	 * to:
	 * *	Tab
	 * 		 *	Tab and space
	 * 			 * Tab and space
	 * 
	 * This follows the difference with Markdown that list can be placed after text
	 * if it is indented, regardless of the list level. With Markdown indentation
	 * is not necessary in nested lists, but the first level must have a blank line.
	 * In my implementation there must not be a blank line in the first level, but
	 * all levels must have indentation if there is no blank line (indentation not
	 * necessary if there is a blank line).
	 */
	public function orderedAndUnorderedLists()
	{
		$this->createTestFor('OrderedAndUnorderedLists');
	}

	/**
	 * @test
	 * 
	 * Removed the underscore tests since I use a different implementation.
	 */
	public function strongAndEmTogether()
	{
		$this->createTestFor('StrongAndEmTogether');
	}
}