<!-- resources/views/email.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{$subject}}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width-device-width ,initial-scale-1, shrink-to-fit">
</head>
<body>
<h1>New Email</h1>

<p>Subject: {{$subject}}</p>

<p> {{$message}}</p>
</body>
</html>
