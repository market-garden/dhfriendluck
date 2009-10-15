<?php
    class OddReplayWidget extends Widget{
        public function render( $data ){
            return $this->renderFile( 'OddReplay',$data );
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
