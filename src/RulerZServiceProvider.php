<?php

namespace Silex\Provider\RulerZ;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use RulerZ\Compiler\FileCompiler;
use RulerZ\Compiler\Target;
use RulerZ\Parser\HoaParser;

class RulerZServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['rulerz.evaluator'] = function () use ($app) {
            $cacheDir = isset($app['rulerz.cache_dir']) ? $app['rulerz.cache_dir'] : null;

            return new \RulerZ\Compiler\FileEvaluator($cacheDir);
        };

        $app['rulerz.compiler'] = function () use ($app) {
            return new \RulerZ\Compiler\Compiler($app['rulerz.evaluator']);
        };

        $app['rulerz.compilation_targets'] = function() {
            return [
                 new \RulerZ\Target\Native\Native([
                    'length' => 'strlen',
                ]),
            ];
        };

        $app['rulerz'] = function () use ($app) {
            return new \RulerZ\RulerZ($app['rulerz.compiler'], $app['rulerz.compilation_targets']);
        };
    }
}
