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

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ACM | Welcome</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>

  <div class="row">
    <div class="large-12 columns">
      <div class="nav-bar right">
       <ul class="button-group">
         <li><a href="#" class="button">Home</a></li>
         <li><a href="#" class="button">Forum</a></li>
         <li><a href="#" class="button">SIGS</a></li>
         <li><a href="#" class="button">Constitution</a></li>
        </ul>
      </div>
      <h1>ACM <small>Njit Special Interest GroupS.</small></h1>
      <hr/>
    </div>
  </div>
 <div class="row">
  <div class="large-12 columns">
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
      </table>
    </form>
    <!-- <hr id="myhr" style="display:none;"> -->
    <table id="signuppage" style="display:none;">
      <tr id="sp1">
        <td>Email:</td>
        <td><input id="sp1info" name="username" type="email"></td>
        <td><button id="create">Create</button></td>
      </tr>
      <tr id="sp2" style = "display:none; color:green;">
        <td>Your account should be created. Check your email to complete registration.</td>
      </tr>
    </table>
    <?php
        $getRank = $mysqli->query("SELECT Rank FROM Users WHERE uCode = '".$mykey."';");
        $row = $getRank->fetch_assoc();
        if($cookiechecker==1) 
        {
            echo '<div style="background-image:url(./triangular.png); width:100%;">Welcome '.$uname.'!'; 
            if ($row['Rank'] == 99)
                echo ' - <a href="users.php">Users</a> -';
            echo ' <a href="logout.php">Logout?</a></div>';
        }
    ?>
  </div>
 </div>
  <div class="row">
    <div style="width:100%" role="content">
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
 
        <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
 
        <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
 
      </article>
 
      <hr/>
 
      <article>
 
        <h3><a href="#">Blog Post Title</a></h3>
        <h6>Written by <a href="#">John Smith</a> on August 12, 2012.</h6>
 
        <div class="row">
          <div class="large-6 columns">
            <p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit. Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine spare ribs reprehenderit culpa.</p>
            <p>Boudin aliqua adipisicing rump corned beef. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
          </div>
          <div class="large-6 columns">
            <img src="http://placehold.it/400x240&text=[img]"/>
          </div>
        </div>
 
        <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
 
        <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
 
      </article>
 
    </div>
 
  </div>
 
   
 
 
   
 
  <footer class="row">
    <div class="large-12 columns">
      <hr/>
      <div class="row">
        <div class="large-6 columns">
          <p>© Copyright no one at all. Go to town.</p>
        </div>
        <div class="large-6 columns">
          <ul class="inline-list right">
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
            <li><a href="#">Link 4</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
    <script scr="js/main.js"></script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
