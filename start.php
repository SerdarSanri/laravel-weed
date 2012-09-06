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
 * Autoload Classes
 */
Autoloader::namespaces(array(
    'Weed' => Bundle::path('weed').'classes',
));

/**
 * Add Twig classes to autoloader with underscore
 */
Autoloader::underscored(array(
    'Twig' => Bundle::path('weed').'vendor/Twig',
));