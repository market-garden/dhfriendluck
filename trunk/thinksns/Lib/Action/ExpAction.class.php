<?php
// 本类由系统自动生成，仅供测试用途
class ExpAction extends BaseAction{
    public function index(){

		/*-------------------------------------
		= 我的资料
		-------------------------------------*/
		$my_info = $this->api->user_getInfo($this->mid);
		$this->assign('my_info',$my_info);

		/*-------------------------------------
		= 好友列表
		-------------------------------------*/
		$my_fris = $this->api->friend_get();

		$fris_info = $this->api->user_getInfo($my_fris);
		$this->assign('fris_info',$fris_info);

		/*-------------------------------------
		= 通知
		-------------------------------------*/

		$notify_num = $this->api->notify_getNewNum();		//通知个数
		$notifys = $this->api->notify_get("notification"); //通知内容


		$this->assign('notify_num',$notify_num);
		$this->assign('notify_con',$notifys);

		/*-------------------------------------
		= 好友动态
		-------------------------------------*/
		$my_feeds  = $this->api->feed_get("fri","all",10);
		$fri_feeds = $this->api->feed_get("my","all",3);

		$this->assign('my_feeds',$my_feeds);
		$this->assign('fri_feeds',$fri_feeds);


		/*-------------------------------------
		= 公共类库
		-------------------------------------*/
		vendor("Test");
		$vendor_test = new Test();
		$vendor = $vendor_test->test();
		$this->assign('vendor',$vendor);

		$this->display();
    }

	public function upload() {
		if(strtolower($_SERVER["REQUEST_METHOD"]) != "post") // 编辑页面
		{
			$this->display();
		}else{
			$path = ROOT_PATH."/data/test/";
			$info = $this->_upload($path);
			dump($info);
		}
	}

	public function up2() {
		
		$path = $_GET['folder'] ;
		$path2 = ROOT_PATH."/data/test2/";
		checkDir($path2);
		$info = $this->_upload($path);
		dump($info);
	}



	public function doUploadFaceImg() {

		$path = ROOT_PATH."/data/test2/";
		$info = $this->_upload($path);
		if(!is_array($info)){
			echo "<script language='javascript'>alert('对不起,上传文件失败')</script>";
		} else {
			//$uploadfile = C('IMG_HOST').str_replace('./','',$info[0]['savepath'].$info[0]['savename']);
			$uploadfile = $info[0]['savepath'].$info[0]['savename'];
			echo '<script language="javascript">parent.insertImg1("'.$uploadfile.'");parent.$("#bigImage").val("'.$uploadfile.'");</script>';
		}
	}



	//保存用户头像
	function saveThumb() {
		$targ_w = $targ_h = 120;//头像的高度和宽度
		$jpeg_quality	= 100;
		$src			= $_POST['bigImage'];
	
		//获取图片的扩展名。来选择使用什么函数
		if(	$arr = @getimagesize($src)	){
			$ext = image_type_to_extension($arr[2],false);
		} else {
			$this->error('对不起,GD库不存在或远程图片不存在');
			exit();
		}
		$func = ($ext != 'jpg')?'imagecreatefrom'.$ext:'imagecreatefromjpeg';
		$img_r = call_user_func($func,$src);
		//函数已经确定

		//开始切割头像
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		$x=$targ_h/$_POST['txt_Zoom'];
		imagecopyresampled($dst_r,$img_r,0,0,$_POST['txt_left']/$_POST['txt_Zoom'],$_POST['txt_top']/$_POST['txt_Zoom'],$targ_w,$targ_h,$x,$x);


		//将头像保存
		$path = ROOT_PATH."/data/thumb/";
		$filename = $path.'xxx_s.jpg';
		imagejpeg($dst_r,$filename);

		$this->redirect("/Home/index");
	}

	public function cache() {



		if($xxx = ts_cache("bbb")){
			dump("cache");
			dump($xxx);
            $ttt = ts_cache("bbb","");
            dump($ttt);
            $ccc = ts_cache("ccc");
            dump($ccc);
		}else{		
			dump("set");
			$xxx["aaa"] = "haha";//sql操作 ....
			$xxx["bbb"] = "hehe";
			ts_cache("bbb",$xxx,10);
            ts_cache("ccc","ccc",10);
			dump($xxx);
		}



		
	}



}
?>