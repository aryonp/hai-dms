<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$page_title?> - <?=PRODUCT?> <?=VERSION?></title>
	<script src="<?=JS_PATH?>jquery-2.1.1.min.js"></script>
	<script src="<?=JS_PATH?>bootstrap.min.js"></script>
	<script src="<?=JS_PATH?>moment.js"></script>
	<link rel="stylesheet" href="<?=CSS_PATH?>bootstrap.min.css">
	<link rel="stylesheet" href="<?=CSS_PATH?>custom.css"/>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><?=PRODUCT?> for <?=COMP_NAME?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
          	<li class="dropdown">
  				<a data-target="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    				Go To
    				<span class="caret"></span>
  				</a>

  				<ul class="dropdown-menu" role="menu">
            		<li>
            			<a href="./sc.php" >
            			<span class="glyphicon glyphicon-calendar"></span>
            				Schedule Centre
            			</a>
            		</li>
            		<li>
            			<a href="./tc.php" >
            			<span class="glyphicon glyphicon-list-alt"></span>
            				Ticket Centre
            			</a>
            		</li>
  				</ul>
            </li>
          	<li class="dropdown">
  				<a data-target="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    				<span class="glyphicon glyphicon-question-sign"></span>
    				<span class="caret"></span>
  				</a>

  				<ul class="dropdown-menu" role="menu">
            		<li>
            			<a href="./help_faq.php" >
            			<span class="glyphicon glyphicon-book"></span>
            				Help & FAQ
            			</a>
            		</li>
            		<li>
            			<a href="./sitemap.php" >
            			<span class="glyphicon glyphicon-list"></span>
            				Sitemap
            			</a>
            		</li>
            		<li>
            			<a href="./bug_rep.php">
            			<span class="glyphicon glyphicon-inbox"></span>
            			Report A Bug
            			</a>
            		</li>
            		<li class="divider"></li>
            		<li>
            			<a href="#" data-target="#aboutBox" data-toggle="modal">
            			<span class="glyphicon glyphicon-home"></span>
            			About
            			</a>
            		</li>
  				</ul>
            </li>
            <li class="dropdown">
  				<a data-target="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    				<span class="glyphicon glyphicon-user"></span>
    				&nbsp;<?=ucwords($_SESSION['fullname']);?>
    				<span class="caret"></span>
  				</a>

  				<ul class="dropdown-menu" role="menu">
            		<li>
            			<a href="./settings.php">
            			<span class="glyphicon glyphicon-cog"></span>
            				Change Password
            			</a>
            		</li>
            		<li class="divider"></li>
            		<li>
            			<a href="./logout.php">
            			<span class="glyphicon glyphicon-off"></span>
            			Logout
            			</a>
            		</li>
  				</ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	&nbsp;
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
         <?=nav_menu()?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">