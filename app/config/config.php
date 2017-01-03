<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => '/',
        'username'    => '/',
        'password'    => '/',
        'dbname'      => '/',
        'charset'     => 'utf8'
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'componentDir'   => APP_PATH . '/components/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'logDir'         => BASE_PATH . '/Runtime/',


        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        //'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
        'baseUri'        => '/'        
    ],
    
    "RSA_PRIVATE_KEY" => "MIICXgIBAAKBgQDE9GQeZdR6Qoqtm07+zdWxX4PiQz48+PWIyxHrqvr88qS4peCsmL9kz0rTEwUI2pykurlYB3b1e+yOw9Nnpf2rE4OqFRuDleUS2/dvyAgxlYFsQlhTrjpY82Jy55V3+Uj2nsHX0tf4M7qr2U+UQA1md/Pmd+7MWRZ6ORuj67BPcQIDAQABAoGBAJ+o2iyGMfu2S4M2npASPkPegSP/jMmSrEBrFijsXzCEZqHE3mAsJyNKo4Z+KZum1Er5j1xcGMQh5H9LIeoo9nu2k/S7zRckY+ieCZ+2J8T+fsagpU0wPd5DShzQfCqakMlUAWLOZ14BXShq6/8Bo8TDJjjkq7ztW1kwTefuzimtAkEA8LARaGW5u/3W3LieTCcJtqlx1a1G2tpWgxnkfhSZDkNWCRQ++wluxS4zM3kZ6F7AzDCjk5ZT5kSa0JBO8vvO4wJBANF8Ea56ugZCNJnHL+RNTzfegv9KRzIzaRqVAiS1LnhmKN8QBgZZZgQflx2eHBnsOjrmjEpAg59bcTobRy/PhJsCQHx8FATS2EWK/F4cfoMUjcmTyfSiMktvMd+MvMkZDjB6Uz1O42QjdM83HfQ5ZlTw7PavEWt8DNjEEu5cNPknk9ECQQCGRcczlhicoF0E4GazKFaJkgdXSS3/YHKTBkW8b6GcrKav655g/XZlWDZNVqXee8sLK/FqOpXjVAJsY0WqwJHXAkEAyMBl3x70ERChQCyoCjHcPC8YpqjgwBwT/AOTBRC75z22OHYV7WkJ67SBk5TH2XoiTO7KWsvm5ktkAS/Hf/Q5rg==",
    "RSA_PUBLIC_KEY"  => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDE9GQeZdR6Qoqtm07+zdWxX4PiQz48+PWIyxHrqvr88qS4peCsmL9kz0rTEwUI2pykurlYB3b1e+yOw9Nnpf2rE4OqFRuDleUS2/dvyAgxlYFsQlhTrjpY82Jy55V3+Uj2nsHX0tf4M7qr2U+UQA1md/Pmd+7MWRZ6ORuj67BPcQIDAQAB"
]);