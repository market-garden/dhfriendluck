<?php
import('AdvModel');
class UserModel extends AdvModel
{
	function search($name){
    	return $this->where("name like '%$name%'")->field('id')->findAll();
    	
    }
    
}
?>