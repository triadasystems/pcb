<?php

return [

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

    'default' => env('DB_CONNECTION', 'mysql'),

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

        // 'mysql' => [
        //     'driver' => 'mysql',
        //     'host' => '10.7.15.205',
        //     'port' => '3329',
        //     'database' => 'inxdix_pes', // inxdix_pes
        //     'username' => 'template', // triada template
        //     'password' => 'eyJpdiI6ImViWkxqTTJmTFNLRVdHSmEzTmdxekE9PSIsInZhbHVlIjoiaXhUcFlcL09yTEd1WlVmdExFdTdkUVE9PSIsIm1hYyI6IjZiMTk0N2JlMTIzMzc2YjljNzQyNjNhOTM1MGU4MWQ2MWQzNTFjMGY1NzdhYWJmODBlMmIzNjg0ZTY4MGRiMjIifQ==', // Syst3m18 Pa$$w0rd01
        //     'charset' => 'latin1',
        //     'collation' => 'latin1_bin',
        //     'unix_socket' => env('DB_SOCKET', ''),
        //     // 'charset' => 'utf8mb4',
        //     // 'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'strict' => true,
        //     'engine' => null,
        //     'encriptado' => true,
        // ],

        // 'mysql' => [
        //     'driver' => 'mysql',
        //     'host' => env('DB_HOST', '10.7.15.204'),
        //     'port' => env('DB_PORT', '3329'),
        //     'database' => env('DB_DATABASE', 'inxdix_pes'),
        //     'username' => env('DB_USERNAME', 'template'), // triada
        //     'password' => env('DB_PASSWORD', 'Pa$$w0rd01_2018.'), // Syst3m18
        //     'charset' => 'latin1',
        //     'collation' => 'latin1_bin',
        //     'unix_socket' => env('DB_SOCKET', ''),
        //     // 'charset' => 'utf8mb4',
        //     // 'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'strict' => true,
        //     'engine' => null,
        // ],
        
        'mysql' => [
            'driver' => 'mysql',
            'host' => '10.7.6.177',
            'port' => '3306',
            'database' => 'pes2018_prod',
            'username' => 'triada', // triada
            'password' => 'eyJpdiI6Imx3YkNoWFAyMFpHUnNaNzdHbWcrOHc9PSIsInZhbHVlIjoiWVhhalwvaGxMbmVzcE5LWXRnY0xuY1E9PSIsIm1hYyI6IjcxNTUzN2M5ZTc5ZDJiNTdmODRmOGUxODIwNWVkN2ZkZDZlZDRmOGQzYzc0NzZhM2Q4Y2I2M2E0ZTUwNzM5YmIifQ==',
            'charset' => 'latin1',
            'collation' => 'latin1_bin',
            'unix_socket' => env('DB_SOCKET', ''),
            // 'charset' => 'utf8mb4',
            // 'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'encriptado' => true,
        ],

        // 'mysql' => [
        //     'driver' => 'mysql',
        //     'host' => 'localhost',
        //     'port' => '3306',
        //     'database' => 'pes2018_prod',
        //     'username' => 'root',
        //     'password' => '',
        //     'charset' => 'latin1',
        //     'collation' => 'latin1_bin',
        //     'unix_socket' => '',
        //     // 'charset' => 'utf8mb4',
        //     // 'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'strict' => true,
        //     'engine' => null,
        //     'encriptado' => false,
        // ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', ''),
            'port' => env('DB_PORT', ''),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],
        
        'dinamyc' => [
            'driver' => '',
            'host' => env('DB_HOST', ''),
            'port' => env('DB_PORT', ''),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
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

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],

        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],

];
