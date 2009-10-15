<?php
import('AdvModel');
class UserModel extends AdvModel
{
	//表单验证
	protected  $_validate = array(
		array('email','require','内容不能为空！'),
	);
    
    /*
     * 获取空间上要显示的信息
     *
     */
    function getDispInfo($mid,$uid) {

        $userInfo_o = $this->find($uid);

        $no = array("id","email","passwd","handle","baseinfoprivacy","admin_level","active","current_city","current_area","company","school","name");
        foreach($userInfo_o as $k=>$v){
            if(!in_array($k,$no)){
                if($v && $v != "null"){
                     if($k == "current_province") {
                        $province_arr = explode("-", $v);
                        $privacy = getPrivNum($province_arr);
                        $v = $province_arr[0]." ".$userInfo_o["current_city"]." ".$userInfo_o["current_area"].$privacy;
                        $k = "current";
                    }
                    if($k == "sex"){
                        $sex_arr = explode("-", $v);
                        $privacy = getPrivnum($sex_arr);
                        $v = ($sex_arr[0] == "1")?"男":"女";
                        $v.=$privacy;
                    }
                    if($k == "status"){
                        $status_arr = explode("-", $v);
                        $privacy = getPrivNum($status_arr);
                        switch($status_arr[0]){
                            case "0" : $v = "其他".$privacy; break;
                            case "1" : $v = $userInfo_o["school"].$privacy;  $k="school"; break;
                            case "2" : $v = $userInfo_o["company"].$privacy; $k="company"; break;
                        }
                    }
                   
                    $userInfo[$k] =  $v;
                }

            }
        }


        foreach($userInfo as $k=>$v){
            if(getPrivacy($v,$mid,$uid)){
                $k = getFieldName($k);
                $userInfo_out[$k] = getValue($v); 
            }

        }



        return $userInfo_out;

    }
    
    //根据组别获取用户列表
    function getGroupUsers($groupId){
    	if(isset($groupId)){
    		$result = $this->where('admin_level='.$groupId)->field('id')->findall();
    		foreach ($result as $v){
    			$return[] = $v['id'];
    		}
    		return $return;
    	}else{
    		return false;
    	}
    }
    
}
?>