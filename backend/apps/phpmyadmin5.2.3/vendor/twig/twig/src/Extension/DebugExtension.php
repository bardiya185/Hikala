<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Extension;

use Twig\Environment;
use Twig\Template;
use Twig\TemplateWrapper;
use Twig\TwigFunction;

final class DebugExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        $isDumpOutputHtmlSafe = \extension_loaded('xdebug')
            && (false === \ini_get('xdebug.overload_var_dump') || \ini_get('xdebug.overload_var_dump'))
            && (false === \ini_get('html_errors') || \ini_get('html_errors'))
            || 'cli' === \PHP_SAPI
        ;

        return [
            new TwigFunction('dump', [self::class, 'dump'], ['is_safe' => $isDumpOutputHtmlSafe ? ['html'] : [], 'needs_context' => true, 'needs_environment' => true, 'is_variadic' => true]),
        ];
    }

    /**
     * @internal
     */
    public static function dump(Environment $env, $context, ...$vars)
    {
        if (!$env->isDebug()) {
            return;
        }

        ob_start();

        if (!$vars) {
            $vars = [];
            foreach ($context as $key => $value) {
                if (!$value instanceof Template && !$value instanceof TemplateWrapper) {
                    $vars[$key] = $value;
                }
            }

            var_dump($vars);
        } else {
            var_dump(...$vars);
        }

        return ob_get_clean();
    }
}
