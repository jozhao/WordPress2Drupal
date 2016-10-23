<?php

/**
 * @file Common functions.
 */

namespace WordPress2Drupal;

define('DIRECTORY_DATA', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'data');

/**
 * Project version.
 * @return string
 */
function version()
{
    return '1.0';
}

/**
 * Retrieve the shortcode attributes regex.
 *
 * @since 4.4.0
 *
 * @return string The shortcode attribute regular expression
 */
function get_shortcode_atts_regex()
{
    return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
}

/**
 * Retrieve all attributes from the shortcodes tag.
 *
 * The attributes list has the attribute name as the key and the value of the
 * attribute as the value in the key/value pair. This allows for easier
 * retrieval of the attributes, since all attributes have to be known.
 *
 * @since 2.5.0
 *
 * @param string $text
 * @return array|string List of attribute values.
 *                      Returns empty array if trim( $text ) == '""'.
 *                      Returns empty string if trim( $text ) == ''.
 *                      All other matches are checked for not empty().
 */
function shortcode_parse_atts($text)
{
    $atts = array();
    $pattern = get_shortcode_atts_regex();
    $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
    if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
        foreach ($match as $m) {
            if (!empty($m[1])) {
                $atts[strtolower($m[1])] = stripcslashes($m[2]);
            } elseif (!empty($m[3])) {
                $atts[strtolower($m[3])] = stripcslashes($m[4]);
            } elseif (!empty($m[5])) {
                $atts[strtolower($m[5])] = stripcslashes($m[6]);
            } elseif (isset($m[7]) && strlen($m[7])) {
                $atts[] = stripcslashes($m[7]);
            } elseif (isset($m[8])) {
                $atts[] = stripcslashes($m[8]);
            }
        }

        // Reject any unclosed HTML elements
        foreach ($atts as &$value) {
            if (false !== strpos($value, '<')) {
                if (1 !== preg_match('/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value)) {
                    $value = '';
                }
            }
        }
    } else {
        $atts = ltrim($text);
    }

    return $atts;
}

/**
 * Remove all shortcode tags from the given content.
 *
 * @since 2.5.0
 *
 * @global array $shortcode_tags
 *
 * @param string $content Content to remove shortcode tags.
 * @return string Content without shortcode tags.
 */
function strip_shortcodes($content)
{
    global $shortcode_tags;

    if (false === strpos($content, '[')) {
        return $content;
    }

    if (empty($shortcode_tags) || !is_array($shortcode_tags)) {
        return $content;
    }

    // Find all registered tag names in $content.
    preg_match_all('@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches);
    $tagnames = array_intersect(array_keys($shortcode_tags), $matches[1]);

    if (empty($tagnames)) {
        return $content;
    }

    $content = do_shortcodes_in_html_tags($content, true, $tagnames);

    $pattern = get_shortcode_regex($tagnames);
    $content = preg_replace_callback("/$pattern/", 'strip_shortcode_tag', $content);

    // Always restore square braces so we don't break things like <!--[if IE ]>
    $content = unescape_invalid_shortcodes($content);

    return $content;
}

/**
 * Strips a shortcode tag based on RegEx matches against post content.
 *
 * @since 3.3.0
 *
 * @param array $m RegEx matches against post content.
 * @return string|false The content stripped of the tag, otherwise false.
 */
function strip_shortcode_tag($m)
{
    // allow [[foo]] syntax for escaping a tag
    if ($m[1] == '[' && $m[6] == ']') {
        return substr($m[0], 1, -1);
    }

    return $m[1].$m[6];
}

