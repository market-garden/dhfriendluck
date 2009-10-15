<?php

class FriendGroupModel extends Model
{

    /**
     * getOneGroup 
     * 获取某个人的好友分组
     * @param mixed $uid 
     * @access public
     * @return void
     */
    public function getOneGroup( $uid ){
        $result = "";

        //如果uid不为数值型。是异常
        if( !is_numeric( $uid ) ){
            $this->error( '错误的用户id' );
        }
        //获取uid这个人定义的分组和系统分组。返回id=》name类型的数组
        $result = $this->where( 'uid=0 or uid='.$uid )->field( 'id,name' )->findAll();
        return $result;
    }
    

    
}
?>
