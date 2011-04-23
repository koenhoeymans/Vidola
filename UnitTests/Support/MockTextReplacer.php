<?php

namespace Vidola\UnitTests\Support;

abstract class MockTextReplacer implements \Vidola\TextReplacer\TextReplacer
{
	public function getExtension()
	{
		return 'html';
	}
}