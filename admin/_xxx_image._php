<?php
	include("inc/imageclass.inc.php");
	
	$filename	= $_GET['f'];
    $width		= (int)$_GET['w'];
	$height		= (int)$_GET['h'];
	$thumb		= (int)$_GET['t'];
	$whitespace = (int)$_GET['s'];
	$background	= trim($_GET['bg']);
	
	$image = new Image($filename);
	
	
	if($height>0&&$thumb==1)
		{
			$image->cutout($width, $height);
		}
	if($height>0&&$whitespace==1)
		{
			if(trim($background))
				{
					$image->whitespace($width, $height,array('color'=>'#'.$background));
				}
			else
				{
					$image->whitespace($width, $height);
				}
		}
	elseif($height>0)
		{
			$image->scale($width, $height);
		}
	else
		{
			$image->width=$width;
		}
		
	$image->output();
	

?>
