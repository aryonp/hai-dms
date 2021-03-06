<?php
require_once 'init.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link rel="stylesheet" href="<?=CSS_PATH?>style.css"/>

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=CSS_PATH?>bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=CSS_PATH?>dashboard.css" rel="stylesheet">
    <link href="<?=CSS_PATH?>style.css" rel="stylesheet">
      <script type="text/javascript"
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzIps0MIlxQF81hydOuid3z_or6pI_KOA">
		</script>
		
<script type="text/javascript">
			function initialize() {
				var styles = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}];
				var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});
				
				var myLatlng = new google.maps.LatLng(-6.7428626,108.554039);
				
				var mapOptions = {
					center: { lat: -6.7428626, lng: 108.554039},	
					zoom: 13,
					disableDefaultUI: true,
					scrollwheel : false
				};
				var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
				// To add the marker to the map, use the 'map' property
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					icon: '<?=IMG_PATH?>biasing.png'
				});
				
				
				google.maps.event.addListener(marker, 'click', function() {
					window.open("https://www.google.co.id/maps/place/Cirebon,+Kota+Cirebon,+Jawa+Barat/@-6.7428626,108.554039,13z/data=!3m1!4b1!4m2!3m1!1s0x2e6ee2649e6e5bbb:0x70a07638a7fe12fe",'_blank');
				  });
				
				map.mapTypes.set('map_style', styledMap);
				map.setMapTypeId('map_style');
			}
			google.maps.event.addDomListener(window, 'load', initialize);
		</script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
	          <img src="<?=IMG_PATH?>logo.png"/>
	          
	          
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        
  
   
          <form class="navbar-form navbar-right">
                <img src="<?=IMG_PATH?>search.png"/>
            <input type="text" class="form-control" placeholder="Input Kota">
          </form>
          
                 <ul class="nav navbar-nav navbar-right">
            <li><a href="#">BERAS</a></li>
            <li><a href="#">DAGING</a></li>
            <li><a href="#">GULA</a></li>
     
          </ul>
        </div>
      </div>
    </nav>

         </div>
      </div>
    <center>
    <div class="lokasi">
    Cirebon, Jawa Barat
    </div>
    <div class="theline">
    <img src="<?=IMG_PATH?>line.png"/>
    </div>
    <center id="map" style="background-color: white; margin:0px;padding:0px;">
    		<div id="map-canvas" style="height:700px"></div>
    </center>
    <div class="infoutama">
				<div class="ketersediaantext">
				KETERSEDIAAN PANGAN
				</div>
				
					<div class="infocircle">
					<center>
							<div class="barang">
							DAGING
							</div>
							
							<div class="jumlahbarang">
							-2000 KG
							</div>
					</center>
				</div>
				
					<div class="batas">
				
				</div>
				<div class="batasbox">
				</div>
				
				<div class="namakota">
				Cirebon
				</div>
				
				<div class="jumlahkk">
				Jumlah KK: 5000
				</div>
				
					<div class="konsumsiperbln">
				Konsumsi: 10.000 kg/bulan
				</div>
				
				<div class="jumlahgudang">
				Gudang: 5
				</div>
				
					<div class="konsumsiperbln">
				Total Stock: 8000kg/bulan
				</div>
				
					<div class="statustext">
					
					<span class="statustexts">
				Status:<span class="statuskota">
				Darurat
				</span>
					</span>
				
				</div>
				
					<div class="hargatext">
				Informasi harga per satuan:
				</div>
				
						<div class="hargabarang">
				130.000 IDR
				</div>
								<div class="batas">
				
				</div>
				    
    </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="<?=JS_PATH?>bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="<?=JS_PATH?>vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?=JS_PATH?>ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
