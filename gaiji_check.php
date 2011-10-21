<?php

include(dirname(__FILE__)."/inc/gaiji.image.wedding.php");

$char = $_GET["d"];
$file = file(dirname(__file__)."/inc/ms_gaiji_sjis1.csv");
$sjis2 = check_sjis_1($char,$file);

print $sjis2;
