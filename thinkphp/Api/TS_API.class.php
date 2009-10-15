<?php

require(THINK_PATH."/Api/LW_ORM/LW_ORM.php");

class TS_API {

	public static $dao;
	
	public function __call($fun,$args){

		$fun = explode("_",$fun);
		$model  = ucfirst($fun[0]);
		$method = $fun[1];
		if(TS_API::$dao->model_name != $model )	TS_API::$dao    = TS_D($model);		

		return  (call_user_func_array(array(TS_API::$dao, $method),$args));	  
	}
}

?>