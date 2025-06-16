<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__.'/../TestCase.php';

class ApiControllerTest extends TestCase
{
 public $url = 'http://localhost/Notes-App/';
 public $randomString;
 public static $email;

 public function setUp(): void
 {
     // Initialize email only once
     if (self::$email === null) {
         $randomString = substr(md5(mt_rand()), 0, 10); // Generate a random 10-character string
         self::$email = "test{$randomString}@test.com";
     }
 }

 function testCreateUser()
{
    error_log('Testing email: ' . self::$email,);
    // Prepare POST data
    $postData = [
        'email' => self::$email,
        'name' => 'Test',
        'surname' => 'User',
        'password' => 'TestPassword123'
    ];

    // Initialize cURL
    $ch = curl_init($this->url."v1/signup/account/create");

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL
    curl_close($ch);

    // Assert HTTP status code
    $this->assertEquals(201, $httpCode, 'Expected HTTP status code 201 for successful user creation.');

    // Assert response content
    $responseData = json_decode($response, true);
}

function testAlreadyExist()
{
    error_log('Testing email: ' . self::$email);
    // Prepare POST data
    $postData = [
        'email' => self::$email,
        'name' => 'Test',
        'surname' => 'User',
        'password' => 'TestPassword123'
    ];

    // Initialize cURL
    $ch = curl_init($this->url."v1/signup/account/create");

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL
    curl_close($ch);

    // Assert HTTP status code
    $this->assertEquals(400, $httpCode, 'Expected HTTP status code 400-21-1 User Already Exists.');
}

function testLogin()
{
    // Prepare POST data
    $postData = [
        'email' => self::$email,
        'password' => 'TestPassword123'
    ];

    // Initialize cURL
    $ch = curl_init($this->url."v1/authentication");

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL
    curl_close($ch);

    // Assert HTTP status code
    $this->assertEquals(200, $httpCode, 'Expected HTTP status code 200 successful login.');
}
 
}