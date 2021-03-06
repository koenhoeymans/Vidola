Vidola Changelog
================

*	0.8.2

	*	Made it work with latest version of AnyMark.

*	0.8.1

	*	Taken HtmlFileUrlBuilder from AnyMark.
	*	Added RelativeInternalUrlBuilder interface.

*	0.8.0

	*	Using the new ElementTree\ElementTree package instead of AnyMarks one
		reflecting AnyMark changes.

*	0.7.1

	*	Renamed `TableOfContents::createTocNode` to `TableOfContents::createToc`.
	*	Creation of FileCopyController to handle copying of template files.
	*	Start of putting config options behind interfaces that can be passed to
		objects.

*	0.7.0

	*	Changes reflect the use of `\AnyMark\ComponentTree` components instead
		of the `PHP DOM`.

*	0.6.0

	*	A plugin architecture has been added. This allows for extending
		Vidola through the use of plugins.
	*	Vidola no longer adds the document element `<doc>` when saved to xml.

*	0.5.2

	*	Custom page titles specified in the toc were not applied. Now fixed.

*	0.5.1

	*	`copy` and `copy-excluded` buildfile option renamed to
		`copy-include` and `copy-exclude` respectively.

*	0.5.0

	*	Files and directories within the template directory are
		copied by default. Options to exclude and set exceptions
		on the excluded ones are present.

*	0.4.0

	*	Template api accepts a maximum depth for table of contents.

*	0.3.0

	*	Option to build documentation by specifying a `build` file.
	*	Including (and excluding) files and/or directories from the
		template for being copied automatically when generating the
		documentation.

*	0.2.0

	*	Works with `.md` files now.

*	0.1.1

	*	Fixed Autoloading of vendor libraries.

*	0.1.0

	*	Initial release.