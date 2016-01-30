<?php

namespace Silex\Provider\RulerZ;

use Silex\Application;
use Silex\ServiceProviderInterface;

use RulerZ\Compiler\FileCompiler;
use RulerZ\Compiler\Target;
use RulerZ\Parser\HoaParser;

class RulerZServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['rulerz.compiler'] = $app->share(function() use ($app) {
            $cacheDir = isset($app['rulerz.cache_dir']) ? $app['rulerz.cache_dir'] : null;

            return new FileCompiler(new HoaParser(), $cacheDir);
        });

        $app['rulerz.compilation_targets'] = $app->share(function() {
            return [
                new Target\ArrayVisitor(),
            ];
        });

        $app['rulerz'] = $app->share(function($app) {
            return new \RulerZ\RulerZ($app['rulerz.compiler'], $app['rulerz.compilation_targets']);
        });
    }

    public function boot(Application $app)
    {
    }
}
