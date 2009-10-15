<?php

class PublicAction extends BaseAction{


    /*
     * 举报提示页
     *
     */
    function isReport() {
        $site_opts = $this->api->option_get();
        $reasons = $site_opts["report_reason"];

        $reasons_arr = explode("\n",$reasons);


		$appid = $_GET["appid"];
		$recordId = $_GET["id"];
		$this->assign("recordId",$recordId);
		$this->assign("appid",$appid);
        $this->assign("reasons",$reasons_arr);
        $this->display();
    }
	
	/*
	 * 举报
	 *
	 */
	function report() {
		if(!$_POST["url"] || !$this->mid) exit(0);
			
		$dao = D("Report");
		
		//看看举报过没
		$map["uid"] = $this->mid;
		$map["type"] = $_POST["type"];
		$map["recordId"] = $_POST["recordId"];
		$r = $dao->where($map)->find();

		if(!$r){
			$dao->create();
			$dao->uid = $this->mid;
			$dao->cTime = time();
			echo $dao->add();		
		}else{
			echo false;
		}

	}

	/*
	 * 举报列表
	 *
	 */
	function report_list() {
			
		$dao = D("Report");

		$data = $dao->order("cTime desc")->findPage(10);

		//dump($data["data"]);

		echo "<table>";
		echo "<tr>";
		echo "<td>举报人</td>";
		echo "<td>链接</td>";
        echo "<td>原因</td>";
		echo "<td>举报时间</td>";
		echo "</tr>";

		foreach($data["data"] as $key=>$v){
			echo "<tr>";
			echo "<td>".getUserName($v["uid"])."</td>";
			echo "<td><a href='".$v["url"]."'>".getShort($v["info"],30)."</a></td>";
            echo "<td>".$v["reason"]."</td>";
			echo "<td>".friendlyDate($v["cTime"])."</td>";
			echo "</tr>";
		}

		echo "</table>";
	}










}
