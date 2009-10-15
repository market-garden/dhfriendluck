<?php
class UserScoreModel extends Model{
        protected $table_name = 'user_score';
        public function getUserScore($uid){
                return $this->where('uid='.$uid)->order('cTime DESC')->findPage(15);
        }
}
?>
