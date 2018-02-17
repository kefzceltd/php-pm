<?php

namespace PHPPM;

/**
 * Adds a file path to the watcher list of PPM's master process.
 *
 * If you have a custom template engine, cache engine or something dynamic
 * you probably want to call this function to let PPM know,
 * that you want to restart all workers when this file has changed.
 *
 * @param string $path
 */
function register_file($path)
{
    ProcessSlave::$slave->registerFile($path);
}

/**
 * Dumps information about a variable into your console output.
 *
 * @param mixed $expression The variable you want to export.
 * @param mixed $_          [optional]
 */
function console_log($expression, $_ = null)
{
    ob_start();
    var_dump(...func_get_args());
    file_put_contents('php://stderr', ob_get_clean() . PHP_EOL, FILE_APPEND);
}

/**
 * Checks that all required pcntl functions are available, so not fatal errors would be cause in runtime
 *
 * @return bool
 */
function pcntl_enabled()
{
    $requiredFunctions = ['pcntl_signal', 'pcntl_signal_dispatch', 'pcntl_waitpid'];
    $disabledFunctions = explode(',', (string) ini_get('disable_functions'));
    $disabledFunctions = array_map(function ($item) {
        return trim($item);
    }, $disabledFunctions);

    foreach ($requiredFunctions as $function) {
        if (in_array($function, $disabledFunctions)) {
            return false;
        }
    }

    return true;
}
