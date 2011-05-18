<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_TableOfContentsTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->headerFinder = $this->getMockBuilder('\\Vidola\\Patterns\\HeaderFinder')
									->disableOriginalConstructor()
									->getMock();
		$this->toc = new \Vidola\Patterns\TableOfContents($this->headerFinder);
	}

	/**
	 * @test
	 */
	public function createsLocalToc()
	{
		// given
		$text = "table of contents:

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1))
			));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals("<ul>
	<li>
		<a href=\"#header\">header</a>
	</li>
</ul>

header
----

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function respectsLevelOfHeadersThroughSublists()
	{
		// given
		$text = "table of contents:

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1),
				array('title' => 'subheader', 'level' => 2)
			)));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"<ul>
	<li>
		<a href=\"#header\">header</a>
<ul>
	<li>
		<a href=\"#subheader\">subheader</a>
	</li>
</ul>
	</li>
</ul>

header
----

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function createsNestedToc()
	{
				// given
		$text = "table of contents:

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header1a', 'level' => 1),
				array('title' => 'header2a', 'level' => 2),
				array('title' => 'header2b', 'level' => 2),
				array('title' => 'header1b', 'level' => 1)
			)));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"<ul>
	<li>
		<a href=\"#header1a\">header1a</a>
<ul>
	<li>
		<a href=\"#header2a\">header2a</a>
	</li>
	<li>
		<a href=\"#header2b\">header2b</a>
	</li>
</ul>
	</li>
	<li>
		<a href=\"#header1b\">header1b</a>
	</li>
</ul>

header
----

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function tocIsLimitedToItsSectionDeterminedByHeaderLevel()
	{
		// given
		$text = 
"header
---

paragraph

table of contents:

subheader
===

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'subheader', 'level' => 2)
			)));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"header
---

paragraph

<ul>
	<li>
		<a href=\"#subheader\">subheader</a>
	</li>
</ul>

subheader
===

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function tocOfSectionStopsAtSectionWithHigherLevelHeader()
	{
		// given
		$text = 
"header
---

paragraph

table of contents:

subheader
===

paragraph

other header
---

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'subheader', 'level' => 2),
				array('title' => 'other header', 'level' => 1)
			)));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"header
---

paragraph

<ul>
	<li>
		<a href=\"#subheader\">subheader</a>
	</li>
</ul>

subheader
===

paragraph

other header
---

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function depthOptionLimitsDepthOfToc()
	{
				// given
		$text = 
"table of contents:
	depth: 1

header
---

paragraph

subheader
===

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1),
				array('title' => 'subheader', 'level' => 2)
			)));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"<ul>
	<li>
		<a href=\"#header\">header</a>
	</li>
</ul>

header
---

paragraph

subheader
===

paragraph",
			$result
		);
	}
}