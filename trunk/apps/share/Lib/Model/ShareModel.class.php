<?php
class ShareModel extends AdvModel
{
	function setViewNum($id){
		$share = $this->find($id,'id,viewNum');
		$viewNum = $share->viewNum+1;
		$this->save("viewNum='$viewNum'","id='$share->id'");
	}
}
?>