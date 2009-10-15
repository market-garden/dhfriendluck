<?php
    class UserInfoModel extends BaseModel{
    /*
     * 获取空间上要显示的信息
     *
     */
    function getDispInfo($mid,$uid) {
        $userInfo = $this->where("uid=$uid")->find();
		$no = array("id","uid");
        foreach($userInfo as $k=>$v){
			if(!in_array($k,$no)){
				if(getPrivacy($v,$mid,$uid)){
					$k = getFieldName($k);
					$userInfo_out[$k] = getValue($v); 
				}			
			}
        }
        return $userInfo_out;
    }   
}
?>
