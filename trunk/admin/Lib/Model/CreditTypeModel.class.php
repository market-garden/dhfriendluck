<?php
Class CreditTypeModel extends Model {
    var $tableName	=	'credit_type';
    public function addType($name,$alias){
        if($this->checkValue($name, $alias)) return false;
        $map['name'] = $name;
        $map['alias']   = $alias;
        $result =  $this->add($map);
        //设置缓存
        $this->setCache();
        return $result;
    }

    public function checkValue($name,$alias){
        if(!$this->checkName('name',$name)) return false;
        if(!$this->checkName('alias',$alias)) return false;
        return true;
    }
    public function checkName($field,$value){
        $map[$field] = $value;
        $result = $this->where($map)->count();
        return $result>0 ? true:false;
    }

    public function getCreditType(){
        $cache = ts_cache('credit_type');

        if(!$cache){
            return $this->getAllType();
        }
        return $cache;
    }

    public function editTypeAlias($id,$value){
        $map['alias'] = $value;
        $result = $this->where('id='.$id)->save($map);
        $this->setCache();
        return $result;
    }
    public function editTypeName($id,$value){
        $map['name'] = $value;
        $result = $this->where('id='.$id)->save($map);
        $this->setCache();
        return $result;
    }
    private function getAllType(){
           //得到所有数据
        $data = $this->findAll();
        //重组数据
        $cache = array();
        foreach ( $data as $value ){
            $cache[$value['name']]=$value['alias'];
        }
        return $cache;
    }

    public function setCache(){
        $cache = $this->getAllType();
        //存储缓存
        ts_cache('credit_type',$cache);
    }
}

?>
