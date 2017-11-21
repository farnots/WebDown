<!doctype html>

<?php 
require_once 'vendor/autoload.php';

include("./function.php");
include("./phpFileTree/php_file_tree.php");

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

  <title>WebDown - <?php echo get_group($actual_path); ?></title>

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
          $text = file_get_contents($actual_path);

          $parser = new \cebe\markdown\GithubMarkdown();
          $myHtmlContent = $parser->parse($text);

          $markupFixer  = new TOC\MarkupFixer();
          $tocGenerator = new TOC\TocGenerator();

          $htmlOut  = $markupFixer->fix($myHtmlContent);         

          $buffer='';
          $nbrline=0;
          $ok = false;
          for ($i=0; $i < 1000 ; $i++) { 
            $line = readStrLine($htmlOut, $i);
            if ($line == '<p>[TOC]</p>') {
              $toc = "<div class='toc'><ul>" . $tocGenerator->getHtmlMenu($htmlOut) . "</ul></div>";
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
            echo $htmlOut;
          } 
          else {
            $htmlOut = str_chop_lines($htmlOut,$nbrline);
            echo $htmlOut;
          }          
          

        }

        else {
          ?>
          <h1><?php  echo basename($actual_path ); ?></h1>
          <ul>
            <?php
            $info = false;
            if ($actual_path == './Notes') {
              localFolder($actual_path);
              $info =true; 
            } else {
             localFolder($actual_path); 
           }

         } 
         ?>
       </ul>
       <?php } 
       if ($info == true) {
        ?>
        <div class="row">
          <div class="col">
            <h2>Last modified :</h2>
            <?php

            $stack = array();
            last_modified("./Notes",$stack); 

            usort($stack, "date_compare");

            ?> 
            <div class="list-group">
              <?php
              foreach ($stack as $key) {
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
                ?>
              </div>

            </div>
            <div class="col">
              <h2>By name :</h2>
              <?php 
              usort($stack,"name_compare");

              ?> 
            <div class="list-group">
              <?php
              foreach ($stack as $key) {
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
                ?>
              </div>
                            </div>
            </div> 
          </div>
          <?php
        } 
        ?> 
      </article>  
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


