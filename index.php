<?php
    
	//CREATE TABLE Users (Id INT PRIMARY KEY AUTO_INCREMENT, Email VARCHAR(100), Password VARCHAR(500), uCode VARCHAR(200), Rank INT);
	//
	
    $mysqlusername = "ll37";
    $mysqlpassword = "burton37";
    $mysqlserver = "sql2.njit.edu";
    $mysqldb = "ll37";

    $mysqli = mysqli_connect($mysqlserver,$mysqlusername, $mysqlpassword, $mysqldb);
    
	$cookiechecker=0;
	$dbauthentication=0;
    
	if(isset($_COOKIE["mycode"]))
	{
		//echo "hello";
		$expire=time()+60*60;
		$mykey=$_COOKIE["mycode"];
		//echo $mykey;
		$results = $mysqli->query("SELECT * FROM Users WHERE uCode = '".$mykey."';");
        $row = $results->fetch_assoc();
        $success = 0;
		if($row["uCode"] == $mykey)
		{
			$success = 1;
			$uname = $row["Email"];
			setcookie('mycode',$mykey,$expire,'/');
			$cookiechecker=1;
		}
		
	}
	if($cookiechecker!=1)
	{
		if(isset($_POST["username"])&&isset($_POST["password"]))
		{
			$_POST["username"] = htmlspecialchars($_POST["username"],ENT_QUOTES);
			$_POST["password"] = crypt(htmlspecialchars($_POST["password"],ENT_QUOTES),'$6$rounds=5000$'.$_POST["username"].$_POST["username"].'$');
			
            $results = $mysqli->query("SELECT * FROM Users WHERE Email = '".$_POST["username"]."' AND Password = '".$_POST["password"]."';" );
			
            $row = $results->fetch_assoc();
            if($row["Email"] == $_POST["username"])
            {
				$authentication = 1;
				
				//set cookie
				$expire=time()+60*60;
                $mykey = md5($_POST["username"].time());
				$mysqli->query("UPDATE Users SET uCode = '".$mykey."' WHERE Email = '".$_POST["username"]."';");
				$uname=$_POST['username'];
				setcookie('mycode',$mykey,$expire,'/');
				$cookiechecker=1;
                //setcookie("mycode","",time()-3600,"/");
			}
			else $authentication = 0;
		}
	}
	//*/
?>
<html>
	<head>
	<script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<title>
			Login Page
		</title>
		<style>
			body
			{
				margin:0 auto 0;
			}
		</style>
	</head>
	<body style="background-image:url(./img/grey_wash_wall/grey_wash_wall.png)">
		<div style="background-image:url(./img/triangular_@2X.png)">
		<?php
			echo "<dbauthentication style='display:none'>".$dbauthentication."</dbauthentication>";
			if(isset($authentication))
			{
				if($authentication==1)
				{
					echo "<div style='color:green;'>Authenticated</div>";
				}
				else echo "<div style='color:red;'>Authentication Failed</div>";
			}
		?>
		<form name="login" id="myLogin" action="index.php" method="post" <?php if($cookiechecker==1){echo 'style="display:none;"';}  ?>>
			<table>
				<tr>
					<td>
					Email:
					</td><td>
					<input name="username" type="email" <?php if(isset($_POST["username"])) echo 'value="'.$_POST["username"].'"';?>>
					</td>
				</tr>
				<tr>
					<td id="cpw">
					Password:
					</td><td>
					<input name="password" type="password">
					</td>
				</tr>
				
				<tr>
					<td>
						<input id="mysubmit" type="submit" value="login">
					</td>
					<td>
					<a id="signup" href="#" style="display:block; font-size:12px;">Signup</a>
                    </td>
                    <!--
						</tr>
							<tr>
							<td>
							
						<a href="#" style="display:block; font-size:12px;">
							Change Password?
						</a>
						</td></tr>
						
						</table>
						<button id="custompass3" class="custompass2">
							Reset Password?
						</button>
						<button id="custompass4" style="display:none;">
							Confirm Reset?
						</button>
            -->
			
				
			</table>
		</form>
		<hr id="myhr" style="display:none;">
            <table id="signuppage" style="display:none;">
                
                <tr id="sp1">
					<td>
					Email:
					</td><td>
					<input id="sp1info" name="username" type="email">
					</td>
                    <td>
                        <button id="create">
                            Create
                        </button>
                    </td>
				</tr>
                <tr id="sp2" style = "display:none; color:green;"><td>
                Your account should be created. Check your email to complete registration.   
                </td></tr>
            </table>
		<?php if($cookiechecker==1){echo 'Welcome '.$uname.'! <a href="logout.php">Logout?</a>';}  ?>
		</div>
        <?php
            if($cookiechecker == 1)
            {
                echo "<div><a href='./home.php'>Home Page</a></div>";
            }
        ?>
        
        
        <script type='text/javascript'>
			
			$(document).ready(function(){
            
                $( "#signup" ).click(function(){
					$("#myhr").show("slow",function(){});
                    $( "#signuppage" ).show("slow", function(){});
                    //event.preventDefault();
                    
                });
                
                $( "#create" ).click(function(){
                    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
                    if(pattern.test($("#sp1info").val()))
                    {
                        $.ajax({
                            type: "POST",
                            url: "http://web.njit.edu/~ll37/491MiniProject/mailer.php",
                            async: false,
                            data: {
                                "email": $("#sp1info").val(),
                            },
                            dataType: "xml",
                            success: function(email,code){
                                console.log("success");
								$( "#sp1" ).hide();
								$( "#sp2" ).show("slow", function(){});
                            },
                            error: function(baba, gaga) {
                                alert("Error occured: " + gaga);
                            }
                         });
                        
                    }
          
                    
                    event.preventDefault();
                    
                });
            
			});
            
             
		</script>
        
        
	</body>
</html>
