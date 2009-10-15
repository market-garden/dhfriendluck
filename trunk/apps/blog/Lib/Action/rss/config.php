<?php
define('FEED_BLOG', 1);
define('FEED_IMAGE', 2);
define('FEED_VIDEO', 3);

final class friendfeed_config {
    public static $daemons = array(
        'master' => array(
            'host' => '127.0.0.1',
            'user' => 'root',
            'pass' => '',
            'name' => 'rss',
            'port' => 3306
        ),
        'slaves' => array(
            0 => array(
                'host' => '127.0.0.1',
                'user' => 'root',
                'pass' => '',
                'name' => 'rss',
                'port' => 3306
            )
        )
    );
    public static function get_both_conn() {
        if (self::$both_conn === null) {
            $d = self::$daemons['master'];
            $dsn = $d['host'].$d['user'].$d['pass'].$d['name'].$d['port'];
            if (isset(self::$conns[$dsn])) {
                $conn = self::$conns[$dsn];
            } else {
                $conn = mysqli_connect($d['host'], $d['user'], $d['pass'], $d['name'], $d['port']);
                if (!$conn) {
                    exit('Cannot connect to master');
                }
                self::$conns[$dsn] = $conn;
            }
            self::$both_conn = $conn;
        }
        return self::$both_conn;
    }
    public static function get_read_conn() {
        if (self::$read_conn === null) {
            $ds = array_merge(array(self::$daemons['master']), self::$daemons['slaves']);
            shuffle($ds);
            $d = $ds[0];
            $dsn = $d['host'].$d['user'].$d['pass'].$d['name'].$d['port'];
            if (isset(self::$conns[$dsn])) {
                $conn = self::$conns[$dsn];
            } else {
                $conn = mysqli_connect($d['host'], $d['user'], $d['pass'], $d['name'], $d['port']);
                if (!$conn) {
                    exit('Cannot connect to slave');
                }
                self::$conns[$dsn] = $conn;
            }
            self::$read_conn = $conn;
        }
        return self::$read_conn;
    }
    private static $both_conn = null;
    private static $read_conn = null;
    private static $conns = array();
}
