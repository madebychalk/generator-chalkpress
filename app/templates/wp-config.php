<?php

require_once('config/env-config.php');
EnvironmentConfig::initialize();

$table_prefix  = 'chlk_';

require_once(ABSPATH . 'wp-settings.php');
