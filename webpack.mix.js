const { mix } = require('laravel-mix');

mix.disableNotifications();

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts([
		'bower_components/jquery/dist/jquery.min.js',
		'bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
		'bower_components/tinymce/tinymce.min.js',
		'bower_components/tinymce/themes/modern/theme.js',
		'bower_components/jquery-ui/jquery-ui.min.js',
		'bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js',
      'bower_components/tinymce/plugins/image/plugin.min.js',
      'bower_components/tinymce/plugins/link/plugin.min.js',
		'resources/assets/js/app.js',
	], 'public/js/javascripts.js')
   .copy('bower_components/tinymce/skins/lightgray/fonts', 'public/css/tinymce/fonts/')
   .copy('bower_components/jquery-ui/themes/base/images', 'public/css/images/')
   .sass('bower_components/bootstrap-sass/assets/stylesheets/_bootstrap.scss', 'public/css/')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .styles([
   		'bower_components/jquery-ui/themes/base/theme.css',
   		'bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.css',
   		'bower_components/tinymce/skins/lightgray/skin.min.css',
   		'bower_components/tinymce/skins/lightgray/content.min.css',

   	], 'public/css/themes.css');
