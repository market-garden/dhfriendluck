<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * ThinkPHP 数据库中间层实现类
 * 支持Mysql 可以使用PDO
 +------------------------------------------------------------------------------
 * @category   Think
 * @package  Think
 * @subpackage  Db
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Db extends Base
{

    // 数据库类型
    protected $dbType           = null;

    // 是否自动释放查询结果
    protected $autoFree         = false;

    // 是否显示调试信息 如果启用会在日志文件记录sql语句
    public $debug             = false;

    // 是否使用永久连接
    protected $pconnect         = false;

    // 当前SQL指令
    protected $queryStr          = '';

    // 当前查询的结果数据集
    protected $resultSet         = null;

    // 最后插入ID
    protected $lastInsID         = null;

    // 返回或者影响记录数
    protected $numRows        = 0;

    // 返回字段数
    protected $numCols          = 0;

    // 事务指令数
    protected $transTimes      = 0;

    // 错误信息
    protected $error              = '';

    // 数据库连接ID 支持多个连接
    protected $linkID              = array();

    // 当前连接ID
    protected $_linkID            =   null;

    // 当前查询ID
    protected $queryID          = null;

    // 是否已经连接数据库
    protected $connected       = false;

    // 数据库连接参数配置
    protected $config             = '';

    // SQL 执行时间记录
    protected $beginTime;
    // 数据库表达式
    protected $comparison      = array('eq'=>'=','neq'=>'!=','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE');
    // 查询表达式
    protected $selectSql  =     'SELECT%DISTINCT% %FIELDS% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT%';

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $config 数据库配置数组
     +----------------------------------------------------------
     */
    function __construct($config=''){
        return $this->factory($config);
    }

    /**
     +----------------------------------------------------------
     * 取得数据库类实例
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @return mixed 返回数据库驱动类
     +----------------------------------------------------------
     */
    public static function getInstance()
    {
        $args = func_get_args();
        return get_instance_of(__CLASS__,'factory',$args);
    }

    /**
     +----------------------------------------------------------
     * 加载数据库 支持配置文件或者 DSN
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $db_config 数据库配置信息
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function &factory($db_config='')
    {
        // 读取数据库配置
        $db_config = $this->parseConfig($db_config);
        if(empty($db_config['dbms'])) {
            throw_exception(L('_NO_DB_CONFIG_'));
        }
        // 数据库类型
        $this->dbType = ucwords(strtolower($db_config['dbms']));
        // 读取系统数据库驱动目录
        $dbClass = 'Db'. $this->dbType;
        $dbDriverPath = dirname(__FILE__).'/Driver/';
        require_cache( $dbDriverPath . $dbClass . '.class.php');

        // 检查驱动类
        if(class_exists($dbClass)) {
            $db = new $dbClass($db_config);
            // 获取当前的数据库类型
            if( 'pdo' != strtolower($db_config['dbms']) ) {
                $db->dbType = strtoupper($this->dbType);
            }else{
                $db->dbType = $this->_getDsnType($db_config['dsn']);
            }
            if(C('DEBUG_MODE')) {
                $db->debug    = true;
            }
        }else {
            // 类没有定义
            throw_exception(L('_NOT_SUPPORT_DB_').': ' . $db_config['dbms']);
        }
        return $db;
    }

    /**
     +----------------------------------------------------------
     * 根据DSN获取数据库类型 返回大写
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param string $dsn  dsn字符串
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function _getDsnType($dsn) {
        $match  =  explode(':',$dsn);
        $dbType = strtoupper(trim($match[0]));
        return $dbType;
    }

    /**
     +----------------------------------------------------------
     * 分析数据库配置信息，支持数组和DSN
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param mixed $db_config 数据库配置信息
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    private function parseConfig($db_config='') {
        if ( !empty($db_config) && is_string($db_config)) {
            // 如果DSN字符串则进行解析
            $db_config = $this->parseDSN($db_config);
        }else if(empty($db_config)){
            // 如果配置为空，读取配置文件设置
            $db_config = array (
                'dbms'        =>   C('DB_TYPE'),
                'username'  =>   C('DB_USER'),
                'password'   =>   C('DB_PWD'),
                'hostname'  =>   C('DB_HOST'),
                'hostport'    =>   C('DB_PORT'),
                'database'   =>   C('DB_NAME'),
                'dsn'          =>   C('DB_DSN'),
                'params'     =>   C('DB_PARAMS'),
            );
        }
        return $db_config;
    }

    /**
     +----------------------------------------------------------
     * 增加数据库连接(相同类型的)
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $config 数据库连接信息
     * @param mixed $linkNum  创建的连接序号
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function addConnect($config,$linkNum=null) {
        $db_config  =   $this->parseConfig($config);
        if(empty($linkNum)) {
            $linkNum     =   count($this->linkID);
        }
        if(isset($this->linkID[$linkNum])) {
            // 已经存在连接
            return false;
        }
        // 创建新的数据库连接
        return $this->connect($db_config,$linkNum);
    }

    /**
     +----------------------------------------------------------
     * 切换数据库连接
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param integer $linkNum  创建的连接序号
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function switchConnect($linkNum) {
        if(isset($this->linkID[$linkNum])) {
            // 存在指定的数据库连接序号
            $this->_linkID  =   $this->linkID[$linkNum];
            return true;
        }else{
            return false;
        }
    }

    /**
     +----------------------------------------------------------
     * 初始化数据库连接
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param boolean $master 主服务器
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    protected function initConnect($master=true) {
        if(1 == C('DB_DEPLOY_TYPE')) {
            // 采用分布式数据库
            $this->_linkID = $this->multiConnect($master);
        }else{
            // 默认单数据库
            if ( !$this->connected ) $this->_linkID = $this->connect();
        }
    }

    /**
     +----------------------------------------------------------
     * 连接分布式服务器
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param boolean $master 主服务器
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    protected function multiConnect($master=false) {
        static $_config = array();
        if(empty($_config)) {
            // 缓存分布式数据库配置解析
            foreach ($this->config as $key=>$val){
                $_config[$key]      =   explode(',',$val);
            }
        }
        // 数据库读写是否分离
        if(C('DB_RW_SEPARATE')){
            // 主从式采用读写分离
            if($master) {
                // 默认主服务器是连接第一个数据库配置
                $r  =   0;
            }else{
                // 读操作连接从服务器
                $r = floor(mt_rand(1,count($_config['hostname'])-1));   // 每次随机连接的数据库
            }
        }else{
            // 读写操作不区分服务器
            $r = floor(mt_rand(0,count($_config['hostname'])-1));   // 每次随机连接的数据库
        }
        $db_config = array(
            'username'  =>   isset($_config['username'][$r])?$_config['username'][$r]:$_config['username'][0],
            'password'   =>   isset($_config['password'][$r])?$_config['password'][$r]:$_config['password'][0],
            'hostname'  =>   isset($_config['hostname'][$r])?$_config['hostname'][$r]:$_config['hostname'][0],
            'hostport'    =>   isset($_config['hostport'][$r])?$_config['hostport'][$r]:$_config['hostport'][0],
            'database'   =>   isset($_config['database'][$r])?$_config['database'][$r]:$_config['database'][0],
            'dsn'          =>   isset($_config['dsn'][$r])?$_config['dsn'][$r]:$_config['dsn'][0],
            'params'     =>   isset($_config['params'][$r])?$_config['params'][$r]:$_config['params'][0],
        );
        return $this->connect($db_config,$r);
    }

    /**
     +----------------------------------------------------------
     * DSN解析
     * 格式： mysql://username:passwd@localhost:3306/DbName
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $dsnStr
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    public function parseDSN($dsnStr)
    {
        if( empty($dsnStr) ){return false;}
        $info = parse_url($dsnStr);
        if($info['scheme']){
            $dsn = array(
            'dbms'        => $info['scheme'],
            'username'  => isset($info['user']) ? $info['user'] : '',
            'password'   => isset($info['pass']) ? $info['pass'] : '',
            'hostname'  => isset($info['host']) ? $info['host'] : '',
            'hostport'    => isset($info['port']) ? $info['port'] : '',
            'database'   => isset($info['path']) ? substr($info['path'],1) : ''
            );
        }else {
            preg_match('/^(.*?)\:\/\/(.*?)\:(.*?)\@(.*?)\:([0-9]{1, 6})\/(.*?)$/',trim($dsnStr),$matches);
            $dsn = array (
            'dbms'        => $matches[1],
            'username'  => $matches[2],
            'password'   => $matches[3],
            'hostname'  => $matches[4],
            'hostport'    => $matches[5],
            'database'   => $matches[6]
            );
        }
        return $dsn;
     }

    /**
     +----------------------------------------------------------
     * 数据库调试 记录当前SQL
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     */
    protected function debug() {
        // 记录操作结束时间
        if ( $this->debug )    {
            $runtime    =   number_format(microtime(TRUE) - $this->beginTime, 6);
            Log::record(" RunTime:".$runtime."s SQL = ".$this->queryStr,Log::SQL);
        }
    }

    /**
     +----------------------------------------------------------
     * set分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param array $data
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseSet($data) {
        $setStr  =  '';
        foreach ($data as $key=>$val){
            $value   =  $this->parseValue($val);
            if(is_scalar($value)) { // 过滤非标量数据
                $set[]    = $this->addSpecialChar($key).'='.$value;
            }
        }
        $setStr  =  implode(',',$set);
        return ' SET '.$setStr;
    }

    /**
     +----------------------------------------------------------
     * value分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $value
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseValue($value) {
        if(is_int($value)) {
            $value = intval($value);
        }elseif(is_float($value)) {
            $value = floatval($value);
        }elseif(is_string($value)) {
            $value = '\''.$this->escape_string($value).'\'';
        }elseif(isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp'){
            $value   =  $this->escape_string($value[1]);
        }elseif(is_null($value)){
            $value   =  'null';
        }
        return $value;
    }

    /**
     +----------------------------------------------------------
     * field分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $fields
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseField($fields) {
        if(is_array($fields)) {
            // 完善数组方式传字段名的支持
            // 支持 'field1'=>'field2' 这样的字段别名定义
            $array   =  array();
            foreach ($fields as $key=>$field){
                if(!is_numeric($key)) {
                    $array[] =  $this->addSpecialChar($key).' AS '.$this->addSpecialChar($field);
                }else{
                    $array[] =  $this->addSpecialChar($field);
                }
            }
            $fieldsStr = implode(',', $array);
        }elseif(is_string($fields) && !empty($fields)) {
            $fieldsStr = $this->addSpecialChar($fields);
        }else{
            $fieldsStr = '*';
        }
        return $fieldsStr;
    }

    /**
     +----------------------------------------------------------
     * table分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $table
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseTable($tables) {
        $parseStr   =   '';
        if(is_string($tables)) {
            $tables  =  explode(',',$tables);
        }
        array_walk($tables, array(&$this, 'addSpecialChar'));
        $parseStr   =  implode(',',$tables);
        return $parseStr;
    }

    /**
     +----------------------------------------------------------
     * where分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $where
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseWhere($where) {
        $whereStr = '';
        if(is_string($where) && strpos($where,'&')) parse_str($where,$where);
        if(is_string($where)) {
            // 直接使用字符串条件
            $whereStr = $where;
        }else{ // 使用数组条件表达式
            if(array_key_exists('_logic',$where)) {
                // 定义逻辑运算规则 例如 OR XOR AND NOT
                $operate    =   ' '.strtoupper($where['_logic']).' ';
                unset($where['_logic']);
            }else{
                // 默认进行 AND 运算
                $operate    =   ' AND ';
            }
            foreach ($where as $key=>$val){
                $key = $this->addSpecialChar($key);
                $whereStr .= "( ";
                if(is_array($val)) {
                    if(is_string($val[0])) {
                        if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT|NOTLIKE|LIKE)$/i',$val[0])) { // 比较运算
                            $whereStr .= $key.' '.$this->comparison[strtolower($val[0])].' '.$this->parseValue($val[1]);
                        }elseif('exp'==strtolower($val[0])){ // 使用表达式
                            $whereStr .= ' ('.$key.' '.$val[1].') ';
                        }elseif(preg_match('/IN/i',$val[0])){ // IN 运算
                            $zone   =   is_array($val[1])? implode(',',$this->parseValue($val[1])):$val[1];
                            $whereStr .= $key.' '.strtoupper($val[0]).' ('.$zone.')';
                        }elseif(preg_match('/BETWEEN/i',$val[0])){ // BETWEEN运算
                            $data = is_string($val[1])? explode(',',$val[1]):$val[1];
                            $whereStr .=  ' ('.$key.' BETWEEN '.$data[0].' AND '.$data[1].' )';
                        }else{
                            throw_exception(L('_EXPRESS_ERROR_').':'.$val[0]);
                        }
                    }else {
                        $count = count($val);
                        if(in_array(strtoupper(trim($val[$count-1])),array('AND','OR','XOR'))) {
                            $rule = strtoupper(trim($val[$count-1]));
                            $count   =  $count -1;
                        }else{
                            $rule = 'AND';
                        }
                        for($i=0;$i<$count;$i++) {
                            $data = is_array($val[$i])?$val[$i][1]:$val[$i];
                            if('exp'==strtolower($val[$i][0])) {
                                $whereStr .= '('.$key.' '.$data.') '.$rule.' ';
                            }else{
                                $op = is_array($val[$i])?$this->comparison[strtolower($val[$i][0])]:'=';
                                $whereStr .= '('.$key.' '.$op.' '.$this->parseValue($data).') '.$rule.' ';
                            }
                        }
                        $whereStr = substr($whereStr,0,-4);
                    }
                }else {
                    //对字符串类型字段采用模糊匹配
                    if(C('LIKE_MATCH_FIELDS') && preg_match('/('.C('LIKE_MATCH_FIELDS').')/i',$key)) {
                        $val = '%'.$val.'%';
                        $whereStr .= $key." LIKE ".$this->parseValue($val);
                    }else {
                        $whereStr .= $key." = ".$this->parseValue($val);
                    }
                }
                $whereStr .= ' )'.$operate;
            }
            $whereStr = substr($whereStr,0,-strlen($operate));
        }
        return empty($whereStr)?'':' WHERE '.$whereStr;
    }

    /**
     +----------------------------------------------------------
     * limit分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $lmit
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseLimit($limit) {
        return !empty($limit)?   ' LIMIT '.$limit.' ':'';
    }

    /**
     +----------------------------------------------------------
     * join分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $join
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseJoin($join) {
        $joinStr = '';
        if(!empty($join)) {
            if(is_array($join)) {
                foreach ($join as $key=>$_join){
                    if(false !== stripos($_join,'JOIN')) {
                        $joinStr .= ' '.$_join;
                    }else{
                        $joinStr .= ' LEFT JOIN ' .$_join;
                    }
                }
            }else{
                $joinStr .= ' LEFT JOIN ' .$join;
            }
        }
        return $joinStr;
    }

    /**
     +----------------------------------------------------------
     * order分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $order
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseOrder($order) {
        return !empty($order)?  ' ORDER BY '.$order:'';
    }

    /**
     +----------------------------------------------------------
     * group分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $group
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseGroup($group)
    {
        return !empty($group)? ' GROUP BY '.$group:'';
    }

    /**
     +----------------------------------------------------------
     * having分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param string $having
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseHaving($having)
    {
        return  !empty($having)?   ' HAVING '.$having:'';
    }

    /**
     +----------------------------------------------------------
     * distinct分析
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $distinct
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function parseDistinct($distinct) {
        return !empty($distinct)?   ' DISTINCT ' :'';
    }

    /**
     +----------------------------------------------------------
     * 插入记录
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $data 数据
     * @param array $options 参数表达式
     +----------------------------------------------------------
     * @return false | integer
     +----------------------------------------------------------
     */
    public function insert($data,$options=array()) {
        foreach ($data as $key=>$val){
            $value   =  $this->parseValue($val);
            if(is_scalar($value)) { // 过滤非标量数据
                $values[]   =  $value;
                $fields[]     =  $this->addSpecialChar($key);
            }
        }
        $sql   =  'INSERT INTO '.$this->parseTable($options['table']).' ('.implode(',', $fields).') VALUES ('.implode(',', $values).')';
        return $this->execute($sql);
    }

    /**
     +----------------------------------------------------------
     * 更新记录
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $data 数据
     * @param array $options 表达式
     +----------------------------------------------------------
     * @return false | integer
     +----------------------------------------------------------
     */
    public function update($data,$options) {
        $sql   = 'UPDATE '
            .$this->parseTable($options['table'])
            .$this->parseSet($data)
            .$this->parseWhere(isset($options['where'])?$options['where']:'')
            .$this->parseOrder(isset($options['order'])?$options['order']:'')
            .$this->parseLimit(isset($options['limit'])?$options['limit']:'');
        return $this->execute($sql);
    }

    /**
     +----------------------------------------------------------
     * 删除记录
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 表达式
     +----------------------------------------------------------
     * @return false | integer
     +----------------------------------------------------------
     */
    public function delete($options=array())
    {
        $sql   = 'DELETE FROM '
            .$this->parseTable($options['table'])
            .$this->parseWhere(isset($options['where'])?$options['where']:'')
            .$this->parseOrder(isset($options['order'])?$options['order']:'')
            .$this->parseLimit(isset($options['limit'])?$options['limit']:'');
        return $this->execute($sql);
    }

    /**
     +----------------------------------------------------------
     * 查找记录
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 表达式
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    public function select($options=array()) {
        $sql   = str_replace(
            array('%TABLE%','%DISTINCT%','%FIELDS%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%'),
            array(
                $this->parseTable($options['table']),
                $this->parseDistinct(isset($options['distinct'])?$options['distinct']:false),
                $this->parseField(isset($options['field'])?$options['field']:'*'),
                $this->parseJoin(isset($options['join'])?$options['join']:''),
                $this->parseWhere(isset($options['where'])?$options['where']:''),
                $this->parseGroup(isset($options['group'])?$options['group']:''),
                $this->parseHaving(isset($options['having'])?$options['having']:''),
                $this->parseOrder(isset($options['order'])?$options['order']:''),
                $this->parseLimit(isset($options['limit'])?$options['limit']:'')
            ),
            $this->selectSql);
        return $this->query($sql);
    }

    /**
     +----------------------------------------------------------
     * 字段和表名添加`
     * 保证指令中使用关键字不出错 针对mysql
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $value
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    protected function addSpecialChar(&$value) {
        if(0 === strpos($this->dbType,'MYSQL')){
            $value   =  trim($value);
            if( false !== strpos($value,' ') || false !== strpos($value,',') || false !== strpos($value,'*') ||  false !== strpos($value,'(') || false !== strpos($value,'.') || false !== strpos($value,'`')) {
                //如果包含* 或者 使用了sql方法 则不作处理
            }else{
                $value = '`'.$value.'`';
            }
        }
        return $value;
    }

    /**
     +----------------------------------------------------------
     * 查询数据方法，支持动态缓存
     * 动态缓存方式为可配置，默认为文件方式
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $sql  查询语句
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    public function query($sql)
    {
        // 进行查询
        return $this->_query($sql);
    }

    /**
     +----------------------------------------------------------
     * 数据库操作方法
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $sql  执行语句
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function execute($sql)
    {
        return $this->_execute($sql);
    }

    /**
     +----------------------------------------------------------
     * 查询次数更新或者查询
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $times
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function Q($times='') {
        static $_times = 0;
        if(empty($times)) {
            return $_times;
        }else{
            $_times++;
            // 记录开始执行时间
            $this->beginTime = microtime(TRUE);
        }
    }

    /**
     +----------------------------------------------------------
     * 写入次数更新或者查询
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $times
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function W($times='') {
        static $_times = 0;
        if(empty($times)) {
            return $_times;
        }else{
            $_times++;
            // 记录开始执行时间
            $this->beginTime = microtime(TRUE);
        }
    }

    /**
     +----------------------------------------------------------
     * 获取最近一次查询的sql语句
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function getLastSql() {
        return $this->queryStr;
    }

}//类定义结束
?>