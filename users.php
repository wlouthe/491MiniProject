<?php
// users.php page
$mysqlusername = "ll37";
$mysqlpassword = "burton37";
$mysqlserver = "sql2.njit.edu";
$mysqldb = "ll37";

$mysqli = mysqli_connect($mysqlserver,$mysqlusername, $mysqlpassword, $mysqldb);

$query = mysqli_query($mysqli, "SELECT Email, Rank FROM Users WHERE uCode='".$_COOKIE["mycode"]."';");
$row = $query->fetch_assoc();
$rank = $row['Rank'];
$username = $row['Email'];

if ($rank == 99)
{
    echo "<table>";
    echo "<th><td>User</td><td>Rank</td></th>";
    $query = mysqli_query($mysqli, "SELECT Email, Rank FROM Users;");
    while ($row = $query->fetch_assoc())
    {
        echo "<tr><td>".$row['Email']."</td><td>".$row['Rank']."</td></tr>";
    }
    echo "</table>";
}

?>