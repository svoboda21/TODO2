<?php
    session_start();
    function getValidate($login,$password)
    {
        define('DB_DRIVER', 'mysql');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'todo');
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
        $result = $db->query("SELECT * FROM `tasks` ");
        $row = $result->fetch();
        if ($login==$row[1]&&$password==$row[2]) {
            return true;
        } else {
            return false;
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
        define('DB_NAME', 'todo');
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
        $result = $db->query("SELECT * FROM `tasks` ");
        while($row = $result->fetch()) {
            if ($row['login'] == $newlogin) {
                echo 'Такой логин существует';
            } else {
                $add = $db->exec("INSERT INTO `tasks` (`id`, `description`, `is_done`, `date_added`)
               VALUES ('$id', '$newlogin', '$newpassword'");
                echo 'Пользователь добавлен';
                $resultid = $db->query("SELECT '$newlogin' FROM `tasks` ");
                $rowid = $resultid->fetch();
                $_SESSION['id']=$rowid['id'];
            }
        }
    }
?>
