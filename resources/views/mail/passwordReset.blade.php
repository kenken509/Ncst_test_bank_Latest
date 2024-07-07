<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 20px;
        }

        .reset-password {
            background-color: #0d6efd;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .reset-password:hover {
            background-color: #569afe;
            
        }

        .contact-info {
            margin-top: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
        }

        .contact-info p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password Reset</h1>
        <p>Hello,</p>
        <p>This email is sent in response to your request to reset your password on NCST TEST BANK..</p>
        <p>To reset your password, please follow the link below:</p>
        <a href="{{$mailData['url']}}" target="_blank" class="reset-password">Reset Password</a>
        <p>Please ignore this email if you did not request a password change.</p>
        <p style="color:red;">Note: Reset link is valid within 60 mins.</p>
        <div class="contact-info">
            <p>Contact :</p>
            <p>Amafel Bldg., Aguinaldo Hi-way, Dasmariñas, Philippines</p>
            <p>+63 918 888 6278 | <a href="https://ncst.edu.ph/">ncst.edu.ph</a></p>
        </div>
        <p>&copy; National College of Science and Technology. All Rights Reserved</p>
    </div>
</body>
</html>