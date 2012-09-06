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

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your application/config folder, and make them in there.
 */


return array(
    /**
     * Twig_Loader_Filesystem
     */
    'paths' => array(
        Bundle::path('application').'views',
    ),

    /**
     * Twig_Environment configurations.
     */
    'cache'         => path('storage').'views',
    'debug'         => false,
    'autoreload'    => true,

    /**
     * Twig Extensions
     */
    'extensions' => array(
        'Twig\\Weed\Extension',
    ),
);