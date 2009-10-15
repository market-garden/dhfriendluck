<?php
class VisitorModel extends Model
{

   /*
    * 访客记录
    *
    */
   function foot($uid,$mid,$my_name) {
	   $map["uid"] = $uid;
	   $map["visitId"] = $mid;

	   $r = $this->where($map)->find();

	   if($r){
			$this->cTime = time();
			$this->save();
	   }else{
		    $map["cTime"] = time();
			$map["name"]  = $my_name;
			$this->add($map);
	   }

   }


	/*
	 * 获取N个访客
	 *
	 */
	 function get($num,$uid) {
			
		$map["uid"] = $uid;
		
		$visitors = $this->where($map)->order("cTime desc")->limit($num)->findAll();

		return $visitors;

	 }

	 /*
	  * 获取访客num
	  *
	  */
	  function getNum($uid) {

		$map["uid"] = $uid;	
		$num = $this->where($map)->count();
		return $num;			
	 
	  }


}
?>