'use strict';

var LIVERELOAD_PORT = 35729;
var lrSnippet = require('connect-livereload')({port: LIVERELOAD_PORT});
var mountFolder = function (connect, dir) {
    return connect.static(require('path').resolve(dir));
};

var gateway = require('gateway');
var phpGateway = function (dir){
    return gateway(require('path').resolve(dir), {
        '.php': 'php-cgi'
    });
};

module.exports = function(grunt) {
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  var chalkPressConfig = {
    themeDir: 'content/themes/<%= _.slugify(blogName) %>',
    library:  'content/themes/<%= _.slugify(blogName) %>/library',
  };

  chalkPressConfig.prodDir = chalkPressConfig.themeDir + '-prod';

  grunt.initConfig({
    chalk: chalkPressConfig, 
    watch : {
      compass : {
        files : ['<%%= chalk.library %>/scss/**/*.scss'],
        tasks : ['compass:dev']
      },

      livereload: {
        options: {
          livereload: LIVERELOAD_PORT,
          nospawn: true
        },

        files: [
          '<%%= chalk.themeDir %>/**/*.php',
          '<%%= chalk.library %>/css/**/*.css',
          '<%%= chalk.library %>/scss/**/*.scss'
        ]
      }
    },

    connect: {
      options: {
        port: 9000,
        hostname: '0.0.0.0'
      },

      livereload: {
        options: {
          middleware: function (connect) {
            return [
              lrSnippet,
              phpGateway('.'),
              mountFolder(connect, '.')
            ];
          }
        }
      }
    },

    open: {
      server: {
        path: 'http://127.0.0.1.xip.io:<%%= connect.options.port %>/'
      }
    },

    clean: {
      dist: {
        files: [{
          dot: true,
          src: [
            '<%%= chalk.prodDir %>/*',
            '!<%%= chalk.prodDir %>/.git*'
          ]
        }]
      }
    },

    compass : {
      options: {
        sassDir:        '<%%= chalk.library %>/scss',
        importPath:     '<%%= chalk.library %>/vendor', 
        relativeAssets: true
      },

      dev: {
        options: {
          cssDir: '<%%= chalk.library %>/css',
          debugInfo: true,
          environment: 'development',
          outputStyle: 'expanded',
          javascriptsDir: '<%%= chalk.library %>/js',
          imagesDir:      '<%%= chalk.library %>/images', 
          fontsDir:       '<%%= chalk.library %>/fonts' 
        }
      },

      dist: {
        options: {
          cssDir: '<%%= chalk.prodDir %>/library/css',
          debugInfo: false,
          environment: 'production',
          outputStyle: 'compressed',
          javascriptsDir: '<%%= chalk.prodDir %>/library/js',
          imagesDir:      '<%%= chalk.prodDir %>/library/images', 
          fontsDir:       '<%%= chalk.prodDir %>/library/fonts' 
        }
      }
    },

    bower: {
      install: {
        options: {
          targetDir: '<%%= chalk.library %>/vendor',
          cleanTargetDir: true,
          cleanBowerDir: true,
          forceLatest: true,
          verbose: true
        }
      }
    },

    requirejs: {
      options: {
        name: "app",
        baseUrl: "<%%= chalk.library %>/js",
        mainConfigFile: "<%%= chalk.library %>/js/app.js",
        paths: {
          requireLib: "../vendor/js/requirejs/require"
        },
        include: "requireLib"
      },

      dist: {
        options: {
          out: "<%%= chalk.prodDir %>/library/js/app.min.js",
          preserveLicenseComments: false
        }
      }
    },

    modernizr: {
      devFile: "<%%= chalk.library %>/vendor/js/modernizr/modernizr.js",
      outputFile: "<%%= chalk.prodDir %>/library/js/modernizr.min.js",
      extra: {
        load: false,
        cssclasses: true
      },
      files: [
        "<%%= chalk.library %>/**/*.js",
        "<%%= chalk.library %>/**/*.scss",
      ]
    },

    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: "<%%= chalk.library %>/images",
          dest: "<%%= chalk.prodDir %>/library/images",
          src: [
            "**/*.{png,jpg,gif}"
          ]
        }]
      }
    },

    processhtml: {
      dist: {
        files: {
          "<%%= chalk.prodDir %>/footer.php": "<%%= chalk.themeDir %>/footer.php",
          "<%%= chalk.prodDir %>/header.php": "<%%= chalk.themeDir %>/header.php"
        }
      }
    },

    copy: {
      dist: {
        files: [{
          expand: true,
          dot: true,
          cwd: "<%%= chalk.themeDir %>",
          dest: "<%%= chalk.prodDir %>",
          src: [
            '**/*.{php,eot,ttf,woff,svg,js,css,png}',
            '!library/vendor/**/*.{js,css,png,jpg,gif}',
            '!library/images/**/*.{png,jpg,gif}',
            '!library/css/**/*.css',
            '!library/js/**/*.js'
          ]
        }]
      }
    }



  });

  grunt.registerTask('server', [
    'compass:dev', 
    'connect:livereload', 
    'open', 
    'watch'
  ]);

  grunt.registerTask('build', [
    'clean:dist',
    'copy:dist',
    'imagemin:dist',
    'requirejs:dist',
    'modernizr',
    'compass:dist', 
    'processhtml:dist'
  ]);
};
