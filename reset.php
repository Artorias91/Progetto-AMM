<?php 
  $delete = "rm -r /home/amm/repoAmm/amm2014/continiMattia/";
  echo shell_exec($delete . "README.md");
  echo shell_exec($delete . "Settings.php");
  echo shell_exec($delete . "index.php");
  echo shell_exec($delete . "pizzaClick.sql"); 
  echo shell_exec($delete . "amm14Application");
  echo shell_exec($delete . "css"); 
  echo shell_exec($delete . "img"); 
  echo shell_exec($delete . "js");
  echo shell_exec($delete . "lib"); 
  echo shell_exec($delete . "nbproject"); 
  echo shell_exec($delete . "php");
  echo shell_exec($delete . ".htaccess");
?>
