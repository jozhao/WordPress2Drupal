<?php

/**
 * @file Common functions.
 */

namespace Wordpress2Drupal;

define('DIRECTORY_DATA', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'data');

/**
 * Project version.
 * @return string
 */
function version()
{
    return '1.0';
}
