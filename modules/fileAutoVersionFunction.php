<?php
	function auto_version($file='')
	{
		// script to force refresh of a file if it's been modified
		// since it was last cache'd in the user's browser
		if(!file_exists($file))
			return $file;
	 
		$mtime = filemtime($file);
		return $file.'?'.$mtime;
	}
?>