<?php

$app->before(function () use ($app) {
  $app['route.name'] = $app['request_stack']->getMasterRequest()->attributes->get('_route');
  $app['route'] = $app['routes']->get($app['route.name']);
});