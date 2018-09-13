<?php
include_once('inc/geophp/geoPHP.inc');

$point = geoPHP::load("POINT(".floatval($_GET['lon'])." ".floatval($_GET['lat']).")",'wkt');


foreach(scandir('shapes') as $file) {
  if (!is_dir($file)) {
    $shape = geoPHP::load(file_get_contents('shapes/'.$file),'json');
    if ($shape->pointInPolygon($point)) {
      echo strtok($file,".");
      exit;
    }
  }
}
echo "default";
?>