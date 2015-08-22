<?php 
require_once 'init.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Distribution Management System</title>
</head>
<body>
		<div class="container">
			<br><br><br>
			<div class="col-md-6">
				<a href="./home.php">
                   <div class="panel panel-success">
                   		<div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span class="glyphicon glyphicon-hdd" style="font-size:48px;"></span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                          	<span class="pull-left">Pusat Kendali</span>
                          	<span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                            <div class="clearfix"></div>
                        </div> 
                    </div>
                   </a>
              </div>
              <div class="col-md-6">
              	<a href="./sa.php">
              		<div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span class="glyphicon glyphicon-globe" style="font-size:48px;"></span>
                                </div>

                            </div>
                        </div>
                            <div class="panel-footer">
                               	<span class="pull-left">Ketersediaan Pangan</span>
                               	<span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-6">
                	<a href="./tm.php">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span class="glyphicon glyphicon-road" style="font-size:48px;"></span>
                                </div>
                            </div>
                        </div>
                            <div class="panel-footer">
                                <span class="pull-left">Pergerakan Kendaraan</span>
                               	<span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
								<div class="clearfix"></div>    
                            </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-6">
                	<a href="./coll.php">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span class="glyphicon glyphicon-download-alt" style="font-size:48px;"></span>
                                </div>
                            </div>
                        </div>
                            <div class="panel-footer">
                                <span class="pull-left">Kolaborasi</span>
                               	<span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
								<div class="clearfix"></div>    
                            </div>
                    </div>
                    </a>
                </div>
        	</div>
		</div>
<script src="<?=JS_PATH?>jquery-2.1.1.min.js"></script>
<script src="<?=JS_PATH?>bootstrap.min.js"></script>
<link rel="stylesheet" href="<?=CSS_PATH?>bootstrap.min.css">
<link rel="stylesheet" href="<?=CSS_PATH?>custom.css">
</body></html>