<?php
/**
 * A script for exporter the Bible versers for specific tracks
 */
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$error = '';
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
$dbt = new Dbt(getenv('DBT_KEY'));
/**
 * Get the languages available
 */
$data = $dbt->getLibraryLanguage();
if (count($data) > 0) {
    $languages = json_decode($data, true);
}
/**
 * Set the current step
 */
$step = 1;
if ((count($_REQUEST) > 0) && (array_key_exists('next_step', $_REQUEST))) {
    $step = (int) $_REQUEST['next_step'];
}
/**
 * STEP 1
 */
if ($step === 2) {
    $desiredBooklet = $_REQUEST['desired_booklet'];
    $desiredLanguage = $_REQUEST['desired_language'];
    /**
     * validate the given data
     */
    $exists = false;
    foreach ($booklets as $booklet) {
        if ($desiredBooklet === $booklet['title']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $step = 1;
        $error = 'The booklet does not exist!';
    }
    $exists = false;
    foreach ($languages as $language) {
        if ($desiredLanguage === $language['language_code']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $step = 1;
        $error = 'The language does not exist!';
    }
    if ($step === 2) {
        echo 'All Clear';
    }
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
        <?php if($error !== '') { ?>
            <p style="color: red; font-style: italic;"><?php echo $error; ?></p>
        <?php } ?>
        <?php if ($step === 1) { ?>
            <form action="/" name="track_scripture_step_1" method="POST">
                <label for="desired-booklet">Booklet:</label><br>
                <?php
                    foreach ($booklets as $index => $booklet) {
                        echo '<input type="radio" name="desired_booklet" value="' . $booklet['title'] . '"';
                        if ($index === 0) {
                            echo ' checked="checked"';
                        }
                        echo '> <strong>' . $booklet['title'] . '</strong>: ' . $booklet['description'] . '<br><br>';
                    }
                ?>
                <label for="desired-language">Language:</label><br>
                <select name="desired_language">
                    <?php
                        foreach ($languages as $language) {
                            echo '<option value="' . $language['language_code'] . '">' . $language['language_name'] . ' (' . $language['english_name'] . ')</option>';
                        }
                    ?>
                </select><br><br>
                <input type="hidden" name="next_step" value="2">
                <input type="submit" value="Submit"><br><br>
            </form>
        <?php } elseif ($step === 2) { ?>
            <form action="/" name="track_scripture_step_2" method="POST">
                <input type="submit" name="submit" value="Submit"><br><br>
            </form>
        <?php } ?>
    </body>
</html>
