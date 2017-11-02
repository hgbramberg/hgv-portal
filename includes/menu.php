<?php 
// the Menu Array
$menu_items = array(
    array(
        "id" => "0", 
        "url" => '?page=home.php', 
        "parent_id" => "0", 
        "name" => 'Home', 
        "order" => "0",

    ),
    array(
        "id" => "100", 
        "url" => '', 
        "parent_id" => "0", 
        "name" => 'Agenda', 
        "order" => "20",

    ),
    array(
        "id" => "110", 
        "url" => '?page=pages/agenda.php', 
        "parent_id" => "100", 
        "name" => 'Anlässe', 
        "order" => "20",

    ),  
    array(
        "id" => "120", 
        "url" => '?page=pages/spiele.php', 
        "parent_id" => "100", 
        "name" => 'Spiele', 
        "order" => "20",

    ),
    array(
        "id" => "130", 
        "url" => '?page=pages/kombi_agenda.php', 
        "parent_id" => "100", 
        "name" => 'Jahresprogramm', 
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
        "name" => 'Einzelschläger', 
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
        "id" => "340", 
        "url" => '?page=pages/rangpunkte.php', 
        "parent_id" => "300", 
        "name" => 'Rangpunkte', 
        "order" => "20",

    ),
    array(
        "id" => "400", 
        "url" => '?page=pages/home.php', 
        "parent_id" => "0", 
        "name" => 'Mannschaft', 
        "order" => "20",

    ),
    array(
        "id" => "410", 
        "url" => '?club=bramberg&page=https://hgverwaltung.ch/embed/1/mannschaftsdurchschnitt.html', 
        "parent_id" => "400", 
        "name" => 'Mannschaftsdurchschnitt', 
        "order" => "20",

    ),
    array(
        "id" => "420", 
        "url" => '?club=bramberg&page=https://hgverwaltung.ch/embed/1/mannschaftstreiche.html', 
        "parent_id" => "400", 
        "name" => 'Mannschaftsstreiche', 
        "order" => "20",

    ),
    array(
        "id" => "440", 
        "url" => '?club=bramberg&page=https://hgverwaltung.ch/embed/1/streichegrafisch.html', 
        "parent_id" => "400", 
        "name" => 'Streichegrafisch', 
        "order" => "20",

    ),
    array(
        "id" => "900", 
        "url" => '?page=pages/about.php', 
        "parent_id" => "0", 
        "name" => 'About', 
        "order" => "20",

    ),
    array(
        "id" => "990", 
        "url" => '?page=pages/changelog.php', 
        "parent_id" => "900", 
        "name" => 'Changelog', 
        "order" => "20",

    )
);
?>