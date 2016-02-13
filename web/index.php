<?php
error_reporting(E_ALL & ~E_USER_DEPRECATED); // symfony 2.7 components warn for 3.0

use RedBeanPHP\R;

/*
 * Composer
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../database.php';

/*
 * Silex
 */
// Create application
$app = new Silex\Application();
$app['basepath'] = __DIR__;

/*
 * Development settings
 */
$app['debug'] = true;
R::debug(false);
R::freeze(false);
R::useWriterCache(false);

function log_dump($data) {
  file_put_contents(__DIR__ . '/../debug.txt', date('Y-m-d H:i:s') . ': ' . var_export($data, true) . PHP_EOL, FILE_APPEND);
}

/*
 * Services
 */
// 3rd party services
// More info for silex 2.0 https://github.com/silexphp/Pimple
// https://github.com/silexphp/Silex/wiki/Third-Party-ServiceProviders-for-Silex-2.x
$app->register(new Silex\Provider\TwigServiceProvider(), [
  'twig.path' => [
    __DIR__ . '/../src/View',
    __DIR__ . '/../src/Form',
    __DIR__ . '/../src/Form/Type'
  ],
  'twig.form.templates' => array_merge([
    'bootstrap_3_horizontal_layout.html.twig'
  ], array_map('basename', glob('../src/Form/Type/*.twig'))),
  'twig.options' => [
    'cache' => __DIR__ . '/../cache/twig',
    'strict_variable' => false
  ]
]);

$app->register(new Silex\Provider\RoutingServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider(), [
  'monolog.logfile' => __DIR__ . '/../app.log',
  'monolog.name' => 'app'
]);
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), [
  'locale_fallbacks' => ['en'],
]);
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\CsrfServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());

if ($app['debug']) {
  $app->register(new Silex\Provider\ServiceControllerServiceProvider());
  $app->register(new Silex\Provider\WebProfilerServiceProvider(), [
    'profiler.cache_dir' => __DIR__.'/../cache/profiler',
    'profiler.mount_prefix' => '/_profiler', // this is the default
  ]);
}

/*
 * Load controllers
 * Load middleware
 * Start app
 */
foreach (glob('../src/Controller/*.php') as $filename) {
  require_once $filename;
}

foreach (glob('../src/Middleware/*.php') as $filename) {
  require_once $filename;
}

foreach (glob('../src/Form/*.php') as $filename) {
  $forms[pathinfo($filename)['filename']] = require_once $filename;
}
if (isset($forms)) {
  $app['forms'] = $forms;
  unset($forms);
}

$app->run();
