<?php
class SelectFriendWidget extends Widget{

	public function render($data){

        $content = $this->renderFile("SelectFriend",$data);

        return $content;

    }

}
?>