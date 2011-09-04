<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_TableOfContentsTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->headerFinder =
			$this->getMockBuilder('\\Vidola\\Patterns\\TableOfContents\\HeaderFinder')
					->disableOriginalConstructor()
					->getMock();
		$this->fileRetriever =
			$this->getMockBuilder('\\Vidola\\Util\\FileRetriever')
					->disableOriginalConstructor()
					->getMock();
		$this->toc = new \Vidola\Patterns\TableOfContents(
			$this->headerFinder,
			$this->fileRetriever
		);
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
		$this->assertEquals("{{ul}}
	{{li}}
		{{a href=\"#header\"}}header{{/a}}
	{{/li}}
{{/ul}}

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
"{{ul}}
	{{li}}
		{{a href=\"#header\"}}header{{/a}}
{{ul}}
	{{li}}
		{{a href=\"#subheader\"}}subheader{{/a}}
	{{/li}}
{{/ul}}
	{{/li}}
{{/ul}}

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
"{{ul}}
	{{li}}
		{{a href=\"#header1a\"}}header1a{{/a}}
{{ul}}
	{{li}}
		{{a href=\"#header2a\"}}header2a{{/a}}
	{{/li}}
	{{li}}
		{{a href=\"#header2b\"}}header2b{{/a}}
	{{/li}}
{{/ul}}
	{{/li}}
	{{li}}
		{{a href=\"#header1b\"}}header1b{{/a}}
	{{/li}}
{{/ul}}

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

{{ul}}
	{{li}}
		{{a href=\"#subheader\"}}subheader{{/a}}
	{{/li}}
{{/ul}}

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

{{ul}}
	{{li}}
		{{a href=\"#subheader\"}}subheader{{/a}}
	{{/li}}
{{/ul}}

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
"{{ul}}
	{{li}}
		{{a href=\"#header\"}}header{{/a}}
	{{/li}}
{{/ul}}

header
---

paragraph

subheader
===

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function addingFileNameIncludesHeadersFromThatFileAfterCurrentDocumentHeaders()
	{
		// given
		$text =
"table of contents:

	Includedfile

header
----

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1)
			)));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'included header', 'level' => 1)
			)));
		$this->fileRetriever
			->expects($this->any())
			->method('retrieveContent')
			->with('Includedfile')
			->will($this->returnValue(
"included header
----

some text"
			));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"{{ul}}
	{{li}}
		{{a href=\"#header\"}}header{{/a}}
	{{/li}}
	{{li}}
		{{a href=\"Includedfile.html#included_header\"}}included header{{/a}}
	{{/li}}
{{/ul}}

header
----

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function firstEncounteredHeaderInCurrentDocumentDeterminesHighestLevel()
	{
		// given
		$text =
"level1
===

table of contents:

	Includedfile

level2
---

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level2', 'level' => 2)
			)));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level3', 'level' => 3)
			)));
		$this->fileRetriever
			->expects($this->any())
			->method('retrieveContent')
			->with('Includedfile')
			->will($this->returnValue(
"level3
+++

some text"
			));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"level1
===

{{ul}}
	{{li}}
		{{a href=\"#level2\"}}level2{{/a}}
{{ul}}
	{{li}}
		{{a href=\"Includedfile.html#level3\"}}level3{{/a}}
	{{/li}}
{{/ul}}
	{{/li}}
{{/ul}}

level2
---

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function moreThanOneFileCanBeSpecified()
	{
		// given
		$text =
"table of contents:

	Includedfile1
	Includedfile2

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level1a', 'level' => 1)
			)));
		$this->headerFinder
			->expects($this->at(2))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level1b', 'level' => 1)
			)));
		$this->fileRetriever
			->expects($this->at(0))
			->method('retrieveContent')
			->with('Includedfile1')
			->will($this->returnValue(
"level1a
+++

some text"
			));
		$this->fileRetriever
			->expects($this->at(1))
			->method('retrieveContent')
			->with('Includedfile2')
			->will($this->returnValue(
"level1b
+++

some text"
			));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"{{ul}}
	{{li}}
		{{a href=\"Includedfile1.html#level1a\"}}level1a{{/a}}
	{{/li}}
	{{li}}
		{{a href=\"Includedfile2.html#level1b\"}}level1b{{/a}}
	{{/li}}
{{/ul}}

paragraph",
			$result
		);
	}

	/**
	 * @test
	 */
	public function usesTocOfIncludedFiles()
	{
		// given
		$text =
"table of contents:

	Includedfile

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(2))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1)
			)));
		$this->fileRetriever
			->expects($this->at(0))
			->method('retrieveContent')
			->with('Includedfile')
			->will($this->returnValue(
"table of contents:

	Subincludedfile

paragraph"
			));
		$this->fileRetriever
			->expects($this->at(1))
			->method('retrieveContent')
			->with('Subincludedfile')
			->will($this->returnValue(
"header
---

some text"
			));

		// when
		$result = $this->toc->replace($text);

		// then
		$this->assertEquals(
"{{ul}}
	{{li}}
		{{a href=\"Subincludedfile.html#header\"}}header{{/a}}
	{{/li}}
{{/ul}}

paragraph",
			$result
		);
	}
}