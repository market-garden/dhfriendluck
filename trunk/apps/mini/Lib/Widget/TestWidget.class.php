<?php
class TestWidget extends Widget {
    public function render( $data ){
        $content = $this->renderFile( 'comment',$data );
        return $content;
    }
}
?>
