<?php
import('AdvModel');
class FriendModel extends AdvModel
{
	//表单验证
	protected  $_validate = array(
		//array('note','require','内容不能为空！'),
	);
    

    /*
     * 把2个加为好友
     *
     */
    function makeFriend($user1_id,$user1_name,$user1_gid,$user2_id,$user2_name,$user2_gid) {
            //好友表中加2条
            $daoF = D("Friend");

            $data_f[0]["uid"] = $user1_id;
            $data_f[0]["fuid"] = $user2_id;
            $data_f[0]["fusername"] = $user2_name;
            $data_f[0]["status"] = 1;

            $data_f[1]["uid"] = $user2_id;
            $data_f[1]["fuid"] = $user1_id;
            $data_f[1]["fusername"] = $user1_name;
            $data_f[1]["status"] = 1;

            $this->add($data_f[0]);
            $this->add($data_f[1]);

            //好友分组中加2条
            $data_fg["uid"] = $user1_id;
            $data_fg["fuid"] = $user2_id;
            $data_fg["gid"] = $user1_gid;
            D("Fg")->add($data_fg);

            $data_fg["uid"] = $user2_id;
            $data_fg["fuid"] = $user1_id;
            $data_fg["gid"] = $user2_gid;
            D("Fg")->add($data_fg);
    }

    public function addFriend($uid,$fusername){
        $this->uid    = $uid;
        $this->fusername  = $fusername;
        $this->status = 1;
        $this->dateline = time();
        return $this->add();
    }
    public function agreenRequest($fuid,$mid){
        $map["uid"]  = $fuid;
        $map["fuid"] = $mid;
        $save['status'] = 1;
        //如果已经是好友，则返回false
        return $this->where($map)->save($save);
    }
    public function checkFriend($fuid,$mid){
        $map["uid"]  = $fuid;
        $map["fuid"] = $mid;
        $map['status'] = 0;
        $result = $this->where($map)->field('count(1) as count')->find();
        return $result['count'];
    }
    
}
?>