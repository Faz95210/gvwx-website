#!/usr/bin/env php
<?php
/*
 * This file is part of resource-operations.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$functions         = require dirname(__FILE__) . '/arginfo.php';
$resourceFunctions = [];

foreach ($functions as $function => $arguments) {
    foreach ($arguments as $argument) {
        if ($argument == 'resource') {
            $resourceFunctions[] = $function;
        }
    }
}

$resourceFunctions = array_unique($resourceFunctions);
sort($resourceFunctions);

$buffer = <<<EOT
<?php
/*
 * This file is part of resource-operations.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\ResourceOperations;

class ResourceOperations
{
    /**
     * @return string[]
     */
    public static function getFunctions()
    {
        return [

EOT;

foreach ($resourceFunctions as $function) {
    $buffer .= sprintf("            '%s',\n", $function);
}

$buffer .= <<< EOT
        ];
    }
}

EOT;

file_put_contents(dirname(__FILE__) . '/../src/ResourceOperations.php', $buffer);

