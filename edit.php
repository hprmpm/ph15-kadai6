<?php

// 他のPHPファイルを読み込む
require_once __DIR__ . '/functions/user.php';

// これをわすれない
session_start();

$error_message = [];

if (!isset($_SESSION['id']) && !isset($_COOKIE['id'])) {
    header('Location: ./login.php');
    exit();
}

$id = $_SESSION['id'] ?? $_COOKIE['id'];

$user = getUser($id);

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
    $_SESSION['services'] = $_POST['services'];

    $user = [
        'id' => $_SESSION['id'],
        'email' => $_SESSION['email'],
        'name' => $_SESSION['name'],
        'password' => $_SESSION['password'],
        'lastName' => $_SESSION['lastName'],
        'firstName' => $_SESSION['firstName'],
        'lastNameKt' => $_SESSION['lastNameKt'],
        'firstNameKt' => $_SESSION['firstNameKt'],
        'phone' => $_SESSION['phone'],
        'birthYear' => $_SESSION['birthYear'],
        'birthMonth' => $_SESSION['birthMonth'],
        'birthDay' => $_SESSION['birthDay'],
        'gender' => $_SESSION['gender'],
        'services' => implode(',', $_SESSION['services']),
    ];

    editUser($user);

    header('Location: ./my-page.php');
    exit();
} else {
    $error_message['result'] = '入力した情報を修正してください';
}

?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>情報変更</title>
</head>
<body>
    <div class="form-container">
        <h1 class="formTitle">情報変更</h1>
        <h3>以下の項目をご入力ください。</h3>

        <?php if (!empty($error_message['result'])): ?>
            <p class="error">
                <?php echo $error_message['result']; ?>
            </p>
        <?php endif; ?>

        <!-- action: フォームの送信先 -->
        <!-- method: 送信方法 -->
        <form action="./edit.php" method="POST">
            <div class="form-group">
                <label for="email">メールアドレス <span class="required">変更不能</span></label>
                <input type="email" id="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" readonly>
                <?php if (isset($error_message['email'])): ?>
                    <p class="error"><?php echo $error_message['email']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="nickname">ニックネーム<span class="required">必須</span></label>
                <input type="text" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" required>
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
                <label for="lastName">姓<span class="required">変更不能</span></label>
                <input type="text" id="lastName" name="lastName" value="<?php echo isset($user['lastName']) ? $user['lastName'] : ''; ?>" readonly>
                <?php if (isset($error_message['lastName'])): ?>
                    <p class="error"><?php echo $error_message['lastName']; ?></p>
                <?php endif; ?><br>
                <label for="firstName">名<span class="required">変更不能</span></label>
                <input type="text" id="firstName" name="firstName" value="<?php echo isset($user['firstName']) ? $user['firstName'] : ''; ?>" readonly>
                <?php if (isset($error_message['firstName'])): ?>
                    <p class="error"><?php echo $error_message['firstName']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <h3>お名前（フリガナ）</h3>
                <label for="lastNameKt">セイ<span class="required">変更不能</span></label>
                <input type="text" name="lastNameKt" value="<?php echo isset($user['lastNameKt']) ? $user['lastNameKt'] : ''; ?>" readonly>
                <?php if (isset($error_message['lastNameKt'])): ?>
                    <p class="error"><?php echo $error_message['lastNameKt']; ?></p>
                <?php endif; ?><br>
                <label for="firstNameKt">メイ<span class="required">変更不能</span></label>
                <input type="text" name="firstNameKt" value="<?php echo isset($user['firstNameKt']) ? $user['firstNameKt'] : ''; ?>" readonly>
                <?php if (isset($error_message['firstNameKt'])): ?>
                    <p class="error"><?php echo $error_message['firstNameKt']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="phone">電話番号<span class="required">必須</span></label>
                <input type="text" id="phone1" name="phone1" value="<?php echo isset($user['phone']) ? explode('-', $user['phone'])[0] : ''; ?>" required> -
                <input type="text" id="phone2" name="phone2" value="<?php echo isset($user['phone']) ? explode('-', $user['phone'])[1] : ''; ?>" required> -
                <input type="text" id="phone3" name="phone3" value="<?php echo isset($user['phone']) ? explode('-', $user['phone'])[2] : ''; ?>" required>
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
                <select id="birthYear" name="birthYear" required>
                    <?php
                    $currentYear = date('Y');
                    for ($year = $currentYear; $year >= $currentYear - 100; $year--) {
                        $selected = isset($user['birthYear']) && $user['birthYear'] == $year ? 'selected' : '';
                        echo "<option value='$year' $selected>$year</option>";
                    }
                    ?>
                </select>
                <select id="birthMonth" name="birthMonth" required>
                    <?php
                    for ($month = 1; $month <= 12; $month++) {
                        $selected = isset($user['birthMonth']) && $user['birthMonth'] == $month ? 'selected' : '';
                        echo "<option value='$month' $selected>$month</option>";
                    }
                    ?>
                </select>
                <select id="birthDay" name="birthDay" required>
                    <?php
                    for ($day = 1; $day <= 31; $day++) {
                        $selected = isset($user['birthDay']) && $user['birthDay'] == $day ? 'selected' : '';
                        echo "<option value='$day' $selected>$day</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>性別<span class="required">変更不能</span></label>
                <label><input type="radio" name="gender" value="male" <?php echo isset($user['gender']) && $user['gender'] == 'male' ? 'checked' : ''; ?> disabled> 男性</label>
                <label><input type="radio" name="gender" value="female" <?php echo isset($user['gender']) && $user['gender'] == 'female' ? 'checked' : ''; ?> disabled> 女性</label>
                <label><input type="radio" name="gender" value="other" <?php echo isset($user['gender']) && $user['gender'] == 'other' ? 'checked' : ''; ?> disabled> その他</label>
                <input type="hidden" name="gender" value="<?php echo isset($user['gender']) ? $user['gender'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="interests">メールサービス</label>
                <?php
                    $services = isset($user['services']) ? explode(',', $user['services']) : [];
                ?>
                <label><input type="checkbox" name="services[]" value="newsletter" <?php echo in_array('newsletter', $services) ? 'checked' : ''; ?>> ニュースレター</label>
                <label><input type="checkbox" name="services[]" value="updates" <?php echo in_array('updates', $services) ? 'checked' : ''; ?>> パーソナル</label>
                <label><input type="checkbox" name="services[]" value="promotions" <?php echo in_array('promotions', $services) ? 'checked' : ''; ?>> キャンペーン</label>
            </div>
            <div class="form-group">
                <input type="submit" name="submit-button" value="登録" class="button-style">
            </div>
        </form>
    </div>
</body>
</html>
