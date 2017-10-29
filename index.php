<!DOCTYPE html>
<?php 
	// Save club name during session
        session_start();
        if (isset ($_GET['club']))
        $_SESSION['club'] = $_GET['club'];
        $club = $_SESSION['club'];

	// Save page name
		if (isset ($_GET['page']))
		$page = $_GET['page'];

	// Functions for Submenu generation
		function curPageURL() {
			 $pageURL = '';

			 if ($_SERVER["SERVER_PORT"] != "80") {
			  $pageURL .= $_SERVER["REQUEST_URI"];
			 } else {
			  $pageURL .= $_SERVER["REQUEST_URI"];
			 }
			 return $pageURL;
			}

		function ordered_list($array,$parent_id = 0)
			{
			  $temp_array = array();
			  foreach($array as $element)
			  {
				if ($element['parent_id'] == $parent_id)
				{
				  $element['subs'] = $ordered_list($array, $element['id']);
				  $temp_array[] = $element;
				}
			  }
			  return $temp_array;
			}

		function bootstrap_menu($array,$parent_id = 0,$parents = array()) {
				if($parent_id==0)
				{
					foreach ($array as $element) {
						if (($element['parent_id'] != 0) && !in_array($element['parent_id'],$parents)) {
							$parents[] = $element['parent_id'];
						}
					}
				}
				$menu_html = '';
				foreach($array as $element)
				{

					if($element['parent_id']==$parent_id)
					{
						if(in_array($element['id'],$parents))
						{


							$menu_html .= '<li class="dropdown">';
							$menu_html .= '<a href="'.$element['url'].'" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">'.$element['name'].' <span class="caret"></span></a>';
						}
						else   {
						 $pagenameurl=$element['url'];
						 $pagename= curPageURL();
						 $host =$_SERVER['REQUEST_URI'];
						 $pages = 'home.php';


						if($host==$pagenameurl){  $act="active";} else { $act=""; }
							$menu_html .= '<li class='.$act.'>';
                            $menu_html .= '<a href="' . $element['url'] . '">' . $element['name'] . '</a>';
						}
						if(in_array($element['id'],$parents))
						{
							$menu_html .= '<ul class="dropdown-menu" role="menu">';
							$menu_html .= bootstrap_menu($array, $element['id'], $parents);
							$menu_html .= '</ul>';
						}
						$menu_html .= '</li>';
					}
				}
				return $menu_html;
			}

// the Menu Array
		$menu_items = array(
		array(
			"id" => "1", 
			"url" => '?page=home.php', 
			"parent_id" => "0", 
			"name" => 'Home', 
			"order" => "0",

		),
		array(
			"id" => "2", 
			"url" => '?page=pages/spiele.php', 
			"parent_id" => "0", 
			"name" => 'Agenda', 
			"order" => "20",

		), 
		array(
			"id" => "20", 
			"url" => '?page=pages/spieleMitResultatTingle.php', 
			"parent_id" => "0", 
			"name" => 'Spiele', 
			"order" => "20",

		), 
		array(
			"id" => "300", 
			"url" => '', 
			"parent_id" => "0", 
			"name" => 'Durchschnitt', 
			"order" => "0",

		), 
		array(
			"id" => "310", 
			"url" => '?page=pages/schnitt.php', 
			"parent_id" => "300", 
			"name" => 'Durchschnitt', 
			"order" => "0",

		), 
		array(
			"id" => "320", 
			"url" => '?page=pages/schnittsaison.php', 
			"parent_id" => "300", 
			"name" => 'Saisonverlauf', 
			"order" => "10",

		),  
		array(
			"id" => "330", 
			"url" => '?page=pages/schnittentwicklung.php', 
			"parent_id" => "300", 
			"name" => 'letze 5 Jahre', 
			"order" => "10",

		),  
		array(
			"id" => "400", 
			"url" => '?page=pages/rangpunkte.php', 
			"parent_id" => "0", 
			"name" => 'Rangpunkte', 
			"order" => "20",

		)
	);
?>
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
			if (is_file ("includes/analythics.php") ) {
				include "includes/analythics.php";
			}
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
			// check if page name ist file
			if (is_file($page)) {
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

	  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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