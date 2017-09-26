<!DOCTYPE html>
<?php 
        // Save club name during session
        session_start();
        if (isset ($_GET['club']))
        $_SESSION['club'] = $_GET['club'];
        $club = $_SESSION['club'];
?>
<html lang="en">
<head>
    <title>Statistiken von HG Verwaltung</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
        margin-bottom: 0;
        border-radius: 0;
    }

    .active {
            font-weight: bold;
        }
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}

    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
        .row.content {height:auto;} 
    }
    </style>

    <script> // set clubname
        <?php echo 'var club = "' . $club . '";' ;  ?>
    </script>
</head>
<body>

<?php
// TopBar Menu defines
$menuitems = array(
  'Spiellisten'     => array('text'=>'Spiele',          'url'=>'spieleMitResultatTingle.php'),
  'Durchschnitt'    => array('text'=>'Durchschnitt',    'url'=>'schnittsaison.php'),
  'Rangpunkte'      => array('text'=>'Rangpunkte',      'url'=>'rangpunkte.php'),
  'Streiche'        => array('text'=>'Streiche',        'url'=>'schnitt.php'),
);
?>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="https://stats.hg.bramberg.ch" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <?php
        // Dynamic Menu
        foreach ($menuitems as $menuitem)
        {
            if (isset($_GET['page']) && $_GET['page'] == $menuitem['url'])
            {
                echo '<li><a href="?page=' . $menuitem['url'] . '" class="active"> ' . $menuitem['text'] . '</a></li>';
              
                $activePage = $menuitem['url'];
            }
            else
            {
                echo '<li><a href="?page=' . $menuitem['url'] . '"> ' . $menuitem['text'] . '</a></li>';
            }
        }
        ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="https://www.hgverwaltung.ch" target="_blank"> <span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
    </div>
  </div>
</nav>
  
<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-10 text-left"> 
     <?php 
        // Include page
        if (isset($activePage))
        {
            include $activePage;   
        }
        else
        {
            include "home.php";
        }
        
        ?>
    </div>
  </div>
</div>

</body>
</html>