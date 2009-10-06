<?php
class FgModel extends Model
{
	var $table_name = "fg";
   public function addAgreeFriendGroup($mid,$fuid,$gids){
     $gids = explode(",",$gids);
        foreach($gids as $k=>$v){
            $this->addFrendGroup($mid,$fuid,$v);

        }
        //2、在分组关系表中插入对方的关系
        $this->addFrendGroup($fuid,$mid,1);
   }
   public function addFrendGroup($mid,$fuid,$gids){
            $data_g["uid"]  = $mid;
            $data_g["fuid"] = $fuid;
            $data_g["gid"]  = $gids;
            $this->add($data_g);
   }
}
?>