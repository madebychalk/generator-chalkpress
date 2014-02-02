'use strict';
var util = require('util');
var path = require('path');
var _    = require('lodash');
    _.str = require('underscore.string');
  
var spawn = require('child_process').spawn;
var yeoman = require('yeoman-generator');


var ChalkpressGenerator = module.exports = function ChalkpressGenerator(args, options, config) {
  yeoman.generators.Base.apply(this, arguments);

  this.on('end', function () {
    this.installDependencies({ skipInstall: options['skip-install'] });
  });

  this.pkg = JSON.parse(this.readFileAsString(path.join(__dirname, '../package.json')));
};

util.inherits(ChalkpressGenerator, yeoman.generators.Base);

ChalkpressGenerator.prototype.askFor = function askFor() {
  var done = this.async();

  console.log(this.yeoman);

  var prompts = [
    {
      name: 'blogName',
      message: 'What do you want to call the blog?'
    },{
      name: 'wordpressVersion',
      message: 'What version of Wordpress would you like?',
      default: '3.8.1'
    }
  ];

  this.prompt(prompts, function (props) {
    this.blogName = props.blogName;
    this.wordpressVersion = props.wordpressVersion;

    done();
  }.bind(this));
};

ChalkpressGenerator.prototype.app = function app() {
  this.template('_package.json', 'package.json');
  this.template('_bower.json', 'bower.json');
  this.template('_Gruntfile.js', 'Gruntfile.js');
  this.template('_README.md', 'README.md');

  this.copy('index.php', 'index.php');
  this.copy('wp-config.php', 'wp-config.php');

  this.mkdir('config');
  this.copy('env-config.php', 'config/env-config.php');


  this.directory('library', 'content/themes/' + _.str.slugify(this.blogName) + '/library');
  this.template('_style.css', 'content/themes/' + _.str.slugify(this.blogName) + '/style.css');
  this.template('_app.js', 'content/themes/' + _.str.slugify(this.blogName) + '/library/js/app.js');

  this.copy('index-blog.php', 'content/themes/' + _.str.slugify(this.blogName) + '/index.php');
  this.copy('footer.php', 'content/themes/' + _.str.slugify(this.blogName) + '/footer.php');
  this.copy('header.php', 'content/themes/' + _.str.slugify(this.blogName) + '/header.php');
  this.copy('front-page.php', 'content/themes/' + _.str.slugify(this.blogName) + '/front-page.php');
  this.copy('functions.php', 'content/themes/' + _.str.slugify(this.blogName) + '/functions.php');
  this.copy('screenshot.png', 'content/themes/' + _.str.slugify(this.blogName) + '/screenshot.png');

  this.mkdir('content/themes/' + _.str.slugify(this.blogName) + '-prod');
};

ChalkpressGenerator.prototype.gittyUp = function() {
  var done = this.async();

  spawn('git', ['init']).on('close', function() {
    console.log('Git repo initialized.');
    spawn('git', ['add', '.']).on('close', function() {
      spawn('git', ['commit', '-m', 'Initial Commit']).on('close', function() {
        console.log('Files added and committed.');
        done();
      });
    });
  });
}

ChalkpressGenerator.prototype.addWorpressSubmodule = function() {
  var done = this.async();

  console.log('Initializing Wordpress submodule. This may take a minute.');
  var git = spawn('git', ['submodule', 'add', 'git://github.com/WordPress/WordPress.git', 'wordpress'],
      { stdio: 'inherit' });


  git.on('close', function(){
      process.chdir('wordpress');
      console.log('Checking out %s branch of Wordpress', this.wordpressVersion);

      var checkout = spawn('git', ['checkout', this.wordpressVersion])
      
      checkout.stdout.on('data', function(data) {
        console.log(data);
      });

      checkout.on('close', function() {
        process.chdir('../');
        done();
      });

  }.bind(this));

  process.stdout.on('data', function(data) {
    console.log(data);
  });
}

ChalkpressGenerator.prototype.addCustomMetaBoxSubmodule = function() {
  var done = this.async();

  console.log('Initializing Metabox submodule. This may take a minute.');
  spawn('git', ['submodule', 'add', 'git://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress.git', 'content/themes/' + _.str.slugify(this.blogName) + '/library/metabox'])
    .on('close', function() {
      console.log('Metabox in the house.');
      done();
    });
}

ChalkpressGenerator.prototype.addChalkpressSubmodule = function() {
  var done = this.async();

  console.log('Initializing Chalkpress submodule. This may take a minute.');
  spawn('git', ['submodule', 'add', 'git://github.com/madebychalk/Chalkpress.git', 'content/themes/' + _.str.slugify(this.blogName) + '/library/chalkpress'])
    .on('close', function() {
      console.log('Chalkpress In Da House!');
      done();
    });
}
