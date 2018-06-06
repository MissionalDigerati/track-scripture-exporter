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
$versions = [];
$booklets = [];
$languages = [];
$desiredBooklet = null;
$desiredLanguage = null;
if (file_exists($bookletsFile)) {
    $data = Yaml::parseFile($bookletsFile);
    $booklets = $data['booklets'];
}
$dbt = new Dbt(getenv('DBT_KEY'), null, null, 'array');
/**
 * Get the languages available
 */
$languages = $dbt->getLibraryLanguage();
/**
 * Set the current step
 */
$step = 1;
if ((count($_REQUEST) > 0) && (array_key_exists('next_step', $_REQUEST))) {
    $step = (int) $_REQUEST['next_step'];
}
if ((count($_REQUEST) > 0) && (array_key_exists('desired_booklet', $_REQUEST))) {
    foreach ($booklets as $booklet) {
        if ($_REQUEST['desired_booklet'] === $booklet['title']) {
            $desiredBooklet = $booklet;
            break;
        }
    }
}
if ((count($_REQUEST) > 0) && (array_key_exists('desired_language', $_REQUEST))) {
    foreach ($languages as $language) {
        if ($_REQUEST['desired_language'] === $language['language_code']) {
            $desiredLanguage = $language;
            break;
        }
    }
}
if ($step === 2) {
    /**
     * validate the given data
     */
    if (!$desiredLanguage) {
        $step = 1;
        $error = 'The language does not exist!';
    } else if (!$desiredBooklet) {
        $step = 1;
        $error = 'The booklet does not exist!';
    } else {
        $data = $dbt->getLibraryVolume(null, null, 'text', null, null, $desiredLanguage);
        foreach ($data as $version) {
            if (!array_key_exists($version['version_code'], $versions)) {
                $versions[$version['version_code']] = $version['volume_name'];
            }
        }
        if (count($versions) === 0) {
            $step = 1;
            $error = 'Sorry, but there are no Bible versions for that language.';
        }
    }
} elseif ($step === 3) {
    echo 'HERE';
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
                <label for="desired_booklet">Booklet:</label><br>
                <?php
                    foreach ($booklets as $index => $booklet) {
                        echo '<input type="radio" name="desired_booklet" value="' . $booklet['title'] . '"';
                        if ($index === 0) {
                            echo ' checked="checked"';
                        }
                        echo '> <strong>' . $booklet['title'] . '</strong>: ' . $booklet['description'] . '<br><br>';
                    }
                ?>
                <label for="desired_language">Language:</label><br>
                <select name="desired_language">
                    <?php
                        foreach ($languages as $language) {
                            echo '<option value="' . $language['language_code'] . '">' . $language['language_name'] . ' (' . $language['english_name'] . ' - ' . $language['language_code'] . ')</option>';
                        }
                    ?>
                </select><br><br>
                <input type="hidden" name="next_step" value="2">
                <input type="submit" value="Submit"><br><br>
            </form>
        <?php } elseif ($step === 2) { ?>
            <form action="/" name="track_scripture_step_2" method="POST">
                <label for="desired_version">Bible Version:</label><br>
                <select name="desired_version">
                    <?php
                        foreach ($versions as $key => $value) {
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        }
                    ?>
                </select><br><br>
                <input type="hidden" name="next_step" value="3">
                <input type="hidden" name="desired_booklet" value="<?php echo $desiredBooklet; ?>">
                <input type="hidden" name="desired_language" value="<?php echo $desiredLanguage; ?>">
                <input type="submit" name="submit" value="Submit"><br><br>
            </form>
        <?php } ?>
    </body>
</html>
