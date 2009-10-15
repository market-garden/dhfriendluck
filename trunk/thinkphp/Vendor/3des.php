<?php
/**
*
* PHP版3DES加解密类
*
* 可与java的3DES(DESede)加密方式兼容
*
* @Author: Luo Hui (farmer.luo at gmail.com) update by melec 2009-2-19
*
* @version: V0.1 2008.12.04
*
*/
class Crypt3DES
{
	public $key    = "thinksns";
    public $iv    = "good"; //like java: private static byte[] myIV = { 50, 51, 52, 53, 54, 55, 56, 57 };
	//public $iv	=	'qcDY6X+aPLw=';
    //加密
    public function encrypt($input) {
        $input	=	$this->padding( $input );
        $key	=	base64_decode($this->key);
        $td		=	mcrypt_module_open( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        //使用MCRYPT_3DES算法,ecb模式
        @mcrypt_generic_init($td, $key, $this->iv);
        //初始处理
        $data	=	mcrypt_generic($td, $input);
        //加密
        mcrypt_generic_deinit($td);
        //结束
        mcrypt_module_close($td);
        $data	=	$this->removeBR(base64_encode($data));
        return $data;
    }

    //解密
    public function decrypt($encrypted) {
        $encrypted = base64_decode($encrypted);
        $key = base64_decode($this->key);
        $td = mcrypt_module_open( MCRYPT_3DES,'',MCRYPT_MODE_ECB,'');
        //使用MCRYPT_3DES算法,ecb模式
        @mcrypt_generic_init($td, $key, $this->iv);
        //初始处理
        $decrypted = mdecrypt_generic($td, $encrypted);
        //解密
        mcrypt_generic_deinit($td);
        //结束
        mcrypt_module_close($td);
        $decrypted = $this->removePadding($decrypted);
        return $decrypted;
    }

    //填充密码，填充至8的倍数
    public function padding($input) {
		$srcdata = $input;
		$block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
		$padding_char = $block_size - (strlen($input) % $block_size);
		$srcdata .= str_repeat(chr($padding_char),$padding_char);
		return $srcdata;
    }

    //删除填充符
    public function removePadding( $data )
    {
		$pad = ord($data{strlen($data)-1});
		if ($pad > strlen($data) || strspn($data, chr($pad), strlen($data) - $pad) != $pad)	{
			$srcdata = $data;
		}else{
			$srcdata = substr($data, 0, -1 * $pad);
		}
		return $srcdata;
    }

    //删除回车和换行
    public function removeBR( $str )
    {
        $len = strlen( $str );
        $newstr = "";
        $str = str_split($str);
        for ($i = 0; $i < $len; $i++ )
        {
            if ($str[$i] != '\n' and $str[$i] != '\r')
            {
                $newstr .= $str[$i];
            }
        }

        return $newstr;
    }

}


?>