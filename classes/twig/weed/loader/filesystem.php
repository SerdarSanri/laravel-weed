<?php
/**
 * Weed is a bundle that handle the rendering of views using Twig template
 * engine.
 *
 * The work is based on the TwigView bundle by akhawaja.
 *
 * @package     Weed
 * @version     1.0
 * @author      Amir Khawaja, marketyou Development Team
 * @license     BSD License (3-clause)
 * @copyright   2012 Amir Khawaja, 2012 marketyou
 * @link        https://github.com/akhawaja/TwigView
 * @link        https://github.com/marketyou/laravel-weed
 */
namespace Weed\Twig\Weed\Loader;

/**
 * Loads template from the laravel filesystem.
 *
 * @package    twig
 * @author     Isern Palaus <ipalaus@marketyou.com>
 */

class Filesystem extends \Twig_Loader_Filesystem
{

    protected function findTemplate($name)
    {
        // normalize name
        $name = preg_replace('#/{2,}#', '/', strtr($name, '\\', '/'));

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $this->validateName($name);

        try
        {
            $path = $this->path($name);

            return $this->cache[$name] = $path;
        }
        catch (\Exception $e) {}

        foreach ($this->paths as $path) {
            if (is_file($path.'/'.$name)) {
                return $this->cache[$name] = $path.'/'.$name;
            }
        }

        throw new \Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths)));
    }

    /**
     * Get the path to a view on disk.
     *
     * @param $view
     *
     * @return string
     * @throws \Exception
     */
    protected function path($view)
    {
        $root = \Bundle::path(\Bundle::name($view)).'views';

        $path = $root.DS.\Bundle::element($view);

        if (file_exists($path))
        {
            return $path;
        }

        throw new \Exception("View [$view] does not exist.");
    }

}