<?

$con = mysqli_connect("crpl.cis.udel.edu","oaccvv","openaccresults");

if (!$con)
{
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("OpenACC", $con);
 
if( ! is_numeric($article_id) )
  die('invalid article id');

$query = "SELECT * FROM `comments` WHERE `articleid` =$article_id LIMIT 0 , 30";

$comments = mysql_query($query);

echo "<h1>User Comments</h1>";

// Please remember that  mysql_fetch_array has been deprecated in earlier
// versions of PHP.  As of PHP 7.0, it has been replaced with mysqli_fetch_array.  

while($row = mysql_fetch_array($comments, MYSQL_ASSOC))
{
  $name = $row['name'];
  $email = $row['email'];
  $website = $row['website'];
  $comment = $row['comment'];
  $timestamp = $row['timestamp'];
  
  // Be sure to take security precautions! Even though we asked the user
  // for their "name", they could have typed anything. A hacker could have
  // entered the following (or some variation) as their name:
  //
  // <script type="text/javascript">window.location = "https://SomeBadWebsite.com";</script>
  //
  // If instead of printing their name, "John Smith", we would be printing
  // javascript code that redirects users to a malicious website! To prevent
  // this from happening, we can use the <a href="https://php.net/htmlspecialchars" target="_blank">htmlspecialchars function</a> to convert
  // special characters to their HTML entities. In the above example, it would
  // instead print:
  //
  // <span style="color:red;"><</span>script type=<span style="color:red;">"</span>text/javascript<span style="color:red;">"></span>window.location = <span style="color:red;">"</span>https://SomeBadWebsite.com<span style="color:red;">"</span>;<span style="color:red;"><</span>/script<span style="color:red;">></span>
  //
  // This certainly would look strange on the page, but it would not be harmful
  // to visitors
  
  $name = htmlspecialchars($row['name'],ENT_QUOTES);
  $email = htmlspecialchars($row['email'],ENT_QUOTES);
  $website = htmlspecialchars($row['website'],ENT_QUOTES);
  $comment = htmlspecialchars($row['comment'],ENT_QUOTES);
  
  echo "  <div style='margin:30px 0px;'>
      Name: $name<br />
      Email: $email<br />
      Website: $website<br />
      Comment: $comment<br />
      Timestamp: $timestamp
    </div>
  ";
}

mysql_close($con);

?>