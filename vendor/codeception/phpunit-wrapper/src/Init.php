<?php

namespace Codeception\PHPUnit;

class Init
{
    /**
     * @api
     */
    public static function init()
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'shim.php';
    }
}