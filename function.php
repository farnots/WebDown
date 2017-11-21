<?php

spl_autoload_register(function($class){
  require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});


function readStrLine($str, $n) {
  $lines = explode(PHP_EOL, $str);
  return $lines[$n-1];
}

function str_chop_lines($str, $lines = 4) {
  return implode("\n", array_slice(explode("\n", $str), $lines));
}

function ariane($actual_path)
{
  $str = substr($actual_path,2,strlen($actual_path)-2);
  $chunks = explode('/', $str);
  $i=0;
  $direction ='.';
  foreach ($chunks as $element) {
    if ($i == sizeof($chunks)-1) {
      break;
    } 
    else {
      $direction = $direction.'/'.$element;
      ?>
      <li class="breadcrumb-item">
        <a href="index.php?dir=<?php printf($direction) ?>">
          <?php printf($element); ?>
        </a>
      </li>
      <?php
      $i++;
    }

  }
  ?>
  <li class="breadcrumb-item active" aria-current="page"><?php printf($chunks[sizeof($chunks)-1]); ?></li><?php
}

function localFolder($dir)
{
  $dh = scandir($dir);
  $return = array();

  foreach ($dh as $folder) {
    if ($folder != '.' && $folder != '..' && $folder != '.DS_Store') {
      $path_parts = pathinfo($folder);
      ?>      
      <li class="nav-item">
        <a class="nav-link" href="index.php?dir=<?php printf(''.$dir.'/'.$folder); ?>">
          <?php printf($path_parts['filename']); ?>
        </a>
      </li>
      <?php
    }
  }
}

function onlyLocalFolder($dir)
{
  $dh = scandir($dir);
  foreach ($dh as $folder) {
    $path_parts = pathinfo($folder);

    if ($path_parts['extension'] == 'md') {
      break;
    } 
    else{
      if ($folder != '.' && $folder != '..' && $folder != '.DS_Store') {
        ?>      
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="index.php?dir=<?php printf(''.$dir.'/'.$folder); ?>" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php printf($folder); ?></a>
          <div class="dropdown-menu" aria-labelledby="dropdown01">
            <?php 
            $new_folder = $dir.'/'.$folder;
            $dh = scandir($new_folder);
            ?>

            <a class="dropdown-item" href="index.php?dir=<?php printf(''.$dir.'/'.$folder); ?>"><?php printf('<strong>'.$folder.'</strong>'); ?></a>

            <?php
            foreach ($dh as $folder2){
              $path_parts = pathinfo($folder2);
              if ($path_parts['extension'] == 'md') {
                break;
              } 
              else {
                if ($folder2 != '.' && $folder2 != '..' && $folder2 != '.DS_Store'){
                  ?><a class="dropdown-item" href="index.php?dir=<?php printf(''.$new_folder.'/'.$folder2); ?>"><?php printf($folder2); ?></a><?php
                }
              }
            }

            ?>
          </div>
        </li>
        <?php
      }
    }
  }
}

?>