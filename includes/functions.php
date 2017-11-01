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

	function ordered_list($array,$parent_id = 0) {
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

?>