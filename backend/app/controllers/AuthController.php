<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Authentication Controller
 * Handles OTP-based login, register, logout
 */
class AuthController {
    private $userModel;
    private $otpModel;

    public function __construct() {
        $this->userModel = new User();
        $this->otpModel = new Otp();
    }

    /**
     * Show login page
     */
    public function showLogin() {
        // Check if user is already logged in
        session_start();
        if (isset($_SESSION['user_id'])) {
            $redirectUrl = $_SESSION['role'] === 'student' ? '/student-dashboard' : '/counselor-dashboard';
            header("Location: $redirectUrl");
            exit;
        }
        
        // Load login view
        include APP_DIR . 'views/login.php';
    }

    public function register() {
        try {
            // Get input data
        $input = json_decode(file_get_contents('php://input'), true);
            
            // Log the registration attempt
            error_log("Registration attempt: " . json_encode($input));
            
            // Validate input
            if (!$input) {
                error_log("Registration failed: Invalid JSON input");
                return $this->json(['success' => false, 'message' => 'Invalid JSON input'], 400);
            }
            
            if (empty($input['email']) || empty($input['password']) || empty($input['name']) || empty($input['role'])) {
                error_log("Registration failed: Missing required fields");
                return $this->json(['success' => false, 'message' => 'All fields are required'], 400);
            }
            
            // Validate email format
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                error_log("Registration failed: Invalid email format");
                return $this->json(['success' => false, 'message' => 'Invalid email format'], 400);
            }
            
            // Validate role
            if (!in_array($input['role'], ['student', 'counselor'])) {
                error_log("Registration failed: Invalid role");
                return $this->json(['success' => false, 'message' => 'Invalid role. Must be student or counselor'], 400);
            }
            
            // Check if email already exists
        if ($this->userModel->findByEmail($input['email'])) {
                error_log("Registration failed: Email already exists - " . $input['email']);
                return $this->json(['success' => false, 'message' => 'Email already exists'], 409);
            }
            
            // Create user
            $result = $this->userModel->create($input);
            
            if ($result) {
                error_log("Registration successful: " . $input['email']);
                return $this->json([
                    'success' => true, 
                    'message' => 'Registered successfully',
                    'user' => [
                        'name' => $input['name'],
                        'email' => $input['email'],
                        'role' => $input['role']
                    ]
                ]);
            } else {
                error_log("Registration failed: Database insert failed");
                return $this->json(['success' => false, 'message' => 'Failed to create user account'], 500);
            }
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            return $this->json(['success' => false, 'message' => 'Registration failed. Please try again.'], 500);
        }
    }

    /**
     * Send OTP to user's email for login
     */
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['email'])) {
            return $this->json(['success' => false, 'message' => 'Email is required'], 400);
        }

        $user = $this->userModel->findByEmail($input['email']);
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Generate OTP
        $otpCode = $this->otpModel->generateCode();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Store OTP
        if ($this->otpModel->create($user['id'], $otpCode, $expiresAt)) {
            // Send email
            if ($this->sendOtpEmail($user['email'], $otpCode)) {
                return $this->json([
                    'success' => true, 
                    'message' => 'OTP sent to your email',
                    'expires_in' => 300 // 5 minutes in seconds
                ]);
            } else {
                return $this->json(['success' => false, 'message' => 'Failed to send OTP email'], 500);
            }
        }

        return $this->json(['success' => false, 'message' => 'Failed to generate OTP'], 500);
    }

    /**
     * Verify OTP and login user
     */
    public function verifyOtp() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['email']) || empty($input['otp'])) {
            return $this->json(['success' => false, 'message' => 'Email and OTP are required'], 400);
        }

        $user = $this->userModel->findByEmail($input['email']);
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Verify OTP
        $otpRecord = $this->otpModel->verify($user['id'], $input['otp']);
        if (!$otpRecord) {
            return $this->json(['success' => false, 'message' => 'Invalid or expired OTP'], 401);
        }

        // Start session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        // Clean up used OTP
        $this->otpModel->cleanup($user['id']);

            return $this->json([
            'success' => true,
                'message' => 'Login successful',
            'token' => session_id(),
            'role' => $user['role'],
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
        }

    /**
     * Send OTP email using SMTP (Real-time delivery)
     */
    private function sendOtpEmail($email, $otpCode) {
        try {
            // Use PHPMailer for reliable SMTP delivery
            require_once 'scheme/libraries/PHPMailer/PHPMailer.php';
            require_once 'scheme/libraries/PHPMailer/SMTP.php';
            require_once 'scheme/libraries/PHPMailer/Exception.php';
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // SMTP Configuration for Gmail (Real-time delivery)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '01dropz@gmail.com'; // Replace with your Gmail
            $mail->Password = 'idirmojivemdauqp'; // Replace with your Gmail App Password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Email content
            $mail->setFrom('noreply@eguidance.com', 'EGUIDANCE System');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your Login OTP - EGUIDANCE';
            
            $htmlBody = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                    .otp-code { font-size: 32px; font-weight: bold; color: #667eea; text-align: center; background: white; padding: 20px; border-radius: 10px; margin: 20px 0; letter-spacing: 5px; }
                    .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
                    .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>EGUIDANCE</h1>
                        <p>Counseling & Student Wellness System</p>
                    </div>
                    <div class='content'>
                        <h2>Your Login OTP Code</h2>
                        <p>Hello,</p>
                        <p>You have requested to login to your EGUIDANCE account. Please use the following One-Time Password (OTP) to complete your login:</p>
                        
                        <div class='otp-code'>{$otpCode}</div>
                        
                        <div class='warning'>
                            <strong>⚠️ Important:</strong>
                            <ul>
                                <li>This code will expire in <strong>5 minutes</strong></li>
                                <li>Do not share this code with anyone</li>
                                <li>If you didn't request this OTP, please ignore this email</li>
                            </ul>
                        </div>
                        
                        <p>If you're having trouble logging in, please contact our support team.</p>
                        
                        <div class='footer'>
                            <p>Best regards,<br>EGUIDANCE Team</p>
                            <p>This is an automated message. Please do not reply to this email.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->Body = $htmlBody;
            
            // Send email
            $result = $mail->send();
            
            if ($result) {
                error_log("OTP email sent successfully to: $email");
                return true;
            } else {
                error_log("Failed to send OTP email to: $email");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            
            // Fallback to LavaLust Email if PHPMailer fails
            try {
                $emailLib = new Email();
                $emailLib->sender('noreply@eguidance.com', 'EGUIDANCE System');
                $emailLib->recipient($email);
                $emailLib->subject('Your Login OTP - EGUIDANCE');
                
                $message = "Your OTP code is: {$otpCode}\n\n";
                $message .= "This code will expire in 5 minutes.\n\n";
                $message .= "If you didn't request this OTP, please ignore this email.\n\n";
                $message .= "Best regards,\nEGUIDANCE Team";
                
                $emailLib->email_content($message, 'plain');
                
                return $emailLib->send();
            } catch (Exception $fallbackError) {
                error_log("Fallback email also failed: " . $fallbackError->getMessage());
                return false;
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        return $this->json(['success' => true, 'message' => 'Logged out successfully']);
    }

    /**
     * Check if user is authenticated
     */
    public function checkAuth() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return $this->json([
                'success' => true,
                'authenticated' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'name' => $_SESSION['name'],
                    'email' => $_SESSION['email'],
                    'role' => $_SESSION['role']
                ]
            ]);
        }
        return $this->json(['success' => true, 'authenticated' => false]);
    }

    private function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
