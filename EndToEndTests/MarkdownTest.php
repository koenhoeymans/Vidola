<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_MarkdownTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{}

	public function teardown()
	{}

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
	 * changed <p>6 > 5.</p> to <p>6 &gt; 5.</p> as expected outcome
	 */
	public function ampsAndAngleEncoding()
	{
		$this->createTestFor('AmpsAndAngleEncoding');
	}

	/**
	 * @test
	 * changed some formatting
	 */
	public function autoLinks()
	{
		$this->createTestFor('AutoLinks');
	}

	/**
	 * @test
	 * changed some formatting
	 */
	public function backslashEscapes()
	{
		$this->createTestFor('BackslashEscapes');
	}

	/**
	 * @test
	 * changed some formatting
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
}