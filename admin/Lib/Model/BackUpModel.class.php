<?php


class BackUpModel extends Model {
	
	//获取表结构
	function getTableList(){
		$tables = array();
		$tablePre = C('DB_PREFIX');
		$sql = "show table status like '$tablePre%'";
		return $this->query($sql);
	}
	function arraykey($array, $key2) {
		$return = array();
		foreach($array as $value) {
			$return[] = $value[$key2];
		}
	return $return;
}	
	
	
	function sqlDumpTable($table, $startfrom=0, $filesize,$currentsize,$complete=true){
		
		$tabledump = '';
		$offset = 200;
		$tablefields = array();
		
		$tablefields = $this->query('show full columns from '.$table);
		
		if(!$tablefields) return false;
		if($startfrom == 0){
			$createtable = $this->query('show create table '.$table);
			$tabledump .= " drop table if exists $table;\n";
			
			$tabledump .= $createtable[0]['Create Table'].";\n\n";
		}
		
		$first_field = $tablefields[0];
		$numrows = $offset;
		while($currentsize + strlen($tabledump) + 500  < $filesize && $numrows == $offset){
			
			if($first_field['Extra'] == 'auto_increment'){
				
				$sql = 'SELECT *FROM '. $table ." where ".$first_field['Field']." > $startfrom limit $offset";
			}else{
				$sql = 	'SELECT *FROM '. $table ." LIMIT  $startfrom,$offset";
			}
			
			$tableData = $this->query($sql);
			$numrows = count($tableData);
			
			if($numrows && $tableData){
			
				$linkid = $this->db->connect();
				$query = mysql_query($sql);
				while($oneRow = mysql_fetch_assoc($query)){
					$dumpsql = $comma = '';
					
					foreach($oneRow as $field => $value){
						
						$dumpsql .= $comma."'".mysql_escape_string($value)."'";
						$comma = ',';
					}
					
					if(strlen($dumpsql)+$currentsize+strlen($tabledump)+500 < $filesize){
							if($first_field['Extra'] == 'auto_increment') {
								$startfrom = $oneRow[$first_field['Field']];
							} else {
								$startfrom ++;
							}
							
							$tabledump .= 'INSERT INTO '.$table." VALUES ($dumpsql);\n";
							
							
					}else{
						$complete = false;
						
						break 2;
						
					}
					
				}
				
			}
			
		}
		
		
		$tabledump .= "\n";
		
		return array('complete'=>$complete,'startform'=>$startfrom,'tabledump'=>$tabledump);
		
	}
	
	function splitsql($sqldump) {
	$sql = str_replace("\r", "\n", $sqldump);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $subquery) {
			if(!empty($subquery[0])){
				$ret[$num] .= $subquery[0] == '#' ? NULL : $subquery;
			}
		}
		$num++;
	}
	return $ret;
}

function import($sqldump){
	$sqlquery = $this->splitsql($sqldump);
	$ret = false;
	$linkid = $this->db->connect();
		foreach($sqlquery as $sql) {
			$sql = trim($sql);
			if(!empty($sql)) {
					$ret = mysql_query($sql);
				}
		 }
	return $ret;
}


}






?>