<?php 
require_once 'init.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>SATOEBOEMI - GEMAH RIPAH LOH JINAWI</title>
</head>
<body>
		<div class="container">
			<br><br><br>
			
			<center>
					<img src="<?=IMG_PATH?>logobesar.png" class="logodepan"/>
					
					<div class="gemahripah">- GEMAH RIPAH LOH JINAWI -</div>
					
			</center>
			
			<div class="col-md-6">
				<a href="./home.php">
                   <div class="panel panel-success">
                   		
                        <div class="panel-footer panel-pusat">
                          	<span class="pull-left"><img src="<?=IMG_PATH?>pusatkontrol.png"/>PUSAT KONTROL</span>
                            <div class="clearfix"></div>
                        </div> 
                    </div>
                   </a>
              </div>
              <div class="col-md-6">
              	<a href="./maps.php">
              		<div class="panel panel-info">
                       
                            <div class="panel-footer panel-pangan">
                               	<span class="pull-left pangantext"><img src="<?=IMG_PATH?>stokpangan.png" class="panganimg"/>STOK PANGAN</span>
                              
                                <div class="clearfix"></div>
                            </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-6">
                	<a href="./tm.php">
                    <div class="panel panel-warning">
                        
                            <div class="panel-footer panel-pusat">
                                <span class="pull-left"><img src=""/><img src="<?=IMG_PATH?>transport.png"/>MONITORING KENDARAAN</span>
                               	
								<div class="clearfix"></div>    
                            </div>
                    </div>
                    </a>
                </div>
                <div class="col-md-6 kolaborasi">
                	<a href="./coll.php">
                    <div class="panel panel-primary">
                        
                            <div class="panel-footer">
                            
                                <span class="pull-left"><img src="<?=IMG_PATH?>kolaborasi.png"/>KOLABORASI</span>
                               	
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
<link rel="stylesheet" href="<?=CSS_PATH?>style.css">
<link rel="stylesheet" href="<?=CSS_PATH?>custom.css">
</body></html>