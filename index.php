<!doctype html>

<?php 
spl_autoload_register(function($class){
  require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});

include("./phpFileTree/php_file_tree.php");
use \Michelf\Markdown;
use \Michelf\MarkdownExtra;


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


$dir = "./Notes";
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
      </div>
    </nav>
    <div id="wrapper">
    <div id="sidebar-wrapper">
          <?php 
         $allowed = array("gif", "jpg", "jpeg", "png","md","markdown");
         echo php_file_tree("./Notes", "./index.php?dir=[link]",$allowed);
         ?>
    </div>
    <div id="page-content-wrapper">
            <div class="page-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php 

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
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


