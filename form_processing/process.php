<?php
if (isset($_POST['submit'])) {
// This variable is to choose which type of processing you're using. 
$mode = "csv"; // or "csv" or "email"
// These set your initial variables.
$q1_why = $_POST['q1_why'];
$q2_service = $_POST['q2_service'];
$q3_state = $_POST['q3_state'];
$q4_comments = $_POST['q4_comments'];
   // it's good planning to set your variables explicitly before setting them programatically.
   $error = FALSE;
   if ($q3_state == "none") {
   $error = TRUE;
   }
   if (isset($q4_comments)) {
   $q4_comments = trim($q4_comments);
   $q4_comments = strip_tags($q4_comments);
   }
   if (isset($q1_why) && isset($q2_service) && isset($q3_state) && isset($q4_comments) && $error == FALSE) {
   $process = TRUE;
   } else {
   $process = FALSE;
   }
   while ((list($key,$val) = each($q2_service))) {
   $q2_services .= "[" . $val . "]";
   }

   if($mode == "mysql") {
   // in order to play with this section, you will need to create your database and edit these fields to your own information
   define ('DB_USER', 'username');
   define ('DB_PASSWORD', 'password');
   define ('DB_HOST', 'localhost');
   define ('DB_NAME', 'your_db');
   $dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) or die('Failure: ' . mysql_error() );
   mysql_select_db(DB_NAME) or die ('Could not select database: ' . mysql_error() );
   
   $query = "INSERT INTO survey VALUES ('','$q1_why','$q2_services','$q3_state','$q4_comments',NOW())";
   $q = mysql_query($query);
   
    if (!$q) {
    exit("<p>MySQL Insertion failure.</p>");
    } else {
    mysql_close();
    //for testing only
    //echo "<p>MySQL Insertion Successful</p>";
    }
   }

   if ($mode == "csv") {
   $csv_file = 'survey.csv';
    if (is_writable($csv_file)) {
        if (!$csv_handle = fopen($csv_file,'a')) {
        // this line is for troubleshooting
        // echo "<p>Cannot open file $csv_file</p>";
        exit;
        }
    }
   $csv_item = "\"$q1_why\",\"$q2_services\",\"$q3_state\",\"$q4_comments\"\n";
    if (is_writable($csv_file)) {
        if (fwrite($csv_handle, $csv_item) === FALSE) {
        //for testing
        //echo "Cannot write to file";
        exit; } 
    }
   fclose($csv_handle);
   }

   if ($mode == "email") {
   //first, define your four mail function fields
   $recipient = "you@domain.com";
   $subject = "Online Survey from Your Domain";
   $content = "Online Survey:\n
   Why are you contacting us? $q1_why\n
   What services are you interested in? $q2_services\n
   Where are you from? $q3_state\n
   Your Comments:\n
   $q4_comments\n";
   $header = "From: YourSurvey <survey@domain.com>\n"."Reply-To: survey@domain.com\n";
   //now send it off
   mail($recipient, $subject, $content, $header);
   }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us" dir="ltr">
<head>
<title>Beginner's Guide to Form Processing in PHP</title>
</head>
<body>
<h1>Beginner's Guide to Form Processing in PHP</h1>
<h2>Joseph C Dolson</h2>
<p>
This script is the accompaniment to an article at <a href="http://www.joedolson.com/articles/2007/02/processing-forms-with-php/">Joe Dolson Accessible Web Design</a>.
</p>
<?php 
if (isset($_POST['submit'])) {
echo "<p style='padding: .5em; border: 2px solid red;'>Thanks for submitting this form! I've processed this form with $mode!</p>";
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<p>
<fieldset>
<legend>Why are you contacting us?</legend>
<div>
<input type="radio" name="q1_why" id="q1a" value="Technical Support" /><label for="q1a">Technical Support</label><br />
<input type="radio" name="q1_why" id="q1b" value="Applying for Work" /><label for="q1b">Applying for Work</label><br />
<input type="radio" name="q1_why" id="q1c" value="Wanted to Hire You" /><label for="q1c">Wanted to Hire You</label><br />
<input type="radio" name="q1_why" id="q1d" value="Other" /><label for="q1d">Other</label><br />
</div>
</fieldset>
<fieldset>
<legend>What service are you interested in? (Check all that apply.)</legend>
<div>
<input type="checkbox" name="q2_service[]" id="q2a" value="Heating" /><label for="q2a">Heating</label><br />
<input type="checkbox" name="q2_service[]" id="q2b" value="Cooling" /><label for="q2b">Cooling</label><br />
<input type="checkbox" name="q2_service[]" id="q2c" value="Plumbing" /><label for="q2c">Plumbing</label><br />
<input type="checkbox" name="q2_service[]" id="q2d" value="Wiring" /><label for="q2d">Wiring</label><br />
<input type="checkbox" name="q2_service[]" id="q2e" value="Carpentry" /><label for="q2e">Carpentry</label>
</div>
</fieldset>
<fieldset>
<legend>Where are you from?</legend>
<div>
<label for="q3">Pick your state:</label>
<select id="q3" name="q3_state">
<option value="none">Choose One</option>
<option value="MN">Minnesota</option>
<option value="MT">Montana</option>
<option value="NY">New York</option>
</select>
</div>
</fieldset>
<fieldset>
<legend>Comments</legend>
<div>
<label for="q4">Please provide additional comments about our services.</label><br />
<textarea id="q4" name="q4_comments" rows="4" cols="40"></textarea>
</div>
</fieldset>
<fieldset>
<div>
<label for="submit">Submit the form</label>
<input type="submit" name="submit" id="submit" value="Send your Input" />
</div>
</form>
</body>
</html>