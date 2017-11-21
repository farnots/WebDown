<?php 
include 'Parsedown.php';

function localFolder($dir)
{
	$dh = scandir($dir);
	$return = array();

	foreach ($dh as $folder) {
		if ($folder != '.' && $folder != '..' && $folder != '.DS_Store') {
			?>
			<p><a href="test.php?dir=<?php printf(''.$dir.'/'.$folder); ?>"><?php printf(''.$dir.'/'.$folder); ?></a></p>
			<?php
					                
		}
	}
}


?>
	<h1><a href="test.php">INDEX</a></h1>
<?php

$dir = "./Notes";
$actual_path = $_GET['dir'];
if (!isset($actual_path)) 
{
	$actual_path = $dir;
}

$Parsedown = new Parsedown();
$path_parts = pathinfo($actual_path);
if ($path_parts['extension'] == 'md') {
	$handle = file_get_contents($actual_path);
	echo $Parsedown->text($handle);
} else {
	printf($actual_path);
	printf(localFolder($actual_path));
}



?>