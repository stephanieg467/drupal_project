<?php

$config_directories['sync'] = '../config/sync';
$databases['default']['default'] = array (
    'database' => 'sgalata',
    'username' => 'sgalata',
    'password' => 'vKzL4udxgg1smngz7od7ZHRUU3XsyStH',
    'prefix' => '',
    'host' => 'localhost',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
);
$settings['install_profile'] = 'orange_profile';

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

