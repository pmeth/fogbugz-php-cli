<?php

/**
 * An (almost) direct port of http://pypi.python.org/pypi/termcolor to PHP.
 * 
 * @author Rune Kaagaard <rumi.kg@gmail.com>
 * @license None.
 */

/**
 * Returns text formatted with color, background color and/or attributes.
 * 
 * @global array $TC_ALL
 * @global string $TC_RESET
 * @param string $text
 *    The text to apply color to ($arg1).
 * @param string/array $arg2 ... $argN
 *     Any of the following text colors, background colors or style attributes
 *     in arbitrary order.
 *         Available text colors:
 *             red, green, yellow, blue, magenta, cyan, white.
 *         Available text highlights:
 *             on_red, on_green, on_yellow, on_blue, on_magenta, on_cyan, 
               on_white.
 *         Available attributes:
 *             bold, dark, underline, blink, reverse, concealed.
 * @example
 *     tc_colored('Hello, World!', 'red', 'on_grey', 'blue', 'blink');
 *     tc_colored('Hello, World!', 'green');
 * @return string 
 */
function tc_colored() {
    static $fmt_str = "\033[%dm%s";
    static $reset = "\033[0m";
    static $options = false; 
    if (!$options) { 
        $options = _tc_get_options();
    }
    
    $args = func_get_args();
    $text = array_shift($args);
    
    foreach ($args as $_arg) {
        foreach ((array)$_arg as $arg) {
            if (isset($options[$arg])) {
                $text = sprintf($fmt_str, $options[$arg], $text);
            } else {
                tcechon("Invalid argument to termcolor.php: $arg.");
                exit(1);
            }
        }
    }
    
    return $text . $reset;
}

/**
 * Echos text formatted with color, background color and/or attributes. Adds
 * a new line at the end.
 * 
 * @param string $text
 *    The text to apply color to ($arg1).
 * @param string/array $arg2 ... $argN
 *     Any of the following text colors, background colors or style attributes
 *     in arbitrary order.
 *         Available text colors:
 *             red, green, yellow, blue, magenta, cyan, white.
 *         Available text highlights:
 *             on_red, on_green, on_yellow, on_blue, on_magenta, on_cyan, 
               on_white.
 *         Available attributes:
 *             bold, dark, underline, blink, reverse, concealed.
 * @example
 *     tcechon('Hello, World!', 'red', 'on_grey', 'blue', 'blink');
 *     tcechon('Hello, World!', 'green');
 */
function tcechon() {
    $args = func_get_args();
    echo call_user_func_array('tc_colored', $args). "\n";
}

/**
 * Echos text formatted with color, background color and/or attributes.
 * 
 * @param string $text
 *    The text to apply color to ($arg1).
 * @param string/array $arg2 ... $argN
 *     Any of the following text colors, background colors or style attributes
 *     in arbitrary order.
 *         Available text colors:
 *             red, green, yellow, blue, magenta, cyan, white.
 *         Available text highlights:
 *             on_red, on_green, on_yellow, on_blue, on_magenta, on_cyan, 
               on_white.
 *         Available attributes:
 *             bold, dark, underline, blink, reverse, concealed.
 * @example
 *     tcecho('Hello, World!', 'red', 'on_grey', 'blue', 'blink');
 *     tcecho('Hello, World!', 'green');
 */
function tcecho() {
    $args = func_get_args();
    echo call_user_func_array('tc_colored', $args);
}

/**
 * Helper function that builds an array of all the available text colors,
 * background colors and text attributes and their corresponding terminal codes.
 *
 * @return array
 */
function _tc_get_options() {
    $options = array_merge(
        // Foreground colors.
        array_combine(
            array('grey', 'red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 
                  'white'),
            range(30, 37)
        ),
        // Background colors.
        array_combine(
            array('on_grey', 'on_red', 'on_green', 'on_yellow', 'on_blue', 
                  'on_magenta', 'on_cyan', 'on_white'),
            range(40, 47)
                
        ),
        // Text style attributes. 3 and 6 is not used.
        array_combine(
            array('bold', 'dark', '', 'underline', 'blink', '', 'reverse', 
                  'concealed'),
            range(1, 8)
        )
    );
    unset($options['']);
    return $options;
}
