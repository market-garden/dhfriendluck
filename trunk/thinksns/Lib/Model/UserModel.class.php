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
    
    public function findUser($name,$data){
    	$map = array();
	!empty($name) && $map['name'] = array('like','%'.$name.'%');
	$searchCondition = $this->__paramData($data);
	$map = array_merge($searchCondition,$map);
	$map['active'] = 1;
	$result = $this->__getUser($map);
	return $result;
    }

    public function findUserByField($name,$value){
	    $map[$name] = $value;
	    $map['active'] = 1;
	    $result = $this->__getUser($map);
	    return $result;
    }

    private function __paramData($data){
	$result = array();

	isset($data['sex']) && $result['sex'] = $data['sex'];
	if(isset($data['ts_area'])){
		list($province,$city) = explode(',',$data['ts_area']);

		isset($province) && $result['current_province'] = $province;
		isset($city) && $result['current_city']     = $city;
	}
	return $result;
    }

    private function __getUser($map){
    	$privacy = $this->__filterSearchUser();
        if(!empty($privacy)){
        	if(isset($map['id']) && in_array($map['id'],$privacy))
        	   return array('count'=>0);
            $map['id'] = array("not in",$privacy);
        }
	    return $this->where($map)->field('`id` as `uid`,name,sex,current_province,current_city')->order('cTime DESC')->findPage(10);
    }
        private function __filterSearchUser() {
                $dao = D('Privacy');
                $result = array();
                $privacy_request = $dao->field('privacy,uid')->findAll();
                     if($privacy_request ) {
                        foreach($privacy_request as $value){
                             $privacy = unserialize($value['privacy']);
                             if($privacy['search']) $result[] = $value['uid'];
                              }
                                       
                      }
                return $result;
        }
}
?>