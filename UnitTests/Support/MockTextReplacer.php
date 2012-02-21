<?php

namespace Vidola\UnitTests\Support;

Use \Vidola\Processor\TextProcessor;
Use \Vidola\Processor\DomProcessor;

abstract class MockTextReplacer implements \Vidola\TextReplacer\TextReplacer
{
	public function getExtension()
	{
		return 'html';
	}

	public function addPreTextProcessor(TextProcessor $processor)
	{}

	public function addPostTextProcessor(TextProcessor $processor)
	{}

	public function addPreDomProcessor(DomProcessor $processor)
	{}

	public function addPostDomProcessor(DomProcessor $processor)
	{}
}