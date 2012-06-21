<html>
<head>
	<title><?php echo $document->pageName(); ?></title>
	<style type="text/css">
body {
	font: 100% arial;
	width: 750px;
	margin: auto;
}

pre {
	margin: 10px 20px;
	padding: 10px;
	color: #fff;
	background-color: #000;
}

h1, h2, h3, h4, h5, h6 {
	font-family: georgia;
}

h1, h2 {
	text-transform: uppercase;
	color: #1080d0;
}

h1 {
	font-size: 140%;
}

h2 {
	font-size: 120%;
}

h3 {
	font-size: 110%;
}

a {
	color: red;
}
	</style>
</head>
<body>

<?php echo $document->currentPageContent(); ?>

<?php if ($document->previousPageLink()) { ?>
	<a href="<?php echo $document->previousPageLink(); ?>">previous: <?php echo $document->previousPageName(); ?></a>
<?php } ?>

<?php if ($document->nextPageLink()) { ?>
	<a href="<?php echo $document->nextPageLink(); ?>">next: <?php echo $document->nextPageName(); ?></a>
<?php } ?>

</body>
</html>