<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_NoteTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->note = new \Vidola\Pattern\Patterns\Note();
	}

	/**
	 * @test
	 */
	public function noteIsCreatedByUsingNoteAndTheTextOnNextLineIndented()
	{
		$text =
"This is a paragraph.

!Note
	This is a note.

Another paragraph.";

		$html =
"This is a paragraph.

{{div class=\"note\"}}
This is a note.
{{/div}}

Another paragraph.";

		$this->assertEquals(
			$html, $this->note->replace($text)
		);
	}
}