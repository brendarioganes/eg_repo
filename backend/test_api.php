<?php
/**
 * API Test Script for Registration
 * Tests the registration endpoint to ensure it works properly
 */

echo "🧪 EGUIDANCE API Registration Test\n";
echo "==================================\n\n";

// Test data
$testUsers = [
    [
        'name' => 'Test Student',
        'email' => 'teststudent@example.com',
        'password' => 'password123',
        'role' => 'student'
    ],
    [
        'name' => 'Test Counselor',
        'email' => 'testcounselor@example.com',
        'password' => 'password123',
        'role' => 'counselor'
    ]
];

$baseUrl = 'http://localhost:8000';

echo "Testing registration endpoint: $baseUrl/api/register\n\n";

foreach ($testUsers as $index => $user) {
    echo "Test " . ($index + 1) . ": Registering {$user['role']} - {$user['name']}\n";
    
    // Prepare the request
    $data = json_encode($user);
    
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/register');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ cURL Error: $error\n";
    } else {
        echo "   HTTP Code: $httpCode\n";
        
        $responseData = json_decode($response, true);
        if ($responseData) {
            if ($responseData['success']) {
                echo "   ✅ Success: {$responseData['message']}\n";
            } else {
                echo "   ❌ Error: {$responseData['message']}\n";
            }
        } else {
            echo "   ❌ Invalid JSON response: $response\n";
        }
    }
    
    echo "\n";
}

// Test login endpoint
echo "Testing login endpoint: $baseUrl/api/login\n\n";

$loginTest = [
    'email' => 'teststudent@example.com'
];

echo "Test: Login with OTP for teststudent@example.com\n";

$data = json_encode($loginTest);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ cURL Error: $error\n";
} else {
    echo "   HTTP Code: $httpCode\n";
    
    $responseData = json_decode($response, true);
    if ($responseData) {
        if ($responseData['success']) {
            echo "   ✅ Success: {$responseData['message']}\n";
            echo "   📧 OTP should be sent to email (check SMTP configuration)\n";
        } else {
            echo "   ❌ Error: {$responseData['message']}\n";
        }
    } else {
        echo "   ❌ Invalid JSON response: $response\n";
    }
}

echo "\n";
echo "🔧 If registration fails, check:\n";
echo "1. Backend server is running on port 8000\n";
echo "2. Database connection is working\n";
echo "3. Database tables exist\n";
echo "4. PHP error logs for detailed error messages\n";
echo "\n";
echo "📋 To check backend logs:\n";
echo "- Check PHP error log\n";
echo "- Check web server error log\n";
echo "- Enable error reporting in PHP\n";
echo "\n";
?>
