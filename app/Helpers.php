<?php
/**
 * Created by PhpStorm.
 * User: yao
 * Date: 9/4/18
 * Time: 2:39 PM
 */

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}