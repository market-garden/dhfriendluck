<?php

class PrivacyAction extends BaseAction{


    /*
     * 首页
     *
     */
    function index() {

        $dao = D("Privacy");

        //隐私选项
        $map["uid"] = $this->mid;
        $map["type"] = "basic";
        $data = $dao->where($map)->find();

        $privacy = unserialize($data["privacy"]);

        if(!$privacy){
            $site_opts = $this->api->option_get();
            $privacy = unserialize($site_opts["privacy"]);
        }


        $this->assign("privacy",$privacy);


        //好友提示语
        $fri_tip = D("FriendTip")->get($this->mid);
        $this->assign("fri_tip",$fri_tip);

        $this->display();
    }

    /*
     * 设置好友提示语
     *
     */
    function setFriTip() {

        $content = trim($_POST["content"]);

        $len = StrLenW($content);
        if($len>20){
            exit(0);
        }

        $dao = D("FriendTip");

        $tip = $dao->where("uid = ".$this->mid)->find();

        if($tip){
            $dao->content = $content;
            echo $dao->save();

            echo $dao->getLastSql();
        }else{
            $data["uid"] = $this->mid;
            $data["content"] = $content;
            echo $dao->add($data);
        }

    }


	/*
	 * 设置隐私
	 *
	 */
    public function doIndex(){

        $dao = D("Privacy");

        $privacy = serialize($_POST);
        
        $data["uid"] = $this->mid;
        $data["type"] = "basic";

        $r = $dao->where($data)->find();

        if(!$r){
            $data["privacy"] = $privacy;
            $dao->add($data);
        }else{
            $dao->privacy = $privacy;
            $dao->save();
        }       

        $this->redirect("Privacy/index");
    }


   /*
     * 首页
     *
     */
    function feed() {

        $dao = D("Privacy");

        //隐私选项
        $map["uid"] = $this->mid;
        $map["type"] = "feed";
        $data = $dao->where($map)->find();

        $privacy = unserialize($data["privacy"]);

        if(!$privacy){
            $site_opts = $this->api->option_get();
            $privacy = unserialize($site_opts["feed_privacy"]);
        }

        $this->assign("privacy",$privacy);
        if(!$privacy) $this->assign("null",1);

        $this->display();
    }



	/*
	 * 设置隐私
	 *
	 */
    public function doFeed(){

        $dao = D("Privacy");


        $privacy = serialize($_POST);

        $data["uid"] = $this->mid;
        $data["type"] = "feed";

        $r = $dao->where($data)->find();

        if(!$r){
            $data["privacy"] = $privacy;
            $dao->add($data);
        }else{
            $dao->privacy = $privacy;
            $dao->save();
        }

        $this->redirect("Privacy/feed");
    }





















    /*
     * 黑名单
     *
     */
    function black() {

         $map["uid"] = $this->mid;
         $pings = D("FriendBlack")->where($map)->order("id desc")->findPage(10);
         $this->assign("pings",$pings["data"]);
         $this->assign("page",$pings["html"]);

        $this->display();
    }


 
    /*
     * 添加黑名单
     *
     */
    function addBlack() {
         $pingUserIds = explode(",",$_POST["fri_ids"]);
         $dao = D("FriendBlack");
         $data["uid"] = $this->mid;

         foreach($pingUserIds as $k=>$id){
            $user = $dao->where("fuid=$id")->find();
            if(!$user){
               $data["fuid"] = $id;
               $data["fname"] = getUserName($id);
               $dao->add($data);
            }

         }

         $this->redirect("black");
    }

     /*
     * 解除黑名单
     *
     */
     function removeBlack() {
         $map["id"] = intval($_POST["id"]);
         $map["uid"] = $this->mid;
         echo D("FriendBlack")->where($map)->delete();
     }


     /*
     * 访问限制
     *
     */
     function visit() {

         $type_arr = array("space","blog","photo","mini");

         foreach($type_arr as $k=>$type){

             $map["uid"] = $this->mid;
             $map["type"] = $type;
             $pings = D("FriendHide")->where($map)->order("id desc")->findPage(20);

             $this->assign($type."_data",$pings["data"]);
             $this->assign($type."_page",$pings["html"]);
         }
                          
         $this->display();
     }


     /*
     * 设置访问限制
     *
     */
     function doVisit() {
         
         $type = $_POST["type"];
             
         $pingUserIds = explode(",",$_POST["fri_ids".$type]);
         $dao = D("FriendHide");
         $data["uid"] = $this->mid;

         foreach($pingUserIds as $k=>$id){
            $user = $dao->where("fuid=$id AND type='$type' AND uid='$this->mid'")->find();
            if(!$user){
               $data["fuid"] = $id;
               $data["fname"] = getUserName($id);
               $data["type"]  = $type;
               $dao->add($data);
            }

         }
            

         $this->redirect("visit");


     }

     /*
     * 解除屏蔽
     *
     */
     function removeHide() {
         $map["id"] = intval($_POST["id"]);
         $map["uid"] = $this->mid;
         echo D("FriendHide")->where($map)->delete();
     }













}
?>
