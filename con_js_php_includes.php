<?php ob_start(); ?>
<? $webroot = $_SERVER['DOCUMENT_ROOT']; ?>
<!doctype$1
<?php
$replace = ob_get_contents();
ob_end_clean();
?>
<?php ob_start(); ?>
<? include($webroot . "/includes/mobilemenu.php"); ?>
<? include_once($webroot . "/includes/topmenu.php"); ?>
<? include_once($webroot . "/includes/bottommenu.php"); ?>
<?php
$newincs = ob_get_contents();
ob_end_clean();
$includes = explode("\n", $newincs);
$search = "/^<!doctype(.*?)\n/i";

echo "\n";
$files = getDirContents('oldsite');

$x = 0;

$mobmenu = '<script type="text/javascript">topmenu_mobile();</script>';
$topmenu = '<script type="text/javascript">topmenu();</script>';
$botmenu = '<script type="text/javascript">bottommenu();</script>';

foreach ($files as $key => $value) {
    if (substr($value, -4) == ".htm" || substr($value, -5) == ".html" ) {
		$findme = "DOCUMENT_ROOT";
		$current = file_get_contents($value);
		if(strpos($current, $findme) === false){
			#open current file
			$current = preg_replace($search,$replace,$current);
			$current = str_ireplace($mobmenu,$includes[0],$current);
			$current = str_ireplace($topmenu,$includes[1],$current);
			$current = str_ireplace($botmenu,$includes[2],$current);
			#write file
			file_put_contents($value,$current);
			echo "|";
			$x++;
		}
    }
}


echo "\n";
echo "done: Fixed $x files";
echo "\n";
echo "\n";

### Get all the URL's under specified directory getDirContents('dirname') Returns an array
function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}
