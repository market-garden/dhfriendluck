<?php
class ShowCommentWidget extends Widget{

	public function render($data){

		$api     =    new TS_API();

        $data["mid"] = $api->user_getLoggedInUser();
        $content = $this->renderFile("ShowComment",$data);

        return $content;

    }

}
?>