<?php
    // Anzeige der CHANGELOG.md
    require "includes/parsedown/Parsedown.php";
    $file = file_get_contents('CHANGELOG.md');
    $Parsedown = new Parsedown();
    echo $Parsedown->text($file);
    ?>