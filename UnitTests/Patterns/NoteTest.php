<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_NoteTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->note = new \Vidola\Patterns\Note();
	}

	/**
	 * @test
	 */
	public function noteIsCreatedByUsingNoteIndented()
	{
		$text = "This is a paragraph.\n\n\tnote: This is a note.\n\nAnother paragraph.";
		$html = "This is a paragraph.\n\n\t<div class=\"note\">This is a note.</div>\n\nAnother paragraph.";
		$this->assertEquals(
			$html, $this->note->replace($text)
		);
	}
}