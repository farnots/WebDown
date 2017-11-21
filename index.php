<!doctype html>

<?php 
include("./function.php");
include("./phpFileTree/php_file_tree.php");
use \Michelf\Markdown;
use \Michelf\MarkdownExtra;


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$dir = "./Notes";
$type = $_GET['type'];
if (!isset($type)) 
{
  $type = 'none';
}
$actual_path = $_GET['dir'];
if (!isset($actual_path)) 
{
  $actual_path = $dir;
}


?>

<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="All my markdown file">
  <meta name="author" content="Lucas Tarasconi">
  <link rel="icon" href="#">

  <title>Worksite - <?php echo $actual_path; ?></title>

  <!-- Bootstrap core CSS -->
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link href="./css/perso.css" rel="stylesheet">
  <link href="./prism.css" rel="stylesheet" />
  <link href="./phpFileTree/styles/default/default.css" rel="stylesheet" type="text/css" media="screen" />


  <!-- Custom styles for this template -->
  <link href="starter-template.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="./index.php">Worksite</a>
  </button>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <?php 
      onlyLocalFolder($dir);
      ?>
      <li class="nav-item">
        <a class="nav-link" href="index.php?type=tree">
          Tree folder
        </a>
      </li>
    </div>
  </nav>
    <div class="container">
    <?php 

    if ($type == 'tree') {
      ?>
      <h1>Tree folder</h1>
      <?php
      $allowed = array("gif", "jpg", "jpeg", "png","md","markdown");
      echo php_file_tree("./Notes", "./index.php?dir=[link]",$allowed);
    } else {
      $path_parts = pathinfo($actual_path);
      if ($path_parts['extension'] == 'md') {
        $text = file_get_contents($actual_path);
        $html = MarkdownExtra::defaultTransform($text);
        echo $html; 
      }

      else {
        ?>
        <h1><?php  echo basename($actual_path ); ?></h1>
        <ul>
          <?php
          localFolder($actual_path); 
        } 
        ?>
         </ul>
      <?php } ?>   
</div>


    <!-- Bootstrap core JavaScript
      ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="./js/vendor/jquery.min.js"><\/script>')</script>
      <script src="./phpFileTree/php_file_tree.js" type="text/javascript"></script>
      <script src="prism.js"></script>
      <script src="./js/vendor/popper.min.js"></script>
      <script src="./js/bootstrap.min.js"></script>
    </body>
    </html>


