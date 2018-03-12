<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
<h2>Verify Your Email Address</h2>

<div>
    Thanks for creating an account for {{ env('APP_NAME') }}.
    Please follow the link below to verify your email address {{ URL::to('/verify-email/'.$email_token) }}.<br/>
</div>

</body>
</html>