<?php
#exit;

$memcache= new Memcache;
$memcache->addServer("127.0.0.1", 11211);
if ( !$dom=$memcache->get($_GET['lon']."-".$_GET['lat'])) {
  include_once('inc/geophp/geoPHP.inc');

  $point = geoPHP::load("POINT(".floatval($_GET['lon'])." ".floatval($_GET['lat']).")",'wkt');


  foreach(scandir('shapes') as $file) {
    if (!is_dir($file)) {
      $shape = geoPHP::load(file_get_contents('shapes/'.$file),'json');
      if ($shape->pointInPolygon($point)) {
        $dom = strtok($file,".");
        break;
      }
    }
  }
  $dom = $dom ?: "default";
  $memcache->set($_GET['lon']."-".$_GET['lat'],$dom);
}
echo $dom;

?>