<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_CodeBlockTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Patterns\CodeBlock();
	}

	/**
	 * @test
	 */
	public function codeBlockIsWordCodeCapitalisedAndIndentedFollowedByColon()
	{
		$code = "\n\n    CODE:\n    the code\n\n";
		$html = "\n\n<pre><code>the code</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function angledBracketsAreReplacedWithEntities()
	{
		$code = "text\n\n    CODE:\n    a <tag>\n\n";
		$html = "text\n\n<pre><code>a &lt;tag&gt;</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function codeBlocksKeepIndentationAsOutlined()
	{
		$code = "\n\n\tCODE:\n\t\tThis is code.\n\n\t\tThis is also code.\n\t\t\tThis line is indented.";
		$html = "\n\n<pre><code>\tThis is code.\n\n\tThis is also code.\n\t\tThis line is indented.</code></pre>";
		$this->assertEquals($html, $this->pattern->replace($code));
	}
}