<?php
use PHPUnit\Framework\TestCase;

class Issue74Test extends TestCase
{
    public function testCreateAndThrowNewExceptionInProcessIsolation()
    {
        require_once dirname(__FILE__) . '/NewException.php';
        throw new NewException('Testing GH-74');
    }
}
