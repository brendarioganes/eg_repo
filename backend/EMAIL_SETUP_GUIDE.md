# EGUIDANCE Email Configuration Guide

## Real-time Email Delivery Setup

### Option 1: Gmail SMTP (Recommended)

1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate App Password**:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate password for "Mail"
3. **Update AuthController.php**:
   ```php
   $mail->Username = 'your-email@gmail.com'; // Your Gmail
   $mail->Password = 'your-16-char-app-password'; // App password
   ```

### Option 2: Other SMTP Providers

**Outlook/Hotmail:**
```php
$mail->Host = 'smtp-mail.outlook.com';
$mail->Port = 587;
$mail->Username = 'your-email@outlook.com';
$mail->Password = 'your-password';
```

**Yahoo:**
```php
$mail->Host = 'smtp.mail.yahoo.com';
$mail->Port = 587;
$mail->Username = 'your-email@yahoo.com';
$mail->Password = 'your-app-password';
```

### Option 3: Custom SMTP Server

```php
$mail->Host = 'your-smtp-server.com';
$mail->Port = 587; // or 465 for SSL
$mail->Username = 'your-username';
$mail->Password = 'your-password';
$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
```

## PHPMailer Installation

1. **Download PHPMailer**:
   ```bash
   # Download from: https://github.com/PHPMailer/PHPMailer/releases
   # Extract to: backend/scheme/libraries/PHPMailer/
   ```

2. **Required Files**:
   - PHPMailer.php
   - SMTP.php
   - Exception.php

3. **Alternative - Composer Installation**:
   ```bash
   composer require phpmailer/phpmailer
   ```

## Testing Email Configuration

Create a test file `test_email.php`:

```php
<?php
require_once 'scheme/libraries/PHPMailer/PHPMailer.php';
require_once 'scheme/libraries/PHPMailer/SMTP.php';
require_once 'scheme/libraries/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@gmail.com';
    $mail->Password = 'your-app-password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('noreply@eguidance.com', 'EGUIDANCE System');
    $mail->addAddress('test@example.com');
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = '<h1>Email Test Successful!</h1>';

    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email failed: {$mail->ErrorInfo}";
}
?>
```

## Troubleshooting

### Common Issues:

1. **Authentication Failed**:
   - Check username/password
   - Ensure 2FA is enabled for Gmail
   - Use App Password, not regular password

2. **Connection Timeout**:
   - Check firewall settings
   - Verify SMTP server and port
   - Try different ports (587, 465, 25)

3. **SSL/TLS Errors**:
   - Ensure OpenSSL extension is enabled
   - Try different encryption methods

4. **Email Not Delivered**:
   - Check spam folder
   - Verify recipient email address
   - Check SMTP server logs

## Production Considerations

1. **Rate Limiting**: Implement email rate limiting
2. **Queue System**: Use Redis/Database queue for high volume
3. **Monitoring**: Log email delivery status
4. **Fallback**: Implement multiple SMTP providers
5. **Security**: Use environment variables for credentials

## Environment Variables

Create `.env` file:
```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_NAME=EGUIDANCE System
MAIL_FROM_EMAIL=noreply@eguidance.com
```

Update AuthController.php to use environment variables:
```php
$mail->Username = $_ENV['MAIL_USERNAME'];
$mail->Password = $_ENV['MAIL_PASSWORD'];
```
