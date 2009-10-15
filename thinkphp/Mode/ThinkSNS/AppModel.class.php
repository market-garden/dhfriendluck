<?php
import('AdvModel'); 
class AppModel extends AdvModel
{
    // 自动验证设置 
    protected $_validate     =     array( 
        array('name','require','应用名必须！',1), 
		array('author','require','开发者必须！',1), 
		array('icon','require','图标必须！',1), 
		array('url','require','目录必须！',1), 
		array('status','require','状态必须！',1), 
		array('describe','require','描述必须！',1), 
   ); 

   //执行sql语句
	function runquery($sql)
	{
		$sql = trim($sql);
		$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);
	
		$buffer = array();
		$ret = array();
		$in_string = false;
	
		for($i=0; $i<strlen($sql)-1; $i++) {
			if($sql[$i] == ";" && !$in_string) {
				$ret[] = substr($sql, 0, $i);
				$sql = substr($sql, $i + 1);
				$i = 0;
			}
	
			if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
				$in_string = false;
			}
			elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
				$in_string = $sql[$i];
			}
			if(isset($buffer[1])) {
				$buffer[0] = $buffer[1];
			}
			$buffer[1] = $sql[$i];
		}
	
		if(!empty($sql)) {
			$ret[] = $sql;
		}
		$errors = 0;
		for ($i=0; $i<count($ret); $i++) {
			$ret[$i] = trim($ret[$i]);

			if(!empty($ret[$i]) && $ret[$i] != "#") {
				$ret[$i] = str_replace( "#__", $DBPrefix, $ret[$i]);
				$errors[] = $this->execute($ret[$i]);
				if($result!='0'){
					$errors++;
				}
			}
		}
		return $errors;
	}
}
?>