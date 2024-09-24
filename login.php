<?php

require_once __DIR__ . '/functions/user.php';

session_start();

$error_message = [];
$email = '';

if (isset($_POST['submit-button'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $isRememberMe = isset($_POST['remember-me']);
    $user = login($email, $password);

    if (!is_null($user)) {
        // セッションにIDを保存
        $_SESSION['id'] = $user['id'];

        // チェックボックスがチェックされていたらcookieにIDを保存
        if ($isRememberMe) {
            setcookie('id', $user['id'], time() + 60 * 60, '/');
        }

        header('Location: ./my-page.php');
        exit();

    } else {
        $error_message['result'] = 'ログインに失敗しました';
    }

    if (empty($email)) {
        $error_message['email'] = '正しいメールアドレスを入力してください';
    }

    if  (strlen($password) < 8) {
        $error_message['password'] = 'パスワードは8文字以上で入力してください';
    }

    $email_pattern = '/^[^@]+@[^@]+\.[^@]+$/';
    if (empty($email) || !preg_match($email_pattern, $email)) {
        $error_message['email'] = '正しいメールアドレスを入力してください';
    }
}

?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>ログイン</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container2">
        <h1 class="formTitle">ログイン</h1>
        <?php if (!empty($error_message['result'])): ?>
            <p class="error">
                <?php echo $error_message['result']; ?>
            </p>
        <?php endif; ?>

        <!-- action: フォームの送信先 -->
        <!-- method: 送信方法 -->
        <form action="./login.php" method="post">
            <div>
                メールアドレス<br>
                <input type="email" name="email" value="<?php echo $email; ?>">
            </div>

            <?php if (!empty($error_message['email'])): ?>
                <p class="error">
                    <?php echo $error_message['email']; ?>
                </p>
            <?php endif; ?>

            <div>
                パスワード<br>
                <input type="password" name="password">
            </div>

            <?php if (!empty($error_message['password'])): ?>
                <p class="error">
                    <?php echo $error_message['password']; ?>
                </p>
            <?php endif; ?>

            <div>
                <label>
                    <input type="checkbox" name="remember-me">
                    ログイン状態を保存する
                </label>
            </div>
            <div>
                <small><a href="./register.php">まだ会員でない方はこちら</a></small>
            <div>
                <input type="submit" name="submit-button" value="ログイン" class="button-style">
            </div>
        </form>
    </div>
</body>
</html>
