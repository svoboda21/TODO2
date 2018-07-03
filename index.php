<?php
    require_once 'function.php';
    define('DB_DRIVER','mysql');
    define('DB_HOST','localhost');
    define('DB_NAME','todo2');
    define('DB_USER','root');
    define('DB_PASS','');
    try
    {
        $connect_str = DB_DRIVER . ':host='. DB_HOST . ';dbname=' . DB_NAME;
        $db = new PDO($connect_str,DB_USER,DB_PASS);
        $error_array = $db->errorInfo();
        if($db->errorCode() != 0000)
            echo "SQL :fffdf " . $error_array[2] . '<br />';
        $error_array = $db->errorInfo();
        if($db->errorCode() != 0000)
            echo "SQL: " . $error_array[2] . '<br /><br />';
    }
    catch(PDOException $e)
    {
        die("Error: ".$e->getMessage());
    }
    $result = $db->query("SELECT * FROM `task` ");
    $result1 = $db->query("SELECT * FROM `user` ");
?>
    <style>
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        table td, table th {
            border: 1px solid #ccc;
            padding: 5px;
        }

        table th {
            background: #eee;
        }
    </style>
    <h1>Список дел на сегодня</h1>
    <div style="float: left">
        <form method="GET">
            <input type="text" name="description" placeholder="Описание задачи" value="" />
            <input type="submit" name="save" value="Добавить" />
        </form>
    </div>
    <div style="float: left; margin-left: 20px;">
        <form method="POST">
            <label for="sort">Сортировать по:</label>
            <select name="sort_by">
                <option value="date_created">Дате добавления</option>
                <option value="is_done">Статусу</option>
                <option value="description">Описанию</option>
            </select>
            <input type="submit" name="sort" value="Отсортировать" />
        </form>
    </div>
    <div style="clear: both"></div>
    <table>
        <tr>
            <th>Описание задачи</th>
            <th>Дата добавления</th>
            <th>Статус</th>
            <th></th>
            <th>Ответственный</th>
            <th>Автор</th>
            <th>Закрепить задачу за пользователем</th>
            <th></th>
        </tr>
        <tr>
            <?php
                $iduser=$_SESSION['id'];
                $nameuser=$_SESSION['login'];
                echo "ID=$iduser ";
                echo " Имя: $nameuser";
                if (!empty($_GET["save"])&&!empty($_GET["description"])) {
                    $a = $_GET["description"];
                    $id = $db->lastInsertId();
                    $today = date("Y-m-d H:i:s");
                    $res1 = $db->exec("INSERT INTO `task` (`id`, `user_id`, `assigned_user_id`, `description`, `is_done`, `date_added`)
         VALUES ('$id', '$nameuser', '22','$a','0', '$today')");
                    $result = $db->query("SELECT * FROM `task` ");
                    redirect('index');
                }
                if (!empty($_POST["sort_by"])&&!empty($_POST["sort"])) {
                    if ($_POST["sort_by"]== 'is_done') {
                        $result = $db->query("SELECT description,date_added,is_done,assigned_user_id,user_id FROM `task` ORDER BY `is_done` DESC");
                    }
                }
                if (!empty($_POST["sort_by"])&&!empty($_POST["sort"])) {
                    if ($_POST["sort_by"]== 'date_created') {
                        $result = $db->query("SELECT description,`date_added`,is_done,assigned_user_id,user_id FROM `task` ORDER BY `date_added` DESC");
                    }
                }
                if (!empty($_POST["sort_by"])&&!empty($_POST["sort"])) {
                    if ($_POST["sort_by"]== 'description') {
                        $result = $db->query("SELECT description,`date_added`,is_done,assigned_user_id,user_id FROM `task` ORDER BY `description` DESC");
                    }
                }
                if (!empty($_GET["action"])) {
                    if ($_GET["action"] == "delete"){
                        $idget=$_GET['id'];
                        $result4 = $db->query("DELETE FROM `task` where id=$idget");
                        $result = $db->query("SELECT * FROM `task` ");
                     }
                }
                if (!empty($_GET["action"])) {
                    if ($_GET["action"] == "done"){
                        $idget=$_GET['id'];
                        $res3 = $db->exec("UPDATE task SET is_done = 1 where id=$idget");
                        $result = $db->query("SELECT * FROM `task` ");
                    }
                }
                if (!empty($_GET['action'])) {
                    if ($_GET['action'] == 'rew'){
                        ?>
                        <div style="float: left">
                            <form method="POST">
                                <input type="text" name="desc" placeholder="Описание задачи" value=""/>
                                <input type="submit" name="rew" value="Изменить"/>
                            </form>
                        </div>
                        <?php
                    }
                    if (!empty($_POST["desc"])&&!empty($_POST["rew"])) {
                        $idget=$_GET['id'];
                        $a = $_POST["desc"];
                        $res2 = $db->exec("UPDATE task SET description = '$a' where id=$idget");
                        $result = $db->query("SELECT * FROM `task` ");
                    }

                }

                while($row = $result->fetch()){
            ?>
        <tr>
            <td style="width:6px valign=" center
            " align="center"><?php echo $row['description']; ?> </td>
            <td style="width:400px valign=" center
            " align="center"><?php echo $row['date_added']; ?></td>
            <td style="width:180px valign=" center
            " align="center"><?php echo ($row['is_done'] == 0) ? 'Не выполнено' : 'Выполнено'; ?></td>
            <td style="width:50px valign=" center
            " align="center">
            <a href='index.php?id=<?php echo $row['id'] ?>&action=rew'>Изменить</a>
            <a href='index.php?id=<?php echo $row['id'] ?>&action=done'>Выполнить</a>
            <a href='index.php?id=<?php echo $row['id'] ?>&action=delete'>Удалить</a>
            </td>
            <td style="width:400px valign=" center " align="center"><?php echo $row['assigned_user_id']; ?></td>
            <td style="width:400px valign=" center " align="center"><?php echo $row['user_id']; ?></td>
            <td><form method="POST">
<?php
    while ($row1 = $result1->fetch()){
        ?>
            <select name="user_id"><option value="user">
 <?php
     echo $row1['id'];
     if (!empty($_POST["assign"])) {
         $_SESSION['assign'] = $row1['id'];
         $user_id=$_SESSION['assign'];
         $resuser = $db->exec("UPDATE task SET assigned_user_id = '$user_id'");
         redirect('index');
     }
?>
                </option>

<?php                    }
?>
                        <input type="submit" name="assign" value="Переложить ответственность">
                </form>
            </td>
 <?php
                 }
            ?>
            </table>
    <table>
        <tr>
            <th>Описание задачи</th>
            <th>Дата добавления</th>
            <th>Статус</th>
            <th> </th>
            <th>Автор</th>
            <th>Ответственный</th>

            <th></th>
        </tr>
        <tr>
            <?php
                $iduser=$_SESSION['id'];
                $nameuser=$_SESSION['login'];
                $result2 = $db->query("SELECT * FROM `task` WHERE assigned_user_id=$iduser ");
                if (!empty($_GET["action1"])) {
                    if ($_GET["action1"] == "delete1"){
                        $idget1=$_GET['id'];
                        $result5 = $db->query("DELETE FROM `task` WHERE id=$idget1");
                        $result2 = $db->query("SELECT * FROM `task` ");
                    }
                }
                if (!empty($_GET["action1"])) {
                    if ($_GET["action1"] == "done1"){
                        $idget1=$_GET['id'];
                        $res5 = $db->exec("UPDATE task SET is_done = 1 WHERE id=$idget1");
                        $result2 = $db->query("SELECT * FROM `task` ");
                    }
                }
                if (!empty($_GET['action1'])) {
                    if ($_GET['action1'] == 'rew1'){?>
                        <div style="float: left">
                            <form method="POST">
                                <input type="text" name="desc" placeholder="Описание задачи" value=""/>
                                <input type="submit" name="rew" value="Изменить"/>
                            </form>
                        </div>
                        <?php
                    }
                    if (!empty($_POST["desc1"])&&!empty($_POST["rew1"])) {
                        $a = $_POST["desc1"];
                        $idget1=$_GET['id'];
                        $res5 = $db->exec("UPDATE task SET description = '$a' WHERE id=$idget1");
                        $result2 = $db->query("SELECT * FROM `task` ");
                    }
                }
                while($row4 = $result2->fetch()){
            ?>
        <tr>
            <td style="width:6px valign=" center
            " align="center"><?php echo $row4['description']; ?> </td>
            <td style="width:400px valign=" center
            " align="center"><?php echo $row4['date_added']; ?></td>
            <td style="width:180px valign=" center
            " align="center"><?php echo ($row4['is_done'] == 0) ? 'Не выполнено' : 'Выполнено'; ?></td>
            <td style="width:50px valign=" center
            " align="center">
            <a href='index.php?id=<?php echo $row4['id'] ?>&action=rew'>Изменить</a>
            <a href='index.php?id=<?php echo $row4['id'] ?>&action=done'>Выполнить</a>
            <a href='index.php?id=<?php echo $row4['id'] ?>&action=delete'>Удалить</a>
            </td>
            <td style="width:400px valign=" center " align="center"><?php echo $row4['user_id']; ?></td>
            <td style="width:400px valign=" center " align="center"><?php echo $row4['assigned_user_id']; ?></td>
             <?php
                }
            ?>
    </table>

