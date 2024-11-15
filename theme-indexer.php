<?php
function index_theme_directory($dir) {
    $result = array();
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value] = index_theme_directory($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $result[] = $value;
            }
        }
    }
    return $result;
}

$theme_dir = get_template_directory();
$index = index_theme_directory($theme_dir);

// Output the index as JSON
header('Content-Type: application/json');
echo json_encode($index, JSON_PRETTY_PRINT);