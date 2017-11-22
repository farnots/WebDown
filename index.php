<?php session_start(); ?>
<!doctype html>

<?php

require_once 'vendor/autoload.php';

include("./function.php");
include("./phpFileTree/php_file_tree.php");

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

if (!isset($_SESSION["view"])) {
  $_SESSION["view"]='list';
} else {
  if(isset($_GET['view']))
  {
  $_SESSION["view"]=$_GET['view'];
  }
}
$view_mode = $_SESSION["view"];


?>

<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="All my markdown file">
  <meta name="author" content="Lucas Tarasconi">
  <link rel="icon" href="#">

  <title>WebDown - <?php echo get_group($actual_path); ?></title>

  <!-- Bootstrap core CSS -->
  <link href="./css/perso.css" rel="stylesheet">

  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link href="./prism.css" rel="stylesheet" />
  <link href="./phpFileTree/styles/default/default.css" rel="stylesheet" type="text/css" media="screen" />


  <!-- Custom styles for this template -->
  <link href="starter-template.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="./index.php">WebDown</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
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
        ?>
        <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">

            <?php ariane($actual_path); ?>
          </ol>
        </nav>
        <article>
          <?php
          if ($path_parts['extension'] == 'md') {
            print_markdown($actual_path);
          } else {
            ?>
            <ul class="nav ">
            <h1><?php  
              echo basename($actual_path ); 
            ?></h1>
            <?php 
            view_mode($actual_path,$view_mode);
            ?></ul><?php
            if($view_mode == 'cards'){
            ?>
            <div class="row">
              <?php cards_view($actual_path); ?>
            </div>
            <?php } elseif ($view_mode == 'list'){ ?>
            <ul>
              <?php localFolder($actual_path); ?>
            </ul>
            <?php } ?>
            <div class="row">
              <div class="col">
                <h2>Last modified :</h2>
                <?php list_ordered($actual_path,'date') ?>
              </div>
              <div class="col">
                <h2>By name :</h2>
                <?php list_ordered($actual_path,'name') ?>
              </div>
            </div>
          </div> 
        </div>
        <?php
              }
    } 
    ?> 
  </article>  
  <br />
  <br />
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


