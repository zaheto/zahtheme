<?php
function scan_specific_directories($base_dir) {
    $result = array();

    // Scan functions.php
    if (file_exists($base_dir . '/functions.php')) {
        $result['functions.php'] = file_get_contents($base_dir . '/functions.php');
    }

    // Scan resources folder
    $resources_dir = $base_dir . '/resources';
    if (is_dir($resources_dir)) {
        $result['resources'] = array();

        // Scan scripts folder
        $scripts_dir = $resources_dir . '/scripts';
        if (is_dir($scripts_dir)) {
            $result['resources']['scripts'] = scandir($scripts_dir);
        }

        // Scan views folder
        $views_dir = $resources_dir . '/views';
        if (is_dir($views_dir)) {
            $result['resources']['views'] = array();
            $views = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($views_dir));
            foreach ($views as $view) {
                if ($view->isFile()) {
                    $path = str_replace($views_dir . '/', '', $view->getPathname());
                    $result['resources']['views'][] = $path;
                }
            }
        }
    }

    return $result;
}

$theme_dir = get_stylesheet_directory();
$scan_result = scan_specific_directories($theme_dir);

echo "<pre>";
print_r($scan_result);
echo "</pre>";