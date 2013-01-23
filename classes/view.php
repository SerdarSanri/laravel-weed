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
namespace Weed;

use Laravel;
use Laravel\Bundle;
use Laravel\Event;
use Laravel\Config;

class View extends Laravel\View
{
    /**
     * @var string
     */
    protected $bundle_root = '';

    /**
     * @var string
     */
    protected $template = '';

    /**
     * @var string
     */
    protected $template_ext = '.twig';

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
        $view = str_replace('.', '/', $view);
        $this->bundle_root = $root = Bundle::path(Bundle::name($view)).'views';

        $path = $root.DS.Bundle::element($view).$this->template_ext;

        if (file_exists($path))
        {
            $this->template = $view.$this->template_ext;

            return $path;
        }

        throw new \Exception("View [$view] does not exist.");
    }

    /**
     * Render the view.
     *
     * @return string
     * @throws \Exception
     */
    public function render()
    {
        // Events
        Event::fire("laravel.composing: {$this->view}", array($this));

        // Buffer the output
        ob_start();

        try
        {
            // array of paths where to find the views
            $paths = Config::get('weed.paths');

            // build the Twig object
            $loader = new Twig\Weed\Loader\Filesystem($paths);

            // define the Twig environment
            $config = array(
                'cache'       => Config::get('weed.cache'),
                'debug'       => Config::get('weed.debug'),
                'auto_reload' => Config::get('weed.auto_reload'),
            );

            $twig = new \Twig_Environment($loader, $config);

            // register the desired extensions
            foreach(Config::get('weed.extensions') as $extension)
            {
                $twig->addExtension(new $extension());
            }

            // output the rendered template :-)
            echo $twig->render($this->template, $this->data());
        }
        catch (\Exception $e)
        {
            ob_get_clean();

            throw $e;
        }

        return ob_get_clean();
    }
}
