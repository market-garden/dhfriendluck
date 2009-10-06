<?php
import('AdvModel');
class UserInfoModel extends AdvModel
{

    // 自动验证设置
//    protected $_validate     =     array(
//		array('fri_ids','require','请选择用户!',self::MUST_VALIDATE),
//		array('subject','checkLength','标题字数不合要求',self::MUST_VALIDATE,'callback'),
//		array('content','checkLength','内容字数不合要求',self::MUST_VALIDATE,'callback'),
//    );
//
//
//	function checkLength($data,$field)
//	{
//		switch($field) {
//			case "subject": return ( strlen($data)>0 && strlen($data)<=30 )? true : false; 
//			case "content": return ( strlen($data)>0 && strlen($data)<=1000 )? true : false; 
//		}
//		
//	}

    /*
     * 获取空间上要显示的信息
     *
     */
    function getDispInfo($mid,$uid) {

        $userInfo = $this->where("uid=$uid")->find();
        $more = unserialize($userInfo["more"]);
        unset($userInfo["more"]);
        
        foreach($more as $k=>$v){
            $userInfo[$k] = $v;
        }

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