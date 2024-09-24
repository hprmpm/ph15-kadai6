<?php

require_once __DIR__ . '/functions/user.php';

// これを忘れない
session_start();

if (isset($_POST['confirm'])) {
    // ユーザー情報を保存する
    $user = [
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

    // CSVに保存する関数を呼び出す
    $user = saveUser($user);

    // ユーザーIDをセッションに保存し、マイページにリダイレクト
    $_SESSION['id'] = $user['id'];
    header('Location: ./my-page.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>確認ページ</title><html lang="ja">
</head>
<body>
    <div class="container">
        <h1 class="tableTitle">確認ページ</h1>
        <p>以下の内容で登録してもよろしいですか？</p>
        <table>
            <tr>
                <td class="item">ニックネーム</td>
                <td class="selectedItem">
                    <?php echo $_SESSION['name'] ?>
                </td>
            </tr>
            <tr>
                <td class="item">名前</td>
                <td class="selectedItem">
                    <?php echo $_SESSION['lastName'] .' '. $_SESSION['firstName'] ?>
                </td>
            </tr>
            <tr>
                <td class="item">名前(フリカナ)</td>
                <td class="selectedItem">
                    <?php echo $_SESSION['lastNameKt'] .' '. $_SESSION['firstNameKt'] ?>
                </td>
            </tr>
            <tr>
                <td class="item">メールアドレス</td>
                <td class="selectedItem">
                    <?php echo $_SESSION['email'] ?>
                </td>
            </tr>
            <tr>
                <td class="item">電話番号</td>
                <td class="selectedItem">
                    <?php echo $_SESSION['phone'] ?>
                </td>
            </tr>
            <tr>
                <td class="item">誕生日</td>
                <td class="selectedItem">
                    <?php echo $_SESSION['birthYear'] .'年'. $_SESSION['birthMonth'] .'月'. $_SESSION['birthDay'] .'日'?>
                </td>
            </tr>
            <tr>
                <td class="item">性別</td>
                <td class="selectedItem">
                    <?php
                    $gender = [
                        'male' => '男性',
                        'female' => '女性',
                        'other' => 'その他'
                    ];

                    echo $gender[$_SESSION['gender']] ?>
                </td>
            </tr>
            <tr>
                <td class="item">メールサービス</small></td>
                <td class="selectedItem">
                    <?php
                    $services = [
                        'newsletter' => 'ニュースレター',
                        'updates' => 'パーソナル',
                        'promotions' => 'キャンペーン'
                    ];

                    $selectedServices = array_map(function($service) use ($services) {
                        return $services[$service];
                    }, $_SESSION['services']);

                    echo implode(', ', $selectedServices);
                    ?>
                </td>
            </tr>
        </table>
        <form action="confirmation.php" method="POST">
            <input type="submit" name="confirm" value="確認" class="confirm">
        </form>
        <form action="register.php" method="GET">
            <input type="submit" value="戻る" class="back">
        </form>
    </div>

</body>
</html>
