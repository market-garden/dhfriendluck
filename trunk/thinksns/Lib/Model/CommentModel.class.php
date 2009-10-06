<?php

class CommentModel extends Model
{

    public function gettablePrefix() {
        return $this->tablePrefix;
    }
    public function getComment($map,$order,$page,$method ){
        if( $method == "findAll" ){
            $comment = $this->where( $map )->order( $order )->$method();
        }else{
            $comment = $this->where( $map )->order( $order )->$method($page);
        }
        return $comment;
    }
}
?>
