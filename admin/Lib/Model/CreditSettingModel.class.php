<?php
Class CreditSettingModel extends Model {
    var $tableName	=	'credit_setting';


    public function addCreditType($name,$alias){
        $tableName = $this->getTableName();
        $oldfiled = $this->getTableFields($tableName);
        //是否这个字段是否已经存在
        if(in_array($name,$oldfiled)) return false;
        if(is_array($name)) return false;
        if(empty($alias) || empty($name)) return false;
        if(false == D('CreditType')->addType($name,$alias)) return false;

        //插入这个积分种类
        $sql = "alter table `{$tableName}` add `{$name}` int not null default '0'";
        return $this->query($sql);
    }


    /**
     * 添加新的字段。添加新的种类
     * @param string $tableName 表名
     * @return bool
     */
    public function getTableFields($tableName=null){
        if(!isset($tableName)) $tableName = $this->getTableName();
        $fields_list =   $this->query('SHOW COLUMNS FROM '.$tableName);
        
        //重组数组。只需要返回字段名
        $result = array();
        foreach ($fields_list as $value){
            $result[] = $value['Field'];
        }
        return $result;
    }
    public function doDeleteFields($fields){
        $tableName = $this->getTableName();
        $sql = "ALTER table `{$tableName}`
                    drop column {$fields}
            ";
        return $this->query($sql);
    }
    public function editType($id,$map){
        $dao = D('CreditType');
        //取得旧的类型数据
        $old_type = $dao->where('id='.$id)->find();
        $field = $this->getTableFields();
        if(!D('CreditType')->editTypeAlias($id,$map['alias'])) return false;

        //判断类型的字段名是否被修改
        if($old_type['name'] != $map['name'] && !in_array($map['name'],$field)){
            if(!D('CreditType')->editTypeName($id,$map['name'])) return false;
            //修改字段名
            $tableName = $this->getTableName();
            $sql = "ALTER   table   `ts_credit_setting`
                          CHANGE   COLUMN
                         {$old_type['name']}   {$map['name']}   int(11)  not null default 0";
            $edite_field = $this->query($sql);
            if(false === $edite_field) return false;
        }
        return true;


    }
}
?>