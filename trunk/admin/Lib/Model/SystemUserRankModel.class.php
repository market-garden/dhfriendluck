<?php
class SystemUserRankModel extends Model{
	var $table_name = 'system_user_rank';
        public function addRank($map,$rule){
                $map['rulemin'] = serialize($rule['min']);
                $map['rulemax'] = serialize($rule['max']);
                $result = $this->add($map);
                if($result) $this->setCache();
                return $result;
        }
     public function getAll(){
           //得到所有数据
        $data = $this->findAll();
        //重组数据
        foreach ( $data as &$value ){
                $value['rulemin'] = unserialize($value['rulemin']);
                $value['rulemax'] = unserialize($value['rulemax']);

        }
        return $data;
    }
    public function getAllRule(){
            $cache = ts_cache('rank_rule');
            if(!$cache){
                    $cache = $this->getAll();
            }
            return $cache;
    }
    public function setCache(){
        $cache = $this->getAll();
        //存储缓存
        ts_cache('rank_rule',$cache);
    }
}
?>