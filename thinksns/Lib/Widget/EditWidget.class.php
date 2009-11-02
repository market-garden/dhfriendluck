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
    class EditWidget extends Widget{
        public function render( $data ){
            $data['cols'] = isset($data['cols'])?$data['cols']:40;
            $data['rows'] = isset($data['rows'])?$data['rows']:15;
            $data['width'] = isset($data['width'])?$data['width']:"100%";
            
            $data['id'] = isset($data['id'])?$data['id']:'content';
            $data['name'] = isset($data['name'])?$data['name']:'content';
            $content = $this->renderFile("Edit",$data);

            return $content;
        }
    } 
