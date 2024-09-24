<?php

require_once __DIR__ . '/functions/user.php';

// これを忘れない
session_start();

// セッションにIDが保存されていなければ
// ログインページに移動
if (!isset($_SESSION['id']) && !isset($_COOKIE['id'])) {
    header('Location: ./login.php');
    exit();
}

// セッションにIDが保存されていればセッション
// ない場合はCOOKIEからIDを取得
$id = $_SESSION['id'] ?? $_COOKIE['id'];

$user = getUser($id);

// ユーザーが見つからなかったらログインページへ
if (is_null($user)) {
    header('Location: ./login.php');
    exit();
}

?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>マイページ</title>
</head>
<body>
    <div class="container">
        <h1 class="tableTitle">マイページ</h1>
        <table>
            <tr>
                <td>ID</td>
                <td>
                    <?php echo $user['id'] ?>
                </td>
            </tr>
            <tr>
                <td>ニックネーム</td>
                <td>
                    <?php echo $user['name'] ?>
                </td>
            </tr>
            <tr>
                <td>名前</td>
                <td>
                    <?php echo $user['lastName'] .' '. $user['firstName'] ?>
                </td>
            </tr>
            <tr>
                <td>メールアドレス</td>
                <td>
                    <?php echo $user['email'] ?>
                </td>
            </tr>
            <tr>
                <td>電話番号</td>
                <td>
                    <?php echo $user['phone'] ?>
                </td>
            </tr>
            <tr>
                <td>誕生日</td>
                <td>
                    <?php echo $user['birthYear'] .'年'. $user['birthMonth'] .'月'. $user['birthDay'] .'日'?>
                </td>
            </tr>
            <tr>
                <td>性別</td>
                <td>
                    <?php
                    $gender = [
                        'male' => '男性',
                        'female' => '女性',
                        'other' => 'その他'
                    ];

                    echo $gender[$user['gender']] ?>
                </td>
            </tr>
        </table>
        <div>
            <a href="./edit.php">
                情報変更
            </a>
        </div>
        <div>
            <a href="./logout.php" class="button-style2">ログアウト</a>
        </div>
    </div>
</body>
</html>
