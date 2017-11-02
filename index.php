<!DOCTYPE html>
<?php
// Include helper functions	
		if (is_file ("includes/functions.php") ) { include "includes/functions.php";}?>
<html lang="en">
<head>
    <title>Statistiken von HG Verwaltung</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/hgv.css">

    <script> // set clubname
        <?php echo 'var club = "' . $club . '";' ;  ?>
	</script>
	
	<?php 
	    // Include stats script page if file exists		
			if (is_file ("includes/analythics.php") ) { include "includes/analythics.php";}
		// Include hgv-includes		
			if (is_file ("includes/hgverwaltung.php") ) { include "includes/hgverwaltung.php";}
		// Include menu array		
		if (is_file ("includes/menu.php") ) { include "includes/menu.php";}	
	?>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="https://stats.hg.bramberg.ch?club=<?php echo $club ?>" target="_blank" title="Öffne Statistiken in neuem Fenster">  <span class="glyphicon glyphicon-new-window"></span></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <?php
        // Show the dynamic Menu
        $top_menu = bootstrap_menu($menu_items);
        echo $top_menu;
        ?>
      </ul> 
    </div>
  </div>
</nav>
  
<div class="container-fluid text-center">    
	 <?php // echo "DEBUG: Übergeben wurde die activePage ". ($_GET['page']); ?>
  <div class="row content">
    <div class="col-sm-10 text-left"> 
     <?php 
        // Include page if set		
		if (isset ($page))
		{
      
			// check if page name ist file or url (simplified)
			if (is_file($page) or (substr_compare($page, "http", 0, 4) == 0 )) {
				include ($page);
			} else {
				include "error.php";
			}		   
        }
        else
        {
            include "home.php";
        }
      
        ?>
    </div>
  </div>
</div>

	
<script>
	$(document).ready(function($){
		var url = window.location.href;
		$('.nav li a[href="'+url+'"]').addClass('active');
	});
</script>

<script>
	$(function(){

		var url = window.location.pathname, 
			urlRegExp = new RegExp(url.replace(/\/$/,'') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
			// now grab every link from the navigation
			$('.nav li a').each(function(){
				// and test its normalized href against the url pathname regexp
				if(urlRegExp.test(this.href.replace(/\/$/,''))){
					$(this).addClass('active');
				}
			});

	});
</script>
	
	
</body>
</html>