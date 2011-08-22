<?
 $filepath = array();
 $filepath[0]='1';
function scan_directory_recursively($directory, $filter=FALSE)
{
	global $filepath; 
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory))
	{
		return FALSE;
	}elseif(is_readable($directory))
	{
		$directory_tree = array();
		$directory_list = opendir($directory);
		while($file = readdir($directory_list))
		{
			if($file != '.' && $file != '..')
			{
				$path = $directory.'/'.$file;
				
				
				if(is_readable($path))
				{
					$subdirectories = explode('/',$path);
					if(is_dir($path))
					{
						$directory_tree[] = array(
							'path'      => $path,
							'name'      => end($subdirectories),
							'kind'      => 'directory',
							'content'   => scan_directory_recursively($path, $filter));
							
							
						
					}elseif(is_file($path))
					{
						//@unlink($path);  
						//$filepath[] = $path; 
						$extension = end(explode('.',end($subdirectories)));
						if($filter === FALSE || $filter == $extension)
						{
							$directory_tree[] = array(
							'path'		=> $path,
							'name'		=> end($subdirectories),
							'extension' => $extension,
							'size'		=> filesize($path),
							'kind'		=> 'file');
							$filenamearray   = explode('.',basename($path));
							$filename =$filenamearray[0];
							if($filename > $filepath[0])
							{
							   $filepath[0] = $filename;
							   $filepath[1] = $path;
							}							
						}
					}
				}
			}
		}
		closedir($directory_list);
		//return $directory_tree;
		return $filepath[1];
	}else{
		return FALSE;
	}
}
?>
