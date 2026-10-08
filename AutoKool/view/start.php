<?php
ob_start();
?>

<h1>Попulaarsed kursused</h1>
<br>

<?php

foreach ($arr as $value) {
    echo "<h2>" . $value['title'] . "</h2>";

    echo "<p>" . $value['description'] . "</p>";

    echo "<p><b>Hind:</b> " . $value['price'] . " €</p>";

    echo "<p><b>Kestus:</b> " . $value['duration'] . "</p>";

    echo "<a href='course?id=" . $value['id'] . "'>Vaata kursust</a>";

    echo "<hr>";
}

$content = ob_get_clean();

include_once 'view/layout.php';
?>