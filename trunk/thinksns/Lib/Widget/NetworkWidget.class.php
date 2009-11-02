<?php
    class NetworkWidget extends Widget{
        public function render($data){
            $content = $this->renderFile("Network",$data);
            return $content;
        }
        

}
