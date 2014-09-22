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
    <hr id="myhr" style="display:none;">
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
    <div class="large-8 columns" role="content">
      <p><span style="font-weight: bold;">We meet every Friday at 12 noon in GITC 4415.</span></p>
      <p>ACM is an organization focused on helping students further themselves in their fields of study and extending their knowledge past what is covered in the classroom.</p>
      <p>Within ACM we have multiple Special Interest Groups (SIGs) which focus on a range of topics from Network Security to Video Game Development.  These SIGs allow students to go in depth in topics they are interested in while working with a network of people who share the same interests.  ACM also offers free tutoring for majority of the classes offered by the College of Computing Sciences.  Throughout the semester we have numerous workshops and seminars.  ACM has also taken trips to various sites such as Prudential and Google.</p>
      <p>In addition to the academic services we offer, ACM also offers recreational services to its members.  We have multiple computers in our office along with a nice collection of game consoles for anyone who wants to come in and hang out.  We have also hosted numerous game tournaments on campus.</p>
    </div>    
    <div class="large-4 columns" role="content">
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
    </div>

    <div class="large-12 columns" role="content">
      <div class="flex-video">
        <iframe src="https://www.google.com/calendar/embed?src=njitacm%40gmail.com&ctz=America/New_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
      </div>
    </div>
    <div class="large-12 columns" role="content">
 
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
    <script src="js/vendor/jquery.js"></script>
    <script src="js/main.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
