<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Form Message</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #cd0511;">New Message from Contact Form</h2>
    <p><strong>Name:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Message:</strong></p>
    <div style="background-color: #f9f9f9; padding: 10px; border-left: 4px solid #cd0511;">
        {{ $messageContent }}
    </div>
</body>
</html>
