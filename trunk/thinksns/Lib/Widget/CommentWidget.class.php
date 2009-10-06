<?php
    /**
     * CommentWidget 
     * 评论widget
     * @uses Widget
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class CommentWidget extends Widget{
        public function render( $data ){

            $bq_emotion = ts_cache('smile_mini');
            $data['next']    = "下一页";
            $data['prev']    = "上一页";
            $data['perpage'] = 10;
            $data['bq_emotion'] = $bq_emotion;

            $content = $this->renderFile("Comment",$data);

            return $content;
        }
    } 
