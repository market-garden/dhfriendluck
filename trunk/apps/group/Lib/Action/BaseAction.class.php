<?php
    /**

     * <b>注意:$this->未显示定义的变量名将会取值。对应变量名的model对象</b>
     * @uses Action
     * @package Action::group
     * @version $id$
     * @copyright 2009-2011 ThinkSNS
     * @author songhongguang
     */
    class BaseAction extends Action {
        var $gid;
        var $mid;
        /**
         * __initialize
         * 初始化
         * @access public
         * @return void
         */
        var $isadmin;
        var $config;
        var $groupinfo;
        var $siteTitle;
        public function _initialize(){
			

        
            //群组id
           	  //echo __APP__;
              if(isset($_GET['gid']) && intval($_GET['gid']) > 0) {
              	$this->gid = intval($_GET['gid']);
              }elseif(isset($_POST['gid']) && intval($_POST['gid']) > 0){
              	$this->gid = intval($_POST['gid']);
              }else{
              	$this->error('gid 错误');
              }

              $groupinfo = D('Group')->where('id='.$this->gid." AND is_del=0")->find();

              if(!$groupinfo) $this->error('该群组不存在，或者被删除');
              //判读权限  成员权限
              if($groupinfo['brower_level'] == 1 && !isJoinGroup($this->uid,$this->gid)){
              		$this->error('只有成员可见');
              }




              $this->groupinfo = $groupinfo;

              $this->assign('groupinfo',$groupinfo);
              $this->assign('gid',$this->gid);

               //记录访问时间
               D('Member')->where('gid='.$this->gid." AND uid={$this->mid}")->setField('mtime',time());

          	   //判读是否是管理员
              $this->isadmin = isadmin($this->mid,$this->gid);

        	  $this->assign('isadmin',$this->isadmin);
			  $this->setTitle($this->groupinfo['name'].'群-');
			  
			  
			  //添加积分操作
			  $addScore = array('group_create','group_topic_add','group_topic_reply','group_topic_top','group_topic_dist'
			  ,'group_file_upload','group_photo_upload');
			  //减少积分
			  $reduceScore = array('group_topic_delete','group_topic_cancel_top','group_topic_cancel_dist','group_file_delete','group_photo_delete');
			  
			//  $addAction = array();	
			  
        }

        function base() {
        	$this->assign('need_login',1);
        	 $this->config = D('Group')->getConfig();  //系统的配置文件
      		  $this->siteTitle = getSiteTitle();
      		 
      		  
        	  $this->assign('config',$this->config);
     		
			  
              $this->api->space_changeCount( 'group',D('Member')->memberCount($this->uid));

        }


        //执行单图上传操作
	   protected function _upload($path,$save_name,$is_replace,$is_thumb,$thumb_name,$thumb_max_width) {

		if(!checkDir($path)){
			return '目录创建失败: '.$path;
		}

		import("ORG.Net.UploadFile");

        $upload = new UploadFile();

        //设置上传文件大小
        $upload->maxSize	=	'2000000' ;

        //设置上传文件类型
        $upload->allowExts	=	explode(',',strtolower('jpg,gif,png,jpeg,bmp'));

		//存储规则
		$upload->saveRule	=	'uniqid';
		//设置上传路径
		$upload->savePath	=	$path;
        //保存的名字
        $upload->saveName   =   $save_name;
        //是否缩略图
        $upload->thumb          =   $is_thumb;
        $upload->thumbMaxWidth  =   $thumb_max_width;
        $upload->thumbFile      =   $thumb_name;

        //存在是否覆盖
        $upload->uploadReplace  =   $is_replace;
        //执行上传操作
        if(!$upload->upload()) {
            //捕获上传异常
            return $upload->getErrorMsg();
        }else{
			//上传成功
			return $upload->getUploadFileInfo();
    	}
    }

    //弹出无权限对话框
    function alert() {
    	$this->display('../Public/alert');
    	exit();
    }
    
    

    
 }
 ?>