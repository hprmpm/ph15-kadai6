<?php

function saveUser(array $user): array
{
    $handle = fopen(__DIR__ . './../data/users.csv', 'a');

    $user['id'] = getNewId();

    fputcsv($handle, [
        $user['id'],
        $user['name'],
        $user['email'],
        password_hash($user['password'], PASSWORD_DEFAULT),
        $user['lastName'],
        $user['firstName'],
        $user['lastNameKt'],
        $user['firstNameKt'],
        $user['phone'],
        $user['birthYear'],
        $user['birthMonth'],
        $user['birthDay'],
        $user['gender'],
        $user['services']
    ]);

    fclose($handle);

    return $user;
}

function getUsers(): array
{
    $handle = fopen(__DIR__ . './../data/users.csv', 'r');
    $users = [];

    while (!feof($handle)) {
        $row = fgetcsv($handle);

        // 空行対策
        if ($row === false || is_null($row[0])) {
            break;
        }

        $user = [
            'id' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'password' => $row[3],
            'lastName' => $row[4],
            'firstName' => $row[5],
            'lastNameKt' => $row[6],
            'firstNameKt' => $row[7],
            'phone' => $row[8],
            'birthYear' => $row[9],
            'birthMonth' => $row[10],
            'birthDay' => $row[11],
            'gender' => $row[12],
            'services' => $row[13]
        ];

        $users[] = $user;
    }

    fclose($handle);

    return $users;
}

function getNewId(): int {
    $maxId = 0;
    $users = getUsers();

    foreach ($users as $user) {
        $id = intval($user['id']);
        if ($id > $maxId) {
            $maxId = $id;
        }
    }

    return $maxId + 1;
}

function login(string $email, string $password): ?array
{
    $users = getUsers();

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            return $user;
        }
    }

    return null;
}

function getUser(string|int $id): ?array
{
    $users = getUsers();

    foreach ($users as $user) {
        if (intval($user['id']) === intval($id)) {
            return $user;
        }
    }

    return null;
}

function isUserExist($email, $phone, $name) {
    $handle = fopen(__DIR__ . './../data/users.csv', 'r');
    if ($handle) {
        while (($data = fgetcsv($handle)) !== false) {
            $existingEmail = $data[2];
            $existingPhone = $data[8];
            $existingName = $data[1];

            if ($existingEmail == $email || $existingPhone == $phone || $existingName == $name) {
                fclose($handle);
                return true; // ユーザーが既に存在する
            }
        }
        fclose($handle);
    }
    return false; // 存在しない
}

function editUser(array $user): void
{
    $users = getUsers();

    $handle = fopen(__DIR__ . './../data/users.csv', 'w');

    foreach ($users as $u) {
        if ($user['id'] === $u['id']) {

            $u = [
                $user['id'],
                $user['name'],
                $user['email'],
                password_hash($user['password'], PASSWORD_DEFAULT),
                $user['lastName'],
                $user['firstName'],
                $user['lastNameKt'],
                $user['firstNameKt'],
                $user['phone'],
                $user['birthYear'],
                $user['birthMonth'],
                $user['birthDay'],
                $user['gender'],
                $user['services']
            ];
        }

        fputcsv($handle, $u);
    }

    fclose($handle);
}

?>
