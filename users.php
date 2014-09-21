<?php
// users.php page
$mysqlusername = "ll37";
$mysqlpassword = "burton37";
$mysqlserver = "sql2.njit.edu";
$mysqldb = "ll37";

$mysqli = mysqli_connect($mysqlserver,$mysqlusername, $mysqlpassword, $mysqldb);

$query = mysqli_query($mysqli, "SELECT Id, Email, Rank FROM Users WHERE uCode='".$_COOKIE["mycode"]."';");
$row = $query->fetch_assoc();
$rank = $row['Rank'];
$username = $row['Email'];

$ADMIN = 99;
$MEMBER = 1;
if($username!="")
{
    $checker = 0;
    while(isset($_POST[$checker]))
    {
        $mysqli->query("UPDATE TABLE Users SET Rank=".$_POST["rank".$checker]." WHERE Id = ".$_POST[$checker].";");
        $checker++;
    }
}
if ($rank == 99)
{
    echo "<form action='./users.php' method='post'>";
    echo "<table>";
    echo "<tr><td>User</td><td>Rank</td></tr>";
    $query = mysqli_query($mysqli, "SELECT Email, Rank FROM Users;");
    $cnt = 0;
    while ($row = $query->fetch_assoc())
    {
        echo "<tr><td>".$row['Email']."</td><td>".$row['Rank']."</td><td><input type='hidden' name='".$cnt."' value='".$row["Id"]."'><select name='rank".$cnt."'>
            <option value='1'>Member</option><option value='99'>Admin</option></select><td>
            </td></tr>";
        $cnt++;
    }
    echo "<tr><td><input type='submit' name='submit' value='submit'><\td><\tr>";
    echo "</table>";
    echo "</form>";
}

?>