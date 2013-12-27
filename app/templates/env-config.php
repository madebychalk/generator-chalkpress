<?php

class EnvironmentConfig {

  /* Environment Defaults
   * 
   * These will be used across all environments unless
   * explicitly overwritten below
   *
   * */
  private static $defaults = array(
    'DB_CHARSET'              => 'utf8',
    'DB_COLLATE'              => '',
    'WP_HTTP_BLOCK_EXTERNAL'  => true,
    'DISABLE_WP_CRON'         => true,
    'WPLANG'                  => '',
    'WP_DEBUG'                => false,
    'AUTH_KEY'                => '],(,!h!*eE`--hd&nbclKZ/NWFfjryb`#G&o}++9Db0G6!|EFe=0?(8!GXEBFe~|',
    'SECURE_AUTH_KEY'         => 'E=|9/nUUW!b+x)6[<SP4;ney|lFr2Y9-QXyh=SChM@ 4S8@x1T!k2[52x9oX.+MJ',
    'LOGGED_IN_KEY'           => 'SVOP,8IGCUH5:%f,{ocv,A<`|&7xfz=-!n)|Vg[^qj4v3C-=%g)j6Y>>-(AMrV]x',
    'NONCE_KEY'               => '^NSP-_F{.9shI-e#X/|$tTu[9&V6.Tqf12&yLktYIiPD-Cj!&;,Jjh-I}srP9!;o',
    'AUTH_SALT'               => 'fPqsuTAl2?uN y,:i|<{<&``|[Rhm|fIrWu.|a07?SsUof7d| eB[wt$IH3|;>*X',
    'SECURE_AUTH_SALT'        => 'RE+lCPbs8w{Y+}mH>sA|BTx?|Jj2/N&|p;V[eqo-G:+!d@l=a1-VUh**$NM9pN|e',
    'LOGGED_IN_SALT'          => 'v&U(<-jV!vB+<)Fw(GtPMJwOh;<rS#qma1;MWfQ0RSD`<~vMB y%1o(Ed_-,}gIH',
    'NONCE_SALT'              => '[3rvlbCHIaU=-P8RYkz1J+:_yfn}r=3*g]{+eC6N`%W-Jy.Vco>gvimw12G(V>_/'
  );

  private static function environments()
  {
    return array(

      /* Development Environment
       *
       * Settings used on the development environment
       * The key must match the tld and hostname common
       * keys might be 127.0.0.1, localhost, xip.io, if
       * xip.io is recommended since it will allow testing
       * from multiple devices on your network
       */
      'xip.io' => array(
        'WP_SITEURL'      => 'http://' . $_SERVER['SERVER_NAME'] . ':9000/wordpress',
        'WP_HOME'         => 'http://' . $_SERVER['SERVER_NAME'] . ':9000',
        'WP_CONTENT_DIR'  => dirname( dirname( __FILE__ ) ) . '/content',
        'WP_CONTENT_URL'  => 'http://' . $_SERVER['SERVER_NAME'] . ':9000/content',
        'WP_PLUGIN_DIR '  => dirname( dirname(__FILE__) ) . ':9000/content/plugins',
        'WP_PLUGIN_URL '  => 'http://' . $_SERVER['SERVER_NAME'] . ':9000/content/plugins',
        'DB_NAME'         => '',
        'DB_USER'         => '',
        'DB_PASSWORD'     => '',
        'DB_HOST'         => 'localhost',
        'WP_DEBUG'        => true
      ),

      /* Staging Environment
       *
       * Settings used in staging, see dev notes above
       */
      'wearechalk.com' => array(
        'WP_SITEURL'      => 'http://' . $_SERVER['SERVER_NAME'] . '/wordpress',
        'WP_HOME'         => 'http://' . $_SERVER['SERVER_NAME'],
        'WP_CONTENT_DIR'  => dirname( dirname(__FILE__) ) . '/content',
        'WP_CONTENT_URL'  => 'http://' . $_SERVER['SERVER_NAME'] . '/content',
        'WP_PLUGIN_DIR '  => dirname( dirname(__FILE__) ) . '/content/plugins',
        'WP_PLUGIN_URL '  => 'http://' . $_SERVER['SERVER_NAME'] . '/content/plugins',
        'DB_NAME'         => '',
        'DB_USER'         => '',
        'DB_PASSWORD'     => '',
        'DB_HOST'         => '',
        'WP_DEBUG'        => true
      ),

      /* Production Environment
       *
       * Settings used in production
       */
      'example.com' => array(
        'WP_SITEURL'      => 'http://' . $_SERVER['SERVER_NAME'] . '/wordpress',
        'WP_HOME'         => 'http://' . $_SERVER['SERVER_NAME'],
        'WP_CONTENT_DIR'  => dirname( dirname(__FILE__) ) . '/content',
        'WP_CONTENT_URL'  => 'http://' . $_SERVER['SERVER_NAME'] . '/content',
        'WP_PLUGIN_DIR '  => dirname( dirname(__FILE__) ) . '/content/plugins',
        'WP_PLUGIN_URL '  => 'http://' . $_SERVER['SERVER_NAME'] . '/content/plugins',
        'DB_NAME'         => '',
        'DB_USER'         => '',
        'DB_PASSWORD'     => '',
        'DB_HOST'         => '',
        'WP_DEBUG'        => true
      )
    );
  }

  public static function initialize()
  {
    foreach(self::environments() as $key => $value)
    {

      if(substr_compare($_SERVER['SERVER_NAME'], $key, -strlen($key), strlen($key)) === 0)
      {
        self::define_constants($value);
        break;
      }
    }
  }

  public static function define_constants($consts)
  {
    $consts = array_merge(self::$defaults, $consts);
    foreach($consts as $key => $value)
    {
      define($key, $value);
    }
  }
};

