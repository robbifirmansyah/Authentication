<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
</head>
<body>
    <h1>Registration Successful</h1>
    <p>Dear {{ $name }},</p>
    <p>Your registration is successful. Below are your details:</p>
    <ul>
        <li>Name: {{ $name }}</li>
        <li>Email: {{ $email }}</li>
        <li>Registration Date: {{ $registration_date }}</li>
    </ul>
    <p>Thank you for registering with us!</p>
</body>
</html>
