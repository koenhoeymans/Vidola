<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_NoteTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->note = new \Vidola\Pattern\Patterns\Note();
	}

	public function getPattern()
	{
		return $this->note;
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

		$domDocument = new \DOMDocument();
		$domElement = $domDocument->createElement('div', 'This is a note.');
		$domElement->setAttribute('class', 'note');
		$domDocument->appendChild($domElement);
		$this->assertCreatesDomFromText($domElement, $text);
	}
}