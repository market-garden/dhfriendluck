<?php
import('AdvModel');
class WallModel extends AdvModel
{

    // 自动验证设置
    protected $_validate     =     array(
		array('content','checkLength','内容字数不合要求',self::MUST_VALIDATE,'callback'),
    );


	function checkLength($data,$field)
	{
		switch($field) {
			case "content": return ( strlen($data)>0 && strlen($data)<=2000 )? true : false; 
		}
		
	}


    /*
     * 获取留言
     *
     */
    function getWalls($uid,$mid) {
        
        if($uid == $mid){
            $map_w["uid"] = $uid;
            $map_w["replyWallId"] = 0;
        }else{
            $map_w = "(uid = $uid AND replyWallId = 0 AND fromUserId != $mid AND privacy = 0) OR (uid = $uid AND replyWallId = 0 AND fromUserId = $mid)";
        }        

        $my_walls = $this->where($map_w)->order("cTime desc")->findPage(10);
        foreach($my_walls['data'] as $k=>$wall){
            $map_r["replyWallId"] = $wall["id"];
            $wall_replys = $this->where($map_r)->findAll();
            $my_walls['data'][$k]["replys"] = $wall_replys;
        }

        return $my_walls;
    }

    /*
     * 删除留言
     *
     */
    function del($id,$mid) {
        $map = "(fromUserId = $mid OR uid = $mid) AND (id = $id)";
        $r = $this->where($map)->delete();
        setScore($mid, 'delete_wall');
        if($r){
            $map_r[replyWallId] = $id;
            $this->where($map_r)->delete();
        }
        
        return 1;

    }



   
}
?>