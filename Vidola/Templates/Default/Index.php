<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $document->currentPageTitle(); ?></title>
    <link href="<?php echo $document->urlTo('css/bootstrap.css'); ?>" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 10px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
      h1, h2, h3 {
      	margin-bottom: 25px;
      	margin-top: 25px;
      	border-bottom: 1px solid #ddd;
      }
    </style>
  </head>

  <body>

    <div class="container-fluid">
    	<div class="navbar">
            <div class="navbar-inner">
            	<div class="container">
            		<ul class="nav pull-right">
<?php if ($document->previousPageUrl()) { ?>
            <li><a href="<?php echo $document->previousPageUrl(); ?>">previous</a></li>
  			<li class="divider-vertical"></li>
<?php } ?>

<?php if ($document->nextPageUrl()) { ?>
            <li><a href="<?php echo $document->nextPageUrl(); ?>">next</a></li>
  			<li class="divider-vertical"></li>
<?php } ?>
            			<li><a href="<?php echo $document->startPageUrl(); ?>">start</a></li>
            		</ul>
            	</div>
            </div>
          </div>
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
<?php if ($document->pageHasToc()) { ?>
              <li class="nav-header">Table Of Contents</li>
              <li><?php echo $document->toc(); ?></li>
<?php } ?>
<?php if ($document->previousPageTitle()) { ?>
              <li class="nav-header">Previous Topic</li>
              <li><a href="<?php echo $document->previousPageUrl(); ?>"><?php echo $document->previousPageTitle(); ?></a></li>
<?php } ?>
<?php if ($document->nextPageTitle()) { ?>
              <li class="nav-header">Next Topic</li>
              <li><a href="<?php echo $document->nextPageUrl(); ?>"><?php echo $document->nextPageTitle(); ?></a></li>
<?php } ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <ul class="breadcrumb">
          		<?php foreach ($document->getBreadCrumbs() as $page) { ?>
        		<li><a href="<?php echo $document->getPageUrl($page); ?>"><?php echo $document->getPageTitle($page); ?></a>
        			<span class="divider">&raquo;</span></li>
        		<?php } ?>
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
        <p class="pull-right">build with Vidola, Twitter Bootstrap.</p>
      </footer>

    </div><!--/.fluid-container-->

  </body>
</html>