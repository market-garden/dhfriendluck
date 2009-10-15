<?php
/**
* BaseAction
* 分享应用的常用操作实现
*
* @package default
* @version $id$
* @copyright 2009-2011 水上铁
* @author 水上铁 <wxm201411@163.com>
* @license PHP Version 5.2 {@link www.sampeng.cn}
*/
class BaseAction extends Action
{
	/**
    *model
    * 用于记录分享内容表的模型
    *
    * @var string
    * @access public
    */
    public $model = "" ;

	/**
    *Cmodel
    * 用于记录分享分类表的模型
    *
    * @var string
    * @access public
    */
    public $Cmodel = "" ;

	/**
    *mid
    * 当前登录用户ID
    *
    * @var string
    * @access public
    */
    public $mid = "" ;

	/**
    *uid
    * 被浏览用户ID
    *
    * @var string
    * @access public
    */
    public $uid = "" ;
    
    public $types;

	/**
    * _initialize
    * 初始化函数
    *
    * 初始化数据模型,用户ID
    * @param string $aArgs 参数说明
    * @access public
    * @return void
    */
	public function _initialize(){
		$this->model = D('Share');
		$this->Cmodel = D('ShareType');
		 		
		$this->types = ts_cache('share_types');
		if(empty($this->types)){
			$this->types = type_cache();
		}
	}

	/**
    * filtrate
    * 用户分享过滤器
    *
    * 运用用户设定过滤条件过滤要输出的分享
    * @param void
    * @access public
    * @return $where SQL查询条件
    */
	public function filtrate($action='friends'){
		$where = "isDel=0";
		/**
        * 导航条件
        */
		if(!empty($_GET['typeId'])){
			$typeId = intval($_GET['typeId']);
			$typeId = $this->Cmodel->where("state=0 AND id=".$typeId)->getField('id');
            if($typeId){
            	$where .= " AND typeId='$typeId'";
            }else{
            	$this->error('非法操作!');
            }			
		}
		/**		
		* 某人的或我的分享
		*/
		if($action=='my'){
			$where .= " AND toUid='$this->mid'";
		}elseif(!empty($_GET['uid'])){
			$where .= " AND toUid=".$_GET['uid'];
		}
		/**	
		* 好友的分享
		*/
		if(isset($_GET['gid'])){
			$fuids = $this->api->friend_getGroupUids($_GET['gid']);
			if(!empty($fuids)){
				$where.= " AND toUid IN (" . join(",", $fuids) . ")";
			}else{
				$where.= " AND id=-1";
			}
		}elseif ($action=='friends'){
			$fuids = $this->api->friend_get();
			if(!empty($fuids)){
				$where.= " AND toUid IN (" . join(",", $fuids) . ")";
			}else{
				$where.= " AND id=-1";
			}			
		}
		/**	
		* 存档
		*/
		if(isset($_GET['time'])){
			$step = empty($_GET['step'])? 1 : $_GET['step'];
			$time = $_GET['time'];

			$month = date('m',$time);
			$lastMonth = $month+$step;
			$lastTime = mktime(0,0,0,$lastMonth,1,date('Y',$time));

			$where .=" AND cTime BETWEEN '$time' AND '$lastTime'";
		}
		return $where;
	}

	/**
    * checkPurview
    * 用户权限判断
    *
    * 判断当前用户是否有权限查看分享
    * @param void
    * @access public
    * @return void
    */
	function checkPurview($share,$type){
		if($this->mid!=$share->uid){
            if(!empty($share->purview)){
  		        switch ($share->purview){
		    	    case '1':
		    		    $isFriend = $this->api->friend_areFriends($this->mid,$share->uid);
				        if(!$isFriend){
					        $this->error('你无权限查看该分享!');
				        }
				        break;
		    	    case '2':
		    		    $this->error('你无权限查看该分享!');
				        break;
				    default: ;
		        }
            }elseif(!empty($type->purview)){
  		        switch ($type->purview){
		    	    case '1':
		    		    $isFriend = $this->api->friend_areFriends($this->mid,$share->uid);
				        if(!$isFriend){
					        $this->error('你无权限查看该类分享!');
				        }
				        break;
		    	    case '2':
		    		    $this->error('你无权限查看该类分享!');
				        break;
				    default: ;
		        }
            }
		}

		return true;
	}
}
?>