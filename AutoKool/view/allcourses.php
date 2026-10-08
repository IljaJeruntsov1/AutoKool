<?php
ob_start();
?>

<h1>Kõik kursused</h1>
<br>

<?php

foreach ($arr as $value) {
    echo "<h2>" . $value['title'] . "</h2>";

    echo "<p>" . $value['description'] . "</p>";

    echo "<b>" . $value['price'] . " €</b><br>";

    echo "<a href='course?id=" . $value['id'] . "'>Vaata kursust</a>";

    echo "<hr>";
}

$content = ob_get_clean();

include_once 'view/layout.php';
?>