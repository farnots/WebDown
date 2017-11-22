<?php

spl_autoload_register(function($class){
  require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});

function get_group($dir)
{
  $str = substr($dir,2,strlen($dir)-2);
  $chunks = explode('/', $str);
  $i=0;
  $direction ='.';
  foreach ($chunks as $element) {
    if ($i == sizeof($chunks)-1) {
      break;
    } 
    else {
      $direction = $direction.'/'.$element;
      $i++;

    }
  }

  return $chunks[sizeof($chunks)-1];
}

function view_mode($dir,$view_mode)
{
  ?>
  <?php 
  if ($view_mode == 'list') {
    ?>
  <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link" href="index.php?dir=<?php echo $dir;?>&view=cards">cards view</a>
  </li>
  <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link disabled" href="#">list view</a>
  </li>
    <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link" href="index.php?dir=<?php echo $dir;?>&view=tree">tree view</a>
  </li>
  <?php
  } 
  elseif($view_mode == 'cards') {
     ?>
     <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link disabled" href="#">cards view</a>
  </li>
  <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link" href="index.php?dir=<?php echo $dir;?>&view=list">list view</a>
  </li>
  </li>
    <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link" href="index.php?dir=<?php echo $dir;?>&view=tree">tree view</a>
  </li>
    <?php
  }
  elseif($view_mode == 'tree') {
    ?>
     <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link " href="index.php?dir=<?php echo $dir;?>&view=cards">cards view</a>
  </li>
  <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link" href="index.php?dir=<?php echo $dir;?>&view=list">list view</a>
  </li>
  </li>
    <li class="nav-item" style="margin-top: 13px;">
    <a class="nav-link disabled" href="#">tree view</a>
  </li>

    <?php
  }
  

  ?>
  <?php
}

function list_ordered($actual_path,$type)
{
  $stack = array();
  array_of_file($actual_path,$stack); 

  if ($type == 'date') {
    usort($stack, "date_compare");
  } else {
    usort($stack, "name_compare");
  }

  ?> 
  <div class="list-group">
    <?php
    foreach ($stack as $key) {
      if ($key['type'] != 'dir') {

      ?>
      <a href="index.php?dir=<?php printf($key['dir']); ?>" class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1"><?php echo $key['title']; ?></h5>
          <small><span class="badge badge-primary badge-pill"><?php echo $key['group']; ?></span></small>
        </div>
        <small><?php echo date ("d/m/Y  H:i",$key['date']); ?></small>
        <?php
        echo "</a>";
      }
       }
      ?>
    </div>
    <?php
  }

  function print_markdown($file)
  {
    $text = file_get_contents($file);

    $parser = new \cebe\markdown\GithubMarkdown();
    $myHtmlContent = $parser->parse($text);

    $markupFixer  = new TOC\MarkupFixer();
    $tocGenerator = new TOC\TocGenerator();

    $htmlIn  = $markupFixer->fix($myHtmlContent);

    $buffer='';
    $nbrline=0;
    $ok = false;
    for ($i=0; $i < 1000 ; $i++) { 
      $line = readStrLine($htmlIn, $i);
      if ($line == '<p>[TOC]</p>') {
        $toc = "<div class='toc'><ul>" . $tocGenerator->getHtmlMenu($htmlIn) . "</ul></div>";
        echo $buffer;
        echo $toc;
        $ok = true;
        break;
      } else {
        $buffer = $buffer.$line;
        $nbrline++;
      }
    }
    if ($ok == false) {
      echo $htmlIn;
    } 
    $htmlOut = str_chop_lines($htmlIn,$nbrline);
    echo $htmlOut;
  }


  function array_of_file($dir,&$stack)
  {
    foreach (new DirectoryIterator($dir) as $file) {
      $group = get_group($dir);
      if ($file->isFile() && $file != '.DS_Store') {

        $path_parts = pathinfo($file);
        $data = array('title' => $path_parts['filename'],'date' => filemtime($dir.'/'.$file),'dir' => $dir."/".$file,'group'=>$group,'type'=>'file');
        array_push($stack, $data);

      }
      else if($file != '.' && $file != '..' && $file != '.DS_Store')
      {
        $data = array('title' => $file->getFilename(),'date' => filemtime($dir.'/'.$file),'dir' => $dir."/".$file,'group'=>$group,'type'=>'dir');
        array_push($stack, $data);
        array_of_file($dir."/".$file,$stack);

      }
    }
  }

  function cards_view($dir)
  {
    $stack = array();
    array_of_file($dir,$stack); 
    $group = get_group($dir);
    usort($stack,"type_compare");
    foreach ($stack as $key){
      if ($group == $key['group']){
        if($key['type']!='dir'){
        ?>
        <div class="col-lg-4 d-flex align-items-stretch">
        <a href="index.php?dir=<?php echo $key['dir']; ?>" class="card-link">
        <div class="card bg-secondary text-white" style="width: 20rem; margin-bottom: 20px;">
        <div class="card-body ">
            <strong><?php echo $key['title']; ?>&nbsp;<span class="badge badge-info">
          <?php echo $key['group']; ?></span></strong>
            <br/>
            <small><?php echo date ("d/m/Y  H:i",$key['date']); ?></small>
        </div>
        </div>
        </a>
        </div>  
        <?php
      }
      else {
         ?>
        <div class="col-lg-4 d-flex align-items-stretch">
        <a href="index.php?dir=<?php echo $key['dir']; ?>" class="card-link">
        <div class="card bg-primary text-white " style="width: 20rem; margin-bottom: 20px;">
          <div class="card-body ">
            <h5><?php echo $key['title']; ?><br/>
            <small><em><?php echo $key['group']; ?></em></small></h5>
        </div>
        </div>
      </a>
        </div>  
        <?php
      }
    }
    }
  }

  function date_compare($a, $b)
  {
    $t1 = $a['date'];
    $t2 = $b['date'];
    return $t2 - $t1;
  }    

  function name_compare($a, $b)
  {
    $val1 = strtolower(str_replace(' ', '', $a['title']));
    $val2 = strtolower(str_replace(' ', '', $b['title']));
    return strnatcmp($val1, $val2);
  } 

  function type_compare($a, $b)
  {
    $val1 = strtolower(str_replace(' ', '', $a['type']));
    $val2 = strtolower(str_replace(' ', '', $b['type']));
    return strnatcmp($val1, $val2);
  } 
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
    $stack = array();
    array_of_file($dir,$stack);
    usort($stack,"type_compare");
    $group = get_group($dir);
    foreach ($stack as $key){
      if ($group == $key['group']) {
       echo "<li>";
       if ($key['type'] == 'dir') 
       {
        ?>
        <a href="index.php?dir=<?php echo $key['dir'];?>"><strong><?php echo $key['title']; ?></strong></a>
        <?php
      } else {
        ?>
        <a href="index.php?dir=<?php echo $key['dir'];?>"><?php echo $key['title']; ?></a>
        <?php
        echo "<small>".date ("d/m/Y  H:i",$key['date'])."</small>";
      }
      echo "</li>";
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