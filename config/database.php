<?php
if(!empty($_SERVER['SERVER_NAME']))
{
    $domain = $_SERVER['SERVER_NAME'];  
    $debug = true;
    if($domain == 'localhost')
    {
        if($debug)
        {
            $dbInfo['db_ip']       = '120.92.56.192';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'PVAxQjetsU8X2eN0Sw4T';
            $dbInfo['st_ip']       = '120.92.56.192';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'kCZTdI6CMhl35MUIu0DS';
        }
        else
        {
            $dbInfo['db_ip']       = '42.62.24.232';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'jTdDEZHonOrbR98YQn9q';
            $dbInfo['st_ip']       = '42.62.24.246';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'BmFO62n5PbQ7GJQCrlsL';
        }
    }
    if($domain == 'www.xfgame.com')
    {
        if($debug)
        {
            $dbInfo['db_ip']       = '120.92.56.192';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'PVAxQjetsU8X2eN0Sw4T';
            $dbInfo['st_ip']       = '120.92.56.192';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'kCZTdI6CMhl35MUIu0DS';
        }
        else
        {
            $dbInfo['db_ip']       = '42.62.24.232';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'jTdDEZHonOrbR98YQn9q';
            $dbInfo['st_ip']       = '42.62.24.246';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'BmFO62n5PbQ7GJQCrlsL';
        }
    }
    if($domain == 'localhost.xfgame.com')
    {
        if($debug)
        {
            $dbInfo['db_ip']       = '172.16.16.119';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'PVAxQjetsU8X2eN0Sw4T';
            $dbInfo['st_ip']       = '172.16.16.119';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'u_dbpassort';
            $dbInfo['st_password'] = 'dd0Kgmtfh9C2IHwg0S32';
        }
        else
        {
            $dbInfo['db_ip']       = '42.62.24.232';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'jTdDEZHonOrbR98YQn9q';
            $dbInfo['st_ip']       = '42.62.24.246';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'BmFO62n5PbQ7GJQCrlsL';
        }
    }



    if($domain == 'admin.game.87870.com')
    {
        if($debug)
        {
            $dbInfo['db_ip']       = '10.0.1.232';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'jTdDEZHonOrbR98YQn9q';
            $dbInfo['st_ip']       = '10.0.1.245';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'BmFO62n5PbQ7GJQCrlsL';
        }
        else
        {
            $dbInfo['db_ip']       = '10.0.1.245';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'jTdDEZHonOrbR98YQn9q';
            $dbInfo['st_ip']       = '10.0.1.245';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'BmFO62n5PbQ7GJQCrlsL';
        }
    }    
    
    if($domain == 'admin2016.game.87870.com')
    {
        if($debug)
        {
            $dbInfo['db_ip']       = '10.254.213.201';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'PVAxQjetsU8X2eN0Sw4T';
            $dbInfo['st_ip']       = '10.254.213.201';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'kCZTdI6CMhl35MUIu0DS';
        }
        else
        {
            $dbInfo['db_ip']       = '120.92.56.192';
            $dbInfo['db_db']       = 'db_xfplatformcenter';
            $dbInfo['db_username'] = 'u_gamecenter';
            $dbInfo['db_password'] = 'jTdDEZHonOrbR98YQn9q';
            $dbInfo['st_ip']       = '120.92.56.192';
            $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
            $dbInfo['st_username'] = 'statisuser';
            $dbInfo['st_password'] = 'kCZTdI6CMhl35MUIu0DS';
        }
    }

}
else
{
    $dbInfo['db_ip']       = '120.92.56.192';
    $dbInfo['db_db']       = 'db_xfplatformcenter';
    $dbInfo['db_username'] = 'u_gamecenter';
    $dbInfo['db_password'] = 'PVAxQjetsU8X2eN0Sw4T';
    $dbInfo['st_ip']       = '120.92.56.192';
    $dbInfo['st_db']       = 'db_xfplatformcenter_statistics';
    $dbInfo['st_username'] = 'statisuser';
    $dbInfo['st_password'] = 'kCZTdI6CMhl35MUIu0DS';
}

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'xfgame'),
    //'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'gamepub'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => 'ad_',
            'strict' => false,
            'engine' => null,
        ],
        
        'xfgame' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', $dbInfo['db_ip']),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', $dbInfo['db_db']),
            'username' => env('DB_USERNAME', $dbInfo['db_username']),
            'password' => env('DB_PASSWORD', $dbInfo['db_password']),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => 'ad_',
            'strict' => false,
            'engine' => null,
        ],
        'xfgame1' => [
            'driver' => 'mysql', 
            'host' => env('DB_HOST', $dbInfo['db_ip']),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', $dbInfo['db_db']),
            'username' => env('DB_USERNAME', $dbInfo['db_username']),
            'password' => env('DB_PASSWORD', $dbInfo['db_password']),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => 'dt_',
            'strict' => false,
            'engine' => null,
        ],
        'xfgame2' => [
            'driver' => 'mysql', 
            'host' => env('DB_HOST', $dbInfo['db_ip']),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', $dbInfo['db_db']),
            'username' => env('DB_USERNAME', $dbInfo['db_username']),
            'password' => env('DB_PASSWORD', $dbInfo['db_password']),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => 'game_',
            'strict' => false,
            'engine' => null,
        ],
        'statistics' => [
            'driver' => 'mysql', 
            'host' => env('DB_HOST', $dbInfo['st_ip']),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', $dbInfo['st_db']),
            'username' => env('DB_USERNAME', $dbInfo['st_username']),
            'password' => env('DB_PASSWORD', $dbInfo['st_password']),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => 'game_',
            'strict' => false,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
