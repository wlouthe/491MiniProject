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
	<body style="background-image:url(./diamond_upholstery.png)">
		<div style="background-image:url(./triangular.png)">
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
		<?php if($cookiechecker==1){echo '<div style="background-image:url(./triangular.png); width:100%;">Welcome '.$uname.'! <a href="logout.php">Logout?</a></div>';}  ?>
		</div>
        <?php
            if($cookiechecker == 1)
            {
				echo '<iframe style = "position:absolute;right:0px; width:30%; height:400px;" src="https://www.google.com/calendar/embed?src=njitacm%40gmail.com&ctz=America/New_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>';
				echo '
				<div class="entry" style="width:70%;">
<p><span style="font-weight: bold;">We meet every Friday at 12 noon in GITC 4415.</span></p>
<p>ACM is an organization focused on helping students further themselves in their fields of study and extending their knowledge past what is covered in the classroom.</p>
<p>Within ACM we have multiple Special Interest Groups (SIGs) which focus on a range of topics from Network Security to Video Game Development.  These SIGs allow students to go in depth in topics they are interested in while working with a network of people who share the same interests.  ACM also offers free tutoring for majority of the classes offered by the College of Computing Sciences.  Throughout the semester we have numerous workshops and seminars.  ACM has also taken trips to various sites such as Prudential and Google.</p>
<p>In addition to the academic services we offer, ACM also offers recreational services to its members.  We have multiple computers in our office along with a nice collection of game consoles for anyone who wants to come in and hang out.  We have also hosted numerous game tournaments on campus.</p>
<h1>Contact Us</h1>
<div id=":ue" style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr">ACM Email:&nbsp;<a href="mailto:njitacm@gmail.com">njitacm@gmail.com</a></div>
<div id=":uf" style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr">President’s Email:&nbsp;<a href="mailto:njitacm+pres@gmail.com">njitacm+pres@gmail.com</a></div>
<div id=":ug" style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr">Vice President’s Email:&nbsp;<a href="mailto:njitacm+vp@gmail.com">njitacm+vp@gmail.com</a></div>
<div id=":t9" style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr">Secretary’s Email:&nbsp;<a href="mailto:njitacm+sec@gmail.com">njitacm+sec@gmail.com</a></div>
<div id=":un" style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr">Treasurer’s Email:&nbsp;<a href="mailto:njitacm+treas@gmail.com">njitacm+treas@gmail.com</a></div>
<div id=":um" style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr">Webmaster’s Email:&nbsp;<a href="mailto:njitacm+web@gmail.com">njitacm+web@gmail.com</a></div>
<div style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr"></div>
<div style="margin-bottom: 0.2em; text-align: left; color: #222222; font-family: arial, sans-serif; line-height: normal;" dir="ltr">Office Location: GITC 4402</div>
<p>Office Phone #’s:</p>
<ul>
<li>973-596-2990</li>
<li>973-596-2861</li>
</ul>
<h1>Our Constitution</h1>
<p><strong>Article I. Name</strong><br>
The name of this organization shall be the Association for Computing Machinery (ACM). In reference to the ACM when in the context of the international organization, the name shall be the  New Jersey Institute of Technology Chapter of the Association for Computing Machinery (NJIT ACM).</p>
<p><strong>Article II. Purpose</strong><br>
The purpose of this chapter is to organize NJIT students with interests in computing and the associated technologies, so that they may be able to share their knowledge and skills with their peers. This student collaboration in the studies of computing technologies will result in a variety of events, projects, and services hosted by this chapter.</p>
<p><strong>Article III. Non-Discrimination Policy</strong><br>
Membership in this chapter shall be open to any student currently enrolled at NJIT.</p>
<p><strong>Article IV. Membership</strong><br>
Section 1. Membership in this chapter shall be open to any student currently enrolled at NJIT.</p>
<p>Section 2. An “active member” shall be defined as any member who has attended at least one-half of the scheduled meetings that have met up to that point in the semester.</p>
<p>Section 3. Only NJIT full-time undergraduate students who are “active members” as described in Article IV, Section 2 shall be eligible to vote.</p>
<p>Section 4. Attendance by a simple majority, or greater than 50%, of the active voting membership shall constitute a quorum.</p>
<p><strong>Article V. Officers &amp; Elections</strong><br>
Section 1. The officers of this chapter shall be President, Vice-President, Secretary, Treasurer, Webmaster, and Public Relations.</p>
<p>Section 2. Election of officers shall be by secret ballot during the second to last meeting of the Fall semester. Officers shall be elected by a majority vote of approval of a quorum.</p>
<p>Section 3. Installation of newly-elected officers shall be held upon the start of the Spring semester. The term of the newly elected officers shall be for one calendar year.</p>
<p>Section 4. All NJIT full-time undergraduate students who are active members of this chapter and whose grade point average for the preceding quarter is 2.5 or above shall be eligible for office, with the following restrictions: </p>
<p>A. Students who will graduate before the fall semester of the academic year following elections shall be ineligible for office. Their graduation date should be no less than three Spring and/or Fall semesters after the elections.</p>
<p>B. Presidential candidates must have been members of the ACM student chapter at NJIT for one full semester by the fall quarter in which they will serve.</p>
<p>Section 5. Any officer shall be brought up for removal from office if his grade point average falls below the minimum standards set forth in Article V, Section 4, or if the members feel he or she is not adequately performing their duties. A new officer shall be elected to fill the vacancy at the next scheduled meeting. An officer may be removed from office by a three-fourths majority vote of a quorum.</p>
<p>Section 6. No elected officer of this chapter shall have veto power. </p>
<p>Section 7. This chapter shall elect a faculty advisor from the faculty/staff of New Jersey Institute of Technology with the approval of the Office of the Dean of Student Services. The faculty advisor shall be selected by a majority vote of approval of a quorum.</p>
<p><strong>Article VI. Duties of Officers</strong><br>
Section 1.	The President shall preside at all meetings of this chapter and of its Executive Council. He or she shall also represent this chapter at all meetings with the ACM Regional Representative. He or she shall also appoint all committees of this chapter and committee chairmen. He or she shall also maintain steady contact with the College of Computing Sciences.</p>
<p>Section 2.	The Vice-President shall assume the duties of the President in the event of the President’s absence. He shall assume those duties of the President that are delegated to him by the President. He shall serve as this chapter’s liason to the Student Senate. He or she shall also be responsible for coordinating chapter events.</p>
<p>Section 3.	The Secretary shall keep minutes of all chapter meetings. It is his duty to make this information publically available. He or she shall also be responsible for submitting any pertinent paperwork to ACM National and/or the NJIT Student Senate in a timely fashion. He or she shall also be responsible for the active membership roster according to Article IV of this constitution.</p>
<p>Section 4.	The Treasurer shall maintain the chapter’s financial operations. He or she shall also make the annual report of the chapter finances as required by the Treasurer of ACM National.</p>
<p>Section 5.	The Webmaster shall be responsible for maintaining and updating the chapter website. and chapter pages on social networking websites. He shall also be responsible for managing permissions and access of the officers to the various services utilized by the organization. He shall be responsible for maintaining any custom applications created for the organization.</p>
<p>Section 6.	The Public Relations officer shall be responsible for maintaining this chapter’s public image. His duties shall be, but are not limited to, ensuring that this chapter’s events are well advertised and recruiting new students into the organization.</p>
<p><strong>Article VII. Meetings</strong><br>
Meetings shall be held every week during the common hour. Members will be notified prior to any change in the meeting time or location.</p>
<p><strong>Article VIII. Conduct</strong><br>
Section 1.	This chapter and its members agree to uphold and abide by the rules and regulations of New Jersey Institute of Technology.</p>
<p>Section 2.	This chapter and its members agree to uphold and abide by the standard ethical guidelines outlined in the National ACM Code of Ethics and Professional Conduct.</p>
<p>Section 3.	The chapter acknowledges that it is responsible for the behavior of members and guests at any of its functions.</p>
<p>Section 4.	Hazing in any form is not allowed by this organization or its individual members.</p>
<p><strong>Article IX. Amendments and Procedure</strong><br>
Section 1.	Proposed amendments to this constitution shall be distributed to all current officers and members.</p>
<p>Section 2.	An amendment to this constitution shall be officially approved once unanimously agreed upon by all current officers, having received a favorable 2/3 vote from a quorum, and approved by the NJIT Student Senate.</p>
<p>Section 3.	Robert’s Rules of Order Revised shall be the final authority on any points not covered in the National ACM Constitution or in this Constitution.</p>
</div>
				';
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
