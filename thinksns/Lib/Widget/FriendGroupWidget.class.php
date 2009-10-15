<?php
class FriendGroupWidget extends Widget{

	public function render($data){

		$uid		 =    intval($_GET["uid"]);
		$api		 =    new TS_API();
		$mid		 =	  $api->user_getLoggedInUser();
        $data["mid"] =	  $uid?$uid:$mid;

		$map = "uid = 0 or uid = ".$mid;
		$data["groups"] = D("FriendGroup")->where($map)->order("id asc")->findAll();
		if($_GET["uid"]) $other = "/uid/".$_GET["uid"];
        $data["cur_url"] = isset( $data['this_url'] ) ? $data['this_url']:C("TS_URL")."/index.php?s=/".MODULE_NAME."/".ACTION_NAME.$other;

        $content = $this->renderFile("FriendGroup",$data);

        return $content;

    }

}
?>
