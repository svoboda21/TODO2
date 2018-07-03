<?php
    session_start();
    function getValidate($login,$password)
    {
        define('DB_DRIVER', 'mysql');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'todo2');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        try {
            $connect_str = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
            $db = new PDO($connect_str, DB_USER, DB_PASS);
            $error_array = $db->errorInfo();
            if ($db->errorCode() != 0000)
                echo "SQL :Ошибка " . $error_array[2] . '<br />';
                $error_array = $db->errorInfo();
                if ($db->errorCode() != 0000)
                    echo "SQL: " . $error_array[2] . '<br /><br />';
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
        $result = $db->query("SELECT * FROM `user` ");
        while ($row = $result->fetch()) {
            if ($login == $row['login']) {
                if ($password == $row['password']){
                return true;
                } else {
                    return false;
                }
             }
        }
    }
    function redirect($page)
    {
        header("Location: $page.php");
        die;
    }
    function registration($newlogin,$newpassword)
    {
        define('DB_DRIVER', 'mysql');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'todo2');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        try {
            $connect_str = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
            $db = new PDO($connect_str, DB_USER, DB_PASS);

            $error_array = $db->errorInfo();
            if ($db->errorCode() != 0000)
                echo "SQL :Ошибка " . $error_array[2] . '<br />';
            $error_array = $db->errorInfo();
            if ($db->errorCode() != 0000)
                echo "SQL: " . $error_array[2] . '<br /><br />';
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
         $result = $db->query("SELECT * FROM `user` ");
         while($row = $result->fetch()) {
            if ($row['login'] === $newlogin) {
                echo 'Такой логин существует';
                exit;
            } else {
                $id = $db->lastInsertId();
                $add = $db->exec("INSERT INTO `user`(`id`, `login`, `password`) VALUES ('$id','$newlogin','$newpassword')");
                $lastid=$db->lastInsertId();
                echo "Пользователь $newlogin c ID=$lastid добавлен ";
                $_SESSION['id']=$lastid;
                $_SESSION['login'] = $newlogin;
                redirect('index');
                exit;
            }
        }
    }
?>
