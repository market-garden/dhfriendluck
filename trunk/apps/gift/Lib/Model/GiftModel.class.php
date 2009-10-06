<?php
    /**
     * GiftModel
     * 礼物数据模型
     *
     * @uses 
     * @package 
     * @version 
     * @copyright 2009-2011 SamPeng 水上铁
     * @author SamPeng <sampeng87@gmail.com> 水上铁<wxm201411@163.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
	class GiftModel extends AdvModel{
		/**
		 * _initialize
		 * 初始化函数
		 * @return void
		 */
		public function _initialize(){
            parent::_initialize();
		}
		/**
		 * assertNumAreEmpty
		 * 判断是否礼品数是否足够
		 * @return bool
		 */		
		public function assertNumAreEmpty($id,$count = null){
			$num = (int)$this->where('id='.$id)->getField('num');
			if($num == 0 || $num < $count){
				return true;
			}
			return false;			
		}
	   /**
     +----------------------------------------------------------
     * 根据条件禁用表数据
     * 
     +----------------------------------------------------------
     * @access public 
     +----------------------------------------------------------
     * @param mixed $condition 删除条件
     * @param string $table  数据表名
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */
    function forbid($condition,$table='')
    {
        $table = empty($table)?$this->getTableName():$table;
        if(FALSE === $this->db->execute('update '.$table.' set status=0 where status=1 and ('.$condition.')')){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    /**
     +----------------------------------------------------------
     * 根据条件禁用表数据
     * 
     +----------------------------------------------------------
     * @access public 
     +----------------------------------------------------------
     * @param mixed $condition 删除条件
     * @param string $table  数据表名
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */
    function resume($condition,$table='')
    {
        $table = empty($table)?$this->getTableName():$table;
        if(FALSE === $this->db->execute('update '.$table.' set status=1 where status=0 and ('.$condition.')')){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }		
	}
?>