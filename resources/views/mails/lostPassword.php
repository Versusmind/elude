<html>
<body>
Hello <?php echo $username; ?> <br/>

<a href="<?php echo route('auth.changeLostPasswordForm', [
    'username' => base64_encode($username),
    'token' => $token
]) ?>">Lost password</a>
</body>
</html>