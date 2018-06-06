<?php
use Symfony\Component\Yaml\Yaml;

define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
/**
 * Get all the booklets from the YAML file
 */
$bookletsFile = ROOT_DIR . DIRECTORY_SEPARATOR . 'booklets.yaml';
$booklets = [];
if (file_exists($bookletsFile)) {
    $data = Yaml::parseFile($bookletsFile);
    $booklets = $data['booklets'];
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Track Scripture Exporter</title>
    </head>
    <body>
        <h1>Track Scripture Exporter</h1>
        <form action="/" name="track-scripture-select">
            <label for="desired-booklet">Booklet</label><br>
            <?php
                foreach ($booklets as $booklet) {
                    echo '<input type="radio" name="desired-booklet" value="' . $booklet['title'] . '"> <strong>' . $booklet['title'] . '</strong>: ' . $booklet['description'] . '<br>';
                }
            ?>
        </form>
    </body>
</html>
