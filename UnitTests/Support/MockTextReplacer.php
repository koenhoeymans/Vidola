<?php

namespace Vidola\UnitTests\Support;

class MockTextReplacer implements \Vidola\TextReplacer\TextReplacer
{
	public function getExtension()
	{
		return 'html';
	}

	public function replace($text)
	{}
}