<?php
class PhotoModel extends Model{
        public function setCount($appid,$count){
            $map['id'] = $appid;
            $map2['commentCount'] = $count;
            return $this->where($map)->save($map2);
        }
}
?>