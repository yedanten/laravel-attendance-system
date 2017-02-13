<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
@if (session('verify'))
您的验证码为:<b>{{ session('verify') }}</b>
@endif
</body>
</html>