<?php
/**
 * A script for exporter the Bible versers for specific tracks
 */
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
/**
 * Get unique settings
 */
$dotenv = new Dotenv();
$dotenv->load(ROOT_DIR . DIRECTORY_SEPARATOR . '.env');
/**
 * Get all the booklets from the YAML file
 */
$bookletsFile = ROOT_DIR . DIRECTORY_SEPARATOR . 'booklets.yaml';
$booklets = [];
$languages = [];
if (file_exists($bookletsFile)) {
    $data = Yaml::parseFile($bookletsFile);
    $booklets = $data['booklets'];
}
/**
 * Get the languages available
 */
$dbt = new Dbt(getenv('DBT_KEY'));
$data = $dbt->getLibraryLanguage();
if (count($data) > 0) {
    $languages = json_decode($data, true);
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
        <form action="/" name="track-scripture-step-1">
            <label for="desired-booklet">Booklet:</label><br>
            <?php
                foreach ($booklets as $index => $booklet) {
                    echo '<input type="radio" name="desired-booklet" value="' . $booklet['title'] . '"';
                    if ($index === 0) {
                        echo ' checked="checked"';
                    }
                    echo '> <strong>' . $booklet['title'] . '</strong>: ' . $booklet['description'] . '<br><br>';
                }
            ?>
            <label for="desired-language">Language:</label><br>
            <select name="desired-language">
                <?php
                    foreach ($languages as $language) {
                        echo '<option value="' . $language['language_code'] . '">' . $language['language_name'] . ' (' . $language['english_name'] . ')</option>';
                    }
                ?>
            </select><br><br>
            <input type="submit" value="Submit"><br><br>
        </form>
    </body>
</html>
