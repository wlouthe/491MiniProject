<?php
// users.php page
$mysqlusername = "ll37";
$mysqlpassword = "burton37";
$mysqlserver = "sql2.njit.edu";
$mysqldb = "ll37";

$mysqli = mysqli_connect($mysqlserver,$mysqlusername, $mysqlpassword, $mysqldb);
$myquery1="SELECT Id, Email, Rank FROM Users WHERE uCode='".$_COOKIE["mycode"]."';";
//echo $myquery1."<br>";
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
        $myquery = "UPDATE Users SET Rank=".$_POST["rank".$checker]." WHERE Id = ".$_POST[$checker].";";
        $mysqli->query($myquery);
        //echo $myquery."<br>";
        $checker++;
    }
}

if ($rank == 99)
{
    echo "<form action='./users.php' method='post'>";
    echo "<table>";
    echo "<tr><td>User</td><td>Rank</td></tr>";
    $query = mysqli_query($mysqli, "SELECT Id, Email, Rank FROM Users;");
    $cnt = 0;
    while ($row = $query->fetch_assoc())
    {
        echo "<tr><td>".$row['Email']."</td><td><input type='hidden' name='".$cnt."' value='".$row['Id']."'><select name='rank".$cnt."'>
            <option value='1' ></option><option value='1' ";
        if($row["Rank"] == 1) echo "selected";
            echo">Member</option><option value='99' ";
        if($row["Rank"] == 99) echo "selected";
            echo ">Admin</option></select></td></tr>";
        $cnt++;
    }
    echo "<tr><td><input type='submit' name='submit' value='submit'></td></tr>";
    echo "</table>";
    echo "</form>";
}

?>