<?php

// 他のPHPファイルを読み込む
require_once __DIR__ . '/functions/user.php';

// これをわすれない
session_start();

$error_message = [];

if (isset($_POST['submit-button'])) {
    $password = $_POST['password'];
    $lastNameKt = $_POST['lastNameKt'];
    $firstNameKt = $_POST['firstNameKt'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $phone1 = $_POST['phone1'];
    $phone2 = $_POST['phone2'];
    $phone3 = $_POST['phone3'];
    $email = $_POST['email'];
    $phone = $_POST['phone1'] . '-' . $_POST['phone2'] . '-' . $_POST['phone3'];
    $name = $_POST['name'];

    // 重複チェック
    if (isUserExist($email, $phone, $name)) {
        $error_message['duplicate'] = '入力した情報は既に登録されました';
    }

    // パスワード長さチェック
    if (strlen($password) < 8) {
        $error_message['password'] = 'パスワードは8文字以上で入力してください';
    }

    // 片仮名
    $katakana_pattern = '/^[ァ-ヶー]+$/u';

    // 入力チェック
    if (!preg_match($katakana_pattern, $lastNameKt)) {
        $error_message['lastNameKt'] = '片仮名で入力してください';
    }
    if (!preg_match($katakana_pattern, $firstNameKt)) {
        $error_message['firstNameKt'] = '片仮名で入力してください';
    }

    $email_pattern = '/^[^@]+@[^@]+\.[^@]+$/';
    if (!preg_match($email_pattern, $email)) {
        $error_message['email'] = '正しいメールアドレスを入力してください（例: xxx@xxxx.xxx）';
    }

    if (!preg_match('/^\d{3}$/', $phone1)) {
        $error_message['phone1'] = '電話番号の最初の部分は3桁の数字で入力してください';
    }
    if (!preg_match('/^\d{4}$/', $phone2)) {
        $error_message['phone2'] = '電話番号の中央の部分は4桁の数字で入力してください';
    }
    if (!preg_match('/^\d{4}$/', $phone3)) {
        $error_message['phone3'] = '電話番号の最後の部分は4桁の数字で入力してください';
    }

    if (preg_match('/\d/', $lastName)) {
        $error_message['lastName'] = '数字を含めることはできません';
    }
    if (preg_match('/\d/', $firstName)) {
        $error_message['firstName'] = '数字を含めることはできません';
    }
}

// エラーがなければセッションに保存
if (empty($error_message) && isset($_POST['submit-button'])) {
    // フォームデータをセッションに保存
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['lastName'] = $_POST['lastName'];
    $_SESSION['firstName'] = $_POST['firstName'];
    $_SESSION['lastNameKt'] = $_POST['lastNameKt'];
    $_SESSION['firstNameKt'] = $_POST['firstNameKt'];
    $_SESSION['phone'] = $_POST['phone1'] . '-' . $_POST['phone2'] . '-' . $_POST['phone3'];
    $_SESSION['birthYear'] = $_POST['birthYear'];
    $_SESSION['birthMonth'] = $_POST['birthMonth'];
    $_SESSION['birthDay'] = $_POST['birthDay'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['services'] = isset($_POST['services']) ? $_POST['services'] : [];

    // 確認ページにリダイレクト
    header('Location: ./confirmation.php');
    exit();
} else {
    $error_message['result'] = '入力した情報を修正してください';
}

?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>会員登録</title>
</head>
<body>
    <div class="form-container">
        <h1 class="formTitle">会員情報 入力</h1>
        <h3>以下の項目をご入力ください。<br>ログインの際にはメールアドレスとパスワードが必要となりますので、お手元に控えをご用意ください。<br>
        <small class="red">※入力した情報は一度登録されますと、変更ができません</small><br>
        <small><a href="./login.php">すでに登録済みの方はこちら</a></small></h3>

        <?php if (!empty($error_message['result'])): ?>
            <p class="error">
                <?php echo $error_message['result']; ?>
            </p>
        <?php endif; ?>
        <?php if (!empty($error_message['duplicate'])): ?>
            <p class="error"><?php echo $error_message['duplicate']; ?></p>
        <?php endif; ?>
        <!-- action: フォームの送信先 -->
        <!-- method: 送信方法 -->
        <form action="./register.php" method="POST">
            <div class="form-group">
                <label for="email">メールアドレス <span class="required">必須</span></label>
                <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
                <?php if (isset($error_message['email'])): ?>
                    <p class="error"><?php echo $error_message['email']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="nickname">ニックネーム<span class="required">必須</span></label>
                <input type="text" name="name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード<span class="required">必須</span></label>
                <input type="password" name="password" required>
                <?php if (isset($error_message['password'])): ?>
                    <p class="error"><?php echo $error_message['password']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <h3>お名前</h3>
                <label for="lastName">姓<span class="required">必須</span></label>
                <input type="text" id="lastName" name="lastName" value="<?php echo isset($_SESSION['lastName']) ? $_SESSION['lastName'] : ''; ?>" required>
                <?php if (isset($error_message['lastName'])): ?>
                    <p class="error"><?php echo $error_message['lastName']; ?></p>
                <?php endif; ?><br>
                <label for="firstName">名<span class="required">必須</span></label>
                <input type="text" id="firstName" name="firstName" value="<?php echo isset($_SESSION['firstName']) ? $_SESSION['firstName'] : ''; ?>" required>
                <?php if (isset($error_message['firstName'])): ?>
                    <p class="error"><?php echo $error_message['firstName']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <h3>お名前（フリガナ）</h3>
                <label for="lastNameKt">セイ<span class="required">必須</span></label>
                <input type="text" name="lastNameKt" value="<?php echo isset($_SESSION['lastNameKt']) ? $_SESSION['lastNameKt'] : ''; ?>" required>
                <?php if (isset($error_message['lastNameKt'])): ?>
                    <p class="error"><?php echo $error_message['lastNameKt']; ?></p>
                <?php endif; ?><br>
                <label for="firstNameKt">メイ<span class="required">必須</span></label>
                <input type="text" name="firstNameKt" value="<?php echo isset($_SESSION['firstNameKt']) ? $_SESSION['firstNameKt'] : ''; ?>" required>
                <?php if (isset($error_message['firstNameKt'])): ?>
                    <p class="error"><?php echo $error_message['firstNameKt']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="phone">電話番号<span class="required">必須</span></label>
                <input type="text" id="phone1" name="phone1" value="<?php echo isset($_SESSION['phone']) ? explode('-', $_SESSION['phone'])[0] : ''; ?>" required> -
                <input type="text" id="phone2" name="phone2" value="<?php echo isset($_SESSION['phone']) ? explode('-', $_SESSION['phone'])[1] : ''; ?>" required> -
                <input type="text" id="phone3" name="phone3" value="<?php echo isset($_SESSION['phone']) ? explode('-', $_SESSION['phone'])[2] : ''; ?>" required>
                <?php if (isset($error_message['phone1'])): ?>
                    <p class="error"><?php echo $error_message['phone1']; ?></p>
                <?php endif; ?>
                <?php if (isset($error_message['phone2'])): ?>
                    <p class="error"><?php echo $error_message['phone2']; ?></p>
                <?php endif; ?>
                <?php if (isset($error_message['phone3'])): ?>
                    <p class="error"><?php echo $error_message['phone3']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="birthday">生年月日<span class="required">必須</span></label>
                <select id="birthYear" name="birthYear" required>>
                    <?php
                    $currentYear = date('Y');
                    for ($year = $currentYear; $year >= $currentYear - 100; $year--) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
                <select id="birthMonth" name="birthMonth" required>>
                    <?php
                    for ($month = 1; $month <= 12; $month++) {
                        echo "<option value='$month'>$month</option>";
                    }
                    ?>
                </select>
                <select id="birthDay" name="birthDay" required>>
                    <?php
                    for ($day = 1; $day <= 31; $day++) {
                        echo "<option value='$day'>$day</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>性別<span class="required">必須</span></label>
                <label><input type="radio" name="gender" value="male" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'male' ? 'checked' : ''; ?>> 男性</label>
                <label><input type="radio" name="gender" value="female" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'female' ? 'checked' : ''; ?>> 女性</label>
                <label><input type="radio" name="gender" value="other" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'other' ? 'checked' : ''; ?>> その他</label>
            </div>
            <div class="form-group">
                <label for="interests">メールサービス</label>
                <label><input type="checkbox" name="services[]" value="newsletter" <?php echo isset($_SESSION['services']) && in_array('newsletter', $_SESSION['services']) ? 'checked' : ''; ?>> ニュースレター</label>
                <label><input type="checkbox" name="services[]" value="updates" <?php echo isset($_SESSION['services']) && in_array('updates', $_SESSION['services']) ? 'checked' : ''; ?>> パーソナル</label>
                <label><input type="checkbox" name="services[]" value="promotions" <?php echo isset($_SESSION['services']) && in_array('promotions', $_SESSION['services']) ? 'checked' : ''; ?>> キャンペーン</label>
            </div>
            <div class="form-group">
                <input type="submit" name="submit-button" value="登録" class="button-style">
            </div>
        </form>
    </div>
</body>
</html>
