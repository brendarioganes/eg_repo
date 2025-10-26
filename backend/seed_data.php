<?php
define('PREVENT_DIRECT_ACCESS', TRUE);

// Include LavaLust bootstrap
require_once 'index.php';

/**
 * Sample Data Seeder
 * Run this script to populate the database with sample data
 */

echo "EGUIDANCE Sample Data Seeder\n";
echo "============================\n\n";

try {
    $userModel = new User();
    
    // Sample users data
    $sampleUsers = [
        [
            'name' => 'John Smith',
            'email' => 'john@student.edu',
            'password' => 'password123',
            'role' => 'student'
        ],
        [
            'name' => 'Jane Doe',
            'email' => 'jane@student.edu', 
            'password' => 'password123',
            'role' => 'student'
        ],
        [
            'name' => 'Dr. Sarah Johnson',
            'email' => 'sarah@counselor.edu',
            'password' => 'password123',
            'role' => 'counselor'
        ],
        [
            'name' => 'Dr. Michael Brown',
            'email' => 'michael@counselor.edu',
            'password' => 'password123',
            'role' => 'counselor'
        ]
    ];
    
    echo "Creating sample users...\n";
    
    foreach ($sampleUsers as $userData) {
        // Check if user already exists
        $existingUser = $userModel->findByEmail($userData['email']);
        
        if (!$existingUser) {
            $userModel->create($userData);
            echo "✓ Created user: {$userData['name']} ({$userData['email']})\n";
        } else {
            echo "- User already exists: {$userData['name']} ({$userData['email']})\n";
        }
    }
    
    echo "\n✓ Sample data seeding completed!\n";
    echo "\nSample login credentials:\n";
    echo "========================\n";
    echo "Students:\n";
    echo "- john@student.edu (password: password123)\n";
    echo "- jane@student.edu (password: password123)\n";
    echo "\nCounselors:\n";
    echo "- sarah@counselor.edu (password: password123)\n";
    echo "- michael@counselor.edu (password: password123)\n";
    
    echo "\nNote: The system uses OTP authentication, so you'll need to:\n";
    echo "1. Enter your email on the login page\n";
    echo "2. Check your email for the OTP code\n";
    echo "3. Enter the OTP to complete login\n";
    
} catch (Exception $e) {
    echo "✗ Error seeding data: " . $e->getMessage() . "\n";
    echo "Make sure the database is created and migrations are run first.\n";
}

echo "\nTo start the application, run:\n";
echo "php -S localhost:8000 -t public\n";
echo "\nThen visit: http://localhost:8000/login\n";
?>
