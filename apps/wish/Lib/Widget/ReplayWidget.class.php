<?php
    /**
     * ReplayWiget 
     * 回复的wiget
     * @uses BaseWiget
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class ReplayWidget extends Widget{
        public function render( $data ){
            //if( empty($this->tVar) )
            //parent::render( $data );
            return $this->renderFile( 'Replay',$data );
        }
        /**
         * renderFile 
         * 重写renderFile.可以自由组合参数进行模板输出
         * @param string $templateFile 
         * @param string $var 
         * @param string $charset 
         * @access protected
         * @return maxed
         */
        protected function renderFile( $templateFile = '',$var = '',$charset = 'utf-8' ){
            //if( empty( $var ) ){
            //$var = $this->tVar;
            //}
            return parent::renderFile( $templateFile.'Widget',$var,$charset );
        }

    }
