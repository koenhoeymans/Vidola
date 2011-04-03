<?php

namespace Vidola\UnitTests\Support;

class MockPattern implements \Vidola\Patterns\Pattern
{
	public function replace($text)
	{
		return preg_replace(
			"#mockpattern#",
			"<mock>mockpattern</mock>",
			$text
		);
	}
}