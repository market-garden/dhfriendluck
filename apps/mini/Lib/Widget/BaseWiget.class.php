<?php
/**
 * MiniWiget 
 * Mini的基础Wiget
 * 
 * 重写rederFile,增加更多的属性
 *
 * 可以像Action一样assign进行属性赋值
 * @uses Widget
 * @abstract
 * @package 
 * @version $id$
 * @copyright 2009-2011 SamPeng 
 * @author SamPeng <sampeng87@gmail.com> 
 * @license PHP Version 5.2 {@link www.sampeng.cn}
 */
class BaseWidget extends Widget {
    /**
     * tVar 
     * 模板变量
     * @var array
     * @access protected
     */
    protected $tVar = array();
    /**
     * render 
     * 重写render,防止错误的参数输入
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function render( $data ){
        if( false == $data || empty( $data ) || !isset( $data ) ){
            throw new ThinkException("传入参数为假或为空");
        }
    }
    /**
     * assign 
     * wiget的变量赋值
     * @param Action $name 
     * @param string $value 
     * @access public
     * @return void
     */
    public function assign ($name,$value = '') {
        if( is_array( $name ) ) {
            $this->tVar = array_merge( $this->tVar,$name );
        }elseif( is_object( $name ) ){
            foreach( $name as $key => $val ){
                $this->tVar[$key] = $val;
            }
        }else{
            $this->tVar[$name] = $value;
        }
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

    /**
     * __call
     *
     * 重载render方法
     * @param mixed $functionname 
     * @param mixed $args 
     * @access public
     * @return maxed
     */
    public function __call( $functionname,$args ){
        if( $functionname == 'render' ){
            //判断参数的个数。如果参数为0，则是直接用tVar变量中存储的变量进行渲染输出
            switch( sizeof( $args ) ){
                case 0:
                    return $this->render( $this->tVar );
                    break;
            }
        }else{
            throw new Exception( get_class( $this )."中".$functionname."方法不存在" );
        }
    }
}
