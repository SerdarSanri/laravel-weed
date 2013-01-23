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
namespace Weed\Twig\Weed;

use Laravel\Config;
use Laravel\HTML;
use Laravel\Input;
use Laravel\Lang;
use Laravel\URL;
use Twig_Extension;
use Twig_Function_Function;
use Twig_Function_Method;
use \Weed\Twig\Weed\TokenParser\ForwardTokenParser;

/**
 * Provides Twig support for commonly used Laravel classes and methods.
 */
class Extension extends Twig_Extension
{

    /**
     * Gets the name of the extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'weed';
    }

    /**
     * Sets up all of the function this extension makes available.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'config'            => new Twig_Function_Function('Config::get', array('is_safe' => array('html'))),
            'input_get'         => new Twig_Function_Function('Input::get', array('is_safe' => array('html'))),
            'url_to'            => new Twig_Function_Function('URL::to', array('is_safe' => array('html'))),
            'url_to_secure'     => new Twig_Function_Function('URL::to_secure', array('is_safe' => array('html'))),
            'email'             => new Twig_Function_Function('HTML::email', array('is_safe' => array('html'))),
            'request_env'       => new Twig_Function_Function('Request::env', array('is_safe' => array('html'))),

            'asset'             => new Twig_Function_Method($this, 'asset', array('is_safe' => array('html'))),
            't'                 => new Twig_Function_Method($this, 't', array('is_safe' => array('html'))),
            'script'            => new Twig_Function_Method($this, 'script', array('is_safe' => array('html'))),
            'style'             => new Twig_Function_Method($this, 'style', array('is_safe' => array('html'))),
            'link'              => new Twig_Function_Method($this, 'link', array('is_safe' => array('html'))),
            'link_secure'       => new Twig_Function_Method($this, 'link_secure', array('is_safe' => array('html'))),
            'image'             => new Twig_Function_Method($this, 'image', array('is_safe' => array('html'))),
        );
    }

	public function getTokenParsers() {
		return array(new ForwardTokenParser());
	}

    public function asset($url = false)
    {
        if($url)
        {
            return asset($url);
        }

        return null;
    }

    /**
     * Get a language specific line
     *
     * @param string $key   The Key to search
     * @param array  $subst array The values to substitute
     * @param string $lang  string The language
     *
     * @return string
     */
    public function t($key, $subst = null, $lang = null)
    {
        if(is_null($lang))
        {
            $lang = Config::get('application.language', 'en_US');
        }

        if (is_null($subst))
        {
            return Lang::line($key, array())->get($lang);
        }
        else
        {
            parse_str($subst, $repl);

            return Lang::line($key, $repl)->get($lang);
        }
    }

    /**
     * Generate a script tag
     *
     * @param string $file    The file relative to your public directory
     * @param string $params  Additional HTML attributes
     *
     * @return string
     */
    public function script($file, $params = '')
    {
        $attrs = array();

        if (strlen(trim($params)) != 0)
        {
            parse_str($params, $attrs);
        }

        if (strtolower(substr(trim($file), 0, 4)) == 'http')
        {
            $url = HTML::entities($file);
        }
        else
        {
            $url = HTML::entities(asset($file));
        }

        return '<script type="text/javascript" src="'.$url.'"'.HTML::attributes($attrs).'></script>';
    }

    /**
     * Generate a style tag
     *
     * @param string $file   The file relative to your public directory
     * @param string $params Additional HTML attributes
     *
     * @return string
     */
    public function style($file, $params = '')
    {
        $attrs = array();

        if (strlen(trim($params)) != 0)
        {
            parse_str($params, $attrs);
        }

        $defaults = array(
            'media' => 'all',
            'type'  => 'text/css',
            'rel'   => 'stylesheet'
        );

        foreach ($defaults as $attribute => $default)
        {
            if (! array_key_exists($attribute, $attrs))
            {
                $attrs[$attribute] = $default;
            }
        }

        if (strtolower(substr($file, 0, 4)) == 'http')
        {
            $url = HTML::entities($file);
        }
        else
        {
            $url = HTML::entities(asset($file));
        }

        return '<link href="'.$url.'"'.HTML::attributes($attrs).'>';
    }

    /**
     * Generate a hyperlink
     *
     * @param string $dest The file relative to your public directory
     * @param string $title The HTML title attribute
     * @param string $params Additional HTML attributes
     *
     * @return string
     */
    public function link($dest, $title, $params = '')
    {
        $attrs = array();

        if (strlen(trim($params)) != 0)
        {
            parse_str($params, $attrs);
        }

        return HTML::link($dest, $title, $attrs, false);
    }

    /**
     * Generate a secure hyperlink
     *
     * @param string $dest The file relative to your public directory
     * @param string $title The HTML title attribute
     * @param string $params Additional HTML attributes
     *
     * @return string
     */
    public function link_secure($dest, $title, $params = '')
    {
        $attrs = array();

        if (strlen(trim($params)) != 0)
        {
            parse_str($params, $attrs);
        }

        return HTML::link($dest, $title, $attrs, true);
    }

    /**
     * Generate an image tag
     *
     * @param string $file The file relative to your public directory
     * @param string $alt The HTML alt attribute
     * @param string $params Additional HTML attributes
     *
     * @return string
     */
    public function image($file, $alt = '', $params = '')
    {
        $attrs = array();

        if (strlen(trim($params)) != 0)
        {
            parse_str($params, $attrs);
        }

        $attrs['alt'] = $alt;

        if (strtolower(substr($file, 0, 4)) == 'http')
        {
            $url = $file;
        }
        else
        {
            $url = asset($file);
        }

        return '<img src="'.HTML::entities($url).'"'.HTML::attributes($attrs).'>';
    }

}