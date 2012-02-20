<?php

namespace Vidola\UnitTests\Support;

Use \Vidola\Processor\TextProcessor;

abstract class MockTextReplacer implements \Vidola\TextReplacer\TextReplacer
{
	public function getExtension()
	{
		return 'html';
	}

	public function addPreProcessor(TextProcessor $processor)
	{}

	public function addPostProcessor(TextProcessor $processor)
	{}
}