<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $document->pageName(); ?></title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 10px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
  </head>

  <body>

    <div class="container-fluid">
    	<div class="navbar">
            <div class="navbar-inner">
            	<div class="container">
            		<ul class="nav pull-right">
<?php if ($document->previousPageLink()) { ?>
            <li><a href="<?php echo $document->previousPageLink(); ?>">previous</a></li>
  			<li class="divider-vertical"></li>
<?php } ?>

<?php if ($document->nextPageLink()) { ?>
            <li><a href="<?php echo $document->nextPageLink(); ?>">next</a></li>
  			<li class="divider-vertical"></li>
<?php } ?>
            			<li><a href="<?php echo $document->startPageLink(); ?>">index</a></li>
            		</ul>
            	</div>
            </div>
          </div>
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Table Of Contents</li>
              <li><?php echo $document->toc(); ?></li>
<?php if ($document->previousPageLink()) { ?>
              <li class="nav-header">Previous Topic</li>
              <li><a href="<?php echo $document->previousPageLink(); ?>"><?php echo $document->previousPageName(); ?></a></li>
<?php } ?>
<?php if ($document->nextPageLink()) { ?>
              <li class="nav-header">Next Topic</li>
              <li><a href="<?php echo $document->nextPageLink(); ?>"><?php echo $document->nextPageName(); ?></a></li>
<?php } ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <ul class="breadcrumb">
        		<li><a href="">home</a><span class="divider">/</span></li>
        		<li><a href="">page1</a></li>
        	</ul>
          <div class="row-fluid">
            <div class="span12">
              <?php echo $document->currentPageContent(); ?>
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p class="pull-right">build with Twitter Bootstrap.</p>
      </footer>

    </div><!--/.fluid-container-->

  </body>
</html>