<?php

//缓存目录
$dirs	=	array('./Runtime');
//清理缓存
foreach($dirs as $value)
{
	$current_dir = @opendir($value);
	while($entryname = readdir($current_dir)){
		if(is_file($value."/".$entryname))
		{
			@unlink($value."/".$entryname);
		}
	}
	@closedir($current_dir);
	echo "清理缓存文件夹成功! ".$value."<br />";
}

?>