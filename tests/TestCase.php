<?php
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected $CI;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Define required constants if not defined
        if (!defined('BASEPATH')) {
            define('BASEPATH', realpath(__DIR__.'/../system/').DIRECTORY_SEPARATOR);
        }
        if (!defined('APPPATH')) {
            define('APPPATH', realpath(__DIR__.'/../application/').DIRECTORY_SEPARATOR);
        }
        if (!defined('VIEWPATH')) {
            define('VIEWPATH', APPPATH.'views'.DIRECTORY_SEPARATOR);
        }
        if (!defined('FCPATH')) {
            define('FCPATH', realpath(__DIR__.'/../').DIRECTORY_SEPARATOR);
        }
        if (!defined('SELF')) {
            define('SELF', 'index.php');
        }
        if (!defined('ENVIRONMENT')) {
            define('ENVIRONMENT', 'testing');
        }
        
        // Reset superglobals
        $_SERVER = [
            'SERVER_NAME' => 'localhost',
            'SCRIPT_NAME' => '/index.php',
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/'
        ];
        $_GET = [];
        $_POST = [];
        $_REQUEST = [];
        
        // Initialize CI Loader and Output
        $this->CI = new stdClass();
        $this->CI->load = new CI_Loader();
        $this->CI->output = new CI_Output();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->CI = null;
    }
    
    protected function request($method, $uri, $params = [])
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        
        if ($method === 'GET') {
            $_GET = $params;
        } else {
            $_POST = $params;
        }
        
        $_REQUEST = $params;
    }

}