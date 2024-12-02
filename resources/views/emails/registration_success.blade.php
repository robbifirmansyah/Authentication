<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
</head>
<body>
    <h1>Hi, {{ $name }}</h1>
    <p>Thank you for registering!</p>
    <p>Here are your details:</p>
    <ul>
        <li>Email: {{ $email }}</li>
        <li>Registration Date: {{ $registration_date }}</li>
    </ul>
</body>
</html>
