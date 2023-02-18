<?php 

$files = glob(__DIR__ . '/*/load.php');
foreach ($files as $file) {
  require_once $file;
}
