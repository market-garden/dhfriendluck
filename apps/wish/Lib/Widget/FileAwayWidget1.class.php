<?php
    require_once( "BaseWiget.class.php" );
    /**
     * Pub_FileAwayWidget 
     * 归档widget
     * 
     * @uses BaseWiget
     * @package Widget
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class FileAwayWidget extends BaseWidget{

        private $data;
        /**
         * render 
         * 
         * @param mixed $data 
         * @access public
         * @return void
         */
        public function render( $data ){
            $this->data = $data;

            $date = date( 'Ym',time() );
            if( !isset($data['limit']) ){
                $date = self::paramData( $date,6);
            }else{
                $date = self::paramData( $date,$data['limit']);
            }

            $data['date'] = $date;
            return $this->renderFile( 'FileAway',$data );
        }


        /**
         * paramData 
         * 解析日期
         * @param mixed $date 当前时间（200905格式）
         * @param mixed $object 需要查询数据的object名.
         * @static
         * @access private
         * @return void
         */
        private  function paramData( $date,$limit = 6){
            $year     = $date[0].$date[1].$date[2].$date[3];
            $month    = $date[4].$date[5];
            $timestmp = mktime( 0,0,0,$month,1,$year );
            $object = $this->data['instance'];
            $condition = $this->data['condition'];

            //表名age;
            //$sql = "select count(id) as num,idty from (select id,concat(floor(age/10)*10,'-',ceil(age/10)*10) as idty from age) as tmp group by idty order by idty";
            //todo 一条语句获得分组后的计数;

            //循环得到年月列表
            for( $i = 0; $i<$limit;$i++ ){
                $timestmp_temp    = $timestmp-( $i*28*24*60*60 );
                $key              = date( 'Ym',$timestmp_temp );
                $limit_time[$key]['content'] = date( 'Y年m月', $timestmp_temp);
                    
                //获得记录数
                if( $result = $object->fileAwayCount($key,$condition) ){
                    $limit_time[$key]['count'] = $result[0]['count(*)'];
                }else{
                    $limit_time[$key]['count'] = 0;
                }
            }

            return $limit_time;
        }
    }
