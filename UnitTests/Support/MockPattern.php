<?php

namespace Vidola\UnitTests\Support;

class MockPattern implements \Vidola\Pattern\Pattern
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