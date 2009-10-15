<?php

class LoginRecordModel extends Model
{

	function record($mid) {

		$ip = get_ip();

//		$num = $this->where("uid=$mid")->count();
//
//		if($num<2){
			$data["uid"] = $mid;
			$data["login_ip"] = $ip;
			$data["login_time"] = time();
			$this->add($data);
//		}else{
//			$r = $this->where("uid=$mid")->field("id")->order("login_time asc")->find();
//			$this->id = $r["id"];
//			$this->login_ip = $ip;
//			$this->login_time = time();
//			$this->save();
//		}
	}

   
}
?>