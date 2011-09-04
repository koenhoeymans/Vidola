<?php

namespace Vidola\UnitTests\Support;

Use \Vidola\Processor\Processor;

abstract class MockTextReplacer implements \Vidola\TextReplacer\TextReplacer
{
	public function getExtension()
	{
		return 'html';
	}

	public function addPreProcessor(Processor $processor)
	{}

	public function addPostProcessor(Processor $processor)
	{}
}