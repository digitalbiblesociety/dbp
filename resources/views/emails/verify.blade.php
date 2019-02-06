<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<h1>So you Requested An API key</h1>

<a href="{{ route('api_key_generate', ['email_token' => $token]) }}">Click Me</a>

</body>
</html>