let mix = require('laravel-mix');

mix.css('public/iframe/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css', 'public/css/laravel-mix')
.css('public/iframe/assets/plugins/global/plugins.bundle.css', 'public/css/laravel-mix')
.css('public/iframe/assets/css/style.bundle.css', 'public/css/laravel-mix')
.js('public/plugins/bower_components/jquery/dist/jquery.min.js', 'public/js/laravel-mix')
// .js('public/iframe/assets/plugins/global/plugins.bundle.js', 'public/js/laravel-mix')
.js('public/iframe/assets/js/scripts.bundle.js', 'public/js/laravel-mix')
// .js('public/iframe/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js', 'public/js/laravel-mix')
.js('public/iframe/assets/js/pages/crud/forms/widgets/select2.js', 'public/js/laravel-mix')
.js('public/iframe/assets/js/pages/dashboard.js', 'public/js/laravel-mix')
// .js('public/vendor/signature/js/jquery-ui.min.js', 'public/js/laravel-mix')
.js('public/vendor/signature/js/signature_pad.umd.js', 'public/js/laravel-mix')
.js('public/vendor/signature/js/app.js', 'public/js/laravel-mix');