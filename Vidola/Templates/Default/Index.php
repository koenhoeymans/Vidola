<html>
<head>
	<title><?php echo $page->title(); ?></title>
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

<?php echo $page->content(); ?>

<?php if ($page->previousPageName()) { ?>
	<a href="<?php echo $page->previousPageName(); ?>">previous</a>
<?php } ?>

<?php if ($page->nextPageName()) { ?>
	<a href="<?php echo $page->nextPageName(); ?>">next</a>
<?php } ?>

</body>
</html>