<?php
//SELECT VerifiedVote, tblcharities.CharityTitle, COUNT(*) as charityVotes FROM `tblcharityvotes` LEFT JOIN tblcharities ON tblcharityvotes.VerifiedVote = tblcharities.CharityID WHERE VerifiedVote IN (SELECT DISTINCT VerifiedVote FROM tblcharityvotes) GROUP BY VerifiedVote ORDER BY COUNT(*) DESC LIMIT 3
?>
<html>
	<head>
	  <style>
	  h1 {
			color: #ffffff;
	  }
		table {
		  border: none;
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		}
		table.charityBlurb {
			width: 500px;
		}
		table.charityRanked {
			width: 750px;
		}

		td,
		th {
		  padding: 8px;
		  text-align: left;
		  font-size: 14px;
		}

		table.charityRanked {
			color: #ffffff;
		}

		table.votingForm {
			border:none;
			border-top: none;
		}
		table.votingForm tr td {
			background-color: #dadbd8;
			color: #f23941;
		}
		table.votingForm th {
			background-color: #f8f9f3;
			color: #f23941;
		}

	
		table.charityBlurb {
			color: #ffffff;
		}
	
		table.charityBlurb th {
		  background-color: #494949;
		}
		
		table.charityBlurb tr {
		  background-color: #2e2e2e;
		  border: none;
		  cursor: pointer;
		  display: grid;
		  grid-template-columns: repeat(2, 1fr);
		  justify-content: flex-start;
		}

		tr.charityBlurb:first-child:hover {
		  cursor: default;
		  background-color: #951d26;
		}

		tr.charityBlurb:hover {
		  background-color: #406d36;
		}

		.expanded-row-content {
		  border-top: none;
		  display: grid;
		  grid-column: 1/-1;
		  justify-content: flex-start;
		  color: #ffffff;
		  font-size: 13px;
		  background-color: #404c26;
		}
		.expanded-row-content a {
			color: #f0f0f0;
		}

		.hide-row {
		  display: none;
		}
	  </style>
	</head>
	<body style="background-color:#951d26">

<table class="charityRanked">
	<tr>
		<th><h1>Cumberland AC - Charity of the Year, 2023 - ranked by votes (top three on display only)</h1></th>
	</tr>
	<tr>
		<td style="font-size:0.9em;">
		<?php
			//A: RECORDS TODAY'S Date And Time
			$today = time();

			//B: RECORDS Date And Time OF YOUR EVENT
			$event = mktime(17,0,0,12,25,2022); //this is actually 4pm, however IONOS servers are 1 hour ahead

			//C: COMPUTES THE HOURS UNTIL THE EVENT.
			$countdown = ceil(($event-$today)/3600);
			if ($countdown>24)
			{
				//D: DISPLAYS COUNTDOWN UNTIL EVENT
				if (floor($countdown / 24) == 1)
				{
					$dayDays = "day";
				}
				else
				{
					$dayDays = "days";
				}
				
				if (($countdown % 24) == 1)
				{
					$hourHours = "hour";
				}
				else
				{
					$hourHours = "hours";
				}
				
				echo "<i> " . floor($countdown / 24) . " $dayDays, " . ($countdown % 24) . " $hourHours until voting closes for <b>CAC Charity of the year, 2022 (Saturday 25th Dec at 4pm)</b></i>";
			}
			elseif ($countdown > 1)
			{
				$hourHours = "hours";
				//D: DISPLAYS COUNTDOWN UNTIL EVENT
				echo "<i>Less than $countdown $hourHours until voting closes for <b>CAC Charity of the year, 2021 (Saturday 25th Dec at 4pm)</b></i>";

			}
			elseif (($countdown <= 1) && ($countdown > 0))
			{
				$hourHours = "hour";
				//D: DISPLAYS COUNTDOWN UNTIL EVENT
				echo "<i>Less than $countdown $hourHours until voting closes for <b>CAC Charity of the year, 2021 (Saturday 25th Dec at 4pm)</b></i>";
			}
			else
			{
				//D: DISPLAYS COUNTDOWN UNTIL EVENT
				echo "<i>Sorry! Voting closed for <b>CAC Charity of the year, 2021 (Saturday 25th Dec at 4pm)</b></i>";
			}
		?>
		</td>
	</tr>
<?php
	require '../secure/DBconn.php';
	
	$sqlCharityByRank = "SELECT VerifiedVote AS charityID, " .
							   "tblcharities.CharityTitle AS charityTitle, " . 
							   "COUNT(*) as charityVotesCount " .
						"FROM tblcharityvotes " .
						"LEFT JOIN tblcharities " .
							"ON tblcharityvotes.VerifiedVote = tblcharities.CharityID " .
						"WHERE VerifiedVote IN " .
							"(SELECT DISTINCT VerifiedVote " .
							"FROM tblcharityvotes) " .
						"GROUP BY VerifiedVote " .
						"ORDER BY COUNT(*) DESC " .
						"LIMIT 3";
//print "//" . $sqlCharityByRank;

	$result = mysqli_query($conn,$sqlCharityByRank);
	$loopCount = 0;
	
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
		$loopCount = $loopCount + 1;
		
		if ($loopCount == 3) {
			$hexColour = '#b87333';
		}
		elseif ($loopCount == 2) {
			$hexColour = 'silver';
		}
		elseif ($loopCount == 1) {
			$hexColour = 'gold';
		}
		echo "\n<tr><td style=\"background-color:" . $hexColour . "; font-size:" . (2.5 - (0.5*$loopCount)) . "em;\">" . $loopCount . " - " . $row['charityTitle'] . "</td></tr>" ;
	}
?>
</table>
<?php
	if(($event-$today) > 0)
	{
/* 		echo '<script language="javascript">';
		echo 'alert("' . ($event - $today) . '")';
		echo '</script>'; */
		if (isset($_POST["whichForm"]))
		{

			// open connection to the database
			require '../secure/DBconn.php';

			$charityVote = mysqli_real_escape_string($conn, htmlspecialchars($_POST["txtCharity"]));
			$email = mysqli_real_escape_string($conn, htmlspecialchars($_POST["txtEmail"]));

			if($_POST["whichForm"] == "firstForm")
			{

				$sql = "SELECT COUNT(*) AS emailFound FROM tblcharityvotes WHERE email = '" . $email . "'";
				
				// create the 4-digit random verification code
				$verificationCode = random_int(1000,9999);
				
				// prepare the email fields
				$to = $email;
				$subject = "Cumberland AC Validation Code - make your vote count!";
				$txt = "Hi, thanks for voting!"
					 . "<p>Make your vote count; please go back to the browser tab where you have your Cumberland AC charity vote open and enter your unique code: <b><div style=\"font-size:2.5em\">" . $verificationCode . "</div></b> into the text box provided";
				$headers = 'MIME-Version: 1.0' . "\r\n" .
						   'Content-type: text/html;charset=UTF-8' . "\r\n" .
						   'From: charity-vote@cumberlandac.co.uk' . "\r\n" .
						   'Bcc: my.pyne@gmail.com';
				
				// send the email
				mail($to,$subject,$txt,$headers);
			
				// check if email already exists in table
				$result = mysqli_query($conn,$sql);
				// Since there will always be exactly 1 row, fetch it.
				$row = mysqli_fetch_assoc($result);
				
				if ($row['emailFound'] >= 1) {
					//echo "Email already present";
					
					// since the email's already in there, add unverified vote and verification code here
					$sqlAddUnverifiedCode = "UPDATE tblcharityvotes " . 
											"SET VerificationCode=" . $verificationCode . ", " .
											"UnverifiedVote=" . $charityVote . ", " .
											"VerificationCodeSent='" . date("Y-m-d") . "' " . 
											"WHERE email = '" . $email . "'" ;
					
					if (mysqli_query($conn,$sqlAddUnverifiedCode) === TRUE) {
					// echo "Unverified vote and verification code added to email successfully";
					} else {
						echo "Error: <br><br>" . $conn->error . "<br><br> SQL String --> " . $sqlAddUnverifiedCode . " <--";
					}
				}
				if ($row['emailFound'] == 0) {
					// echo "Email doesn't exist yet";
					
					$sqlAddEmailCodeUnverifiedVote = "INSERT INTO tblcharityvotes (email, UnverifiedVote, VerificationCode, VerificationCodeSent)" . 
											"VALUES ('" . $email . "', " . 
											$charityVote . ", ".
											"" . $verificationCode . ", " .
											"'" . date("Y-m-d") . "')" ;
					
					if (mysqli_query($conn,$sqlAddEmailCodeUnverifiedVote) === TRUE) {
					// echo "Unverified vote, verification code and email added successfully";
					} else {
						echo "Error: <br><br>" . $conn->error . "<br><br> SQL String --> " . $sqlAddEmailCodeUnverifiedVote . " <--";
					}
				}
			
				

				print "<br><br><br><br><br>\n\n";
				print "<form id=\"secondForm\" name=\"secondForm\" method=\"POST\" action=\"CACcharity.php\">\n\n";
				print "<br><br>Email: " . $email . "\n\n";
				displayCharity($charityVote);
				print "<input type=\"hidden\" id=\"txtEmail\" name=\"txtEmail\" value=\"" . $email . "\">\n\n";
				print "<input type = \"hidden\" id=\"whichForm\" name=\"whichForm\" value=\"secondForm\">\n\n";
				print "<input type = \"hidden\" id=\"txtCharity\" name=\"txtCharity\" value=\"" . $charityVote . "\">\n\n";
				print "<br><br><br>Please make your vote count! Check your emails and \n";
				print "<br><br>enter your 4-digit verification code here: <input type = \"text\" id=\"txtVerificationCode\" name=\"txtVerificationCode\" >\n\n";
				print "<br><br><input type=\"submit\" id=\"Submit\" name=\"Submit\" value=\"Confirm my vote\">\n\n";
				print "</form>\n\n";
			}
 			if($_POST["whichForm"] == "secondForm")
			{
				$verificationCode = mysqli_real_escape_string($conn, htmlspecialchars($_POST["txtVerificationCode"]));
				
				// code here to confirm verification code entered correctly (and unverified vote becomes verified vote with its own timestamp
				$sqlConfirmVerificationCode = "SELECT COUNT(*) AS codeFound " .
											  "FROM tblcharityvotes " .
											  "WHERE email = '" . $email . "' " .
											  "AND VerificationCode = " . $verificationCode;

				// check if email already exists in table
				$result = mysqli_query($conn,$sqlConfirmVerificationCode);
				// Since there will always be exactly 1 row, fetch it.
				$row = mysqli_fetch_assoc($result);
				
				if ($row['codeFound'] >= 1) {
					print "<br><br><br><br><br><br><br>Thanks! Your vote is verified\n\n";
					
					print "<br><br><a href='CACcharity.php'>Click here</a> to refresh the list\n\n";
					
					// function / routine to get charity (using id) and dispay it to the user to confirm their vote
					displayCharity($charityVote);
					
					// set up a new random code
					$verificationOverwrite = random_int(1000,9999);
					
					// set up unverified vote as a default number
					$unverifiedVote = 99;
					
					// set the verified vote
					$verifiedVote = $charityVote;
					
					// set the date verified
					$dateVerified = date("Y-m-d");
					
					// update the record with the verified details
					$sqlAddVerifiedVote = "UPDATE tblcharityvotes " . 
										  "SET VerificationCode=" . $verificationOverwrite . ", " .
										  "UnverifiedVote=" . $unverifiedVote . ", " .
										  "VerificationCodeSent='" . date("Y-m-d") . "', " . 
									      "VerifiedVote=" . $verifiedVote . ", " . 
										  "VoteVerifiedDate='" . $dateVerified . "' " .
										  "WHERE email = '" . $email . "'" ;
					
					if (mysqli_query($conn,$sqlAddVerifiedVote) === TRUE) {
					 // echo "Verified vote added to email successfully";
					 
					 // Post / Redirect / Get (PRG) code to avoid form resumbission
					 header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
					 exit();
					 
					} else {
						echo "Error: <br><br>" . $conn->error . "<br><br> SQL String --> " . $sqlAddVerifiedVote . " <--";
					}					
				}
				else {
					print "<br><br><br><br><br><br><br>Sorry, <b>" . $verificationCode . "</b> doesn't match what we've got saved for " . $email . "\n\n";
					print "<br><br>If you haven't already, don't forget to check your junk email folder for <b>" . $email . "</b>\n\n";
					print "<br>Sometimes, our emails from <b>charity-vote@cumberlandac.co.uk</b> can end up in there!\n\n";
					print "<br><br><br>Feel free to try again as many times as you like - votes will be counted just the once :)\n\n";
					print "<br><br><a href='CACcharity.php'>Click here</a> to refresh and try again\n\n";
				}
			}
		}
		else
		{
			// if statement to check if this is a reloaded page with form details to process
			print "<br><br><br><br><br><br><br>\n\n";
			print "<form id=\"firstForm\" name=\"firstForm\" method=\"POST\" action=\"CACcharity.php\">\n\n";
			print "<table class=\"votingForm\">\n";
			print "<tr >\n";
			print "<th colspan=\"2\">\n";
			print "<h2>Voting Form</h2>";
			print "</th>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>\n";
			print "<label for=\"txtCharity\">Vote for a charity:</label>\n\n";
			print "</td>\n";
			print "<td>\n";
			print "<select name=\"txtCharity\" id=\"txtCharity\">\n\n";
			
			$sqlCharityList = "SELECT * FROM tblcharities;";

			$result = mysqli_query($conn,$sqlCharityList);		

			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				print "<option value=\"" . $row['CharityID'] . "\">" . $row['CharityTitle'] . "</option>\n";
			}
			print "</select>\n\n";
			print "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>\n";
			print "Email:\n";
			print "</td>\n";
			print "<td>\n";
			print "<input type=\"text\" id=\"txtEmail\" name=\"txtEmail\">\n\n";
			print "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>\n";
			print "</td>\n";
			print "<td>\n";
			print "<input type=\"submit\" id=\"Submit\" name=\"Submit\" value=\"Submit my vote\">\n\n";
			print "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			print "<input type = \"hidden\" id=\"whichForm\" name=\"whichForm\" value=\"firstForm\">\n\n";
			print "</form>\n\n";
		}
	}
	else
	{
		// don't display any form / vote entry buttons after the deadline has passed
	}	
		

		
		/*
		
		vote numbers are anonymised to the public by using its rank.
		(actual votes are available on a separate chart that contains all the nominated charities that is only available to admin)
		
		To protect against one person voting multiple times, users enter their email address. The site emails a random four-digit number for the vote to count.
		
		Once the code has been confirmed and entered into the database, the second random code takes its place in the unvalidated vote slot (to ensure the same code does not occupy both the validated and unvalidated columns)
		
		All email addresses will be deleted the day after the winning charity is announced
		*/
function displayCharity($CharityID) {
	require '../secure/DBconn.php';
	
	$sqlLoadCharity = "SELECT CharityTitle FROM tblcharities WHERE CharityID = " . $CharityID;

	// get charity title
	$result = mysqli_query($conn,$sqlLoadCharity);
	
	// Since there will always be exactly 1 row, fetch it.
	$row = mysqli_fetch_assoc($result);
	
	print "<br><br>Charity: " . $row['CharityTitle'];

}

		?>
	<br><br>
	<h1>Read a little more about the charities here:</h1>
	  <table class="charityBlurb">
		<tr>
		  <th>Logo</th>
		  <th>Charity</th>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
		  <td><img src="img\CalTru50px.png" /></td>
		  <td>Calvert Trust Lake District<br>(click for more)</td>
		  <td class='expanded-row-content hide-row'><img src="img\CalTru150px.png" />The charity provides dedicated 
			facilities so that disabled guests can enjoy outdoor activities in a safe and accessible environment.
			<br><br>For more info please visit <a href="http://www.calvertlakes.org.uk">CalvertLakes.org.uk</a></td>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
		  <td><img src="img\AniCon50px.png" /></td>
		  <td>Animal Concern Cumbria<br>(click for more)</td>
		  <td class='expanded-row-content hide-row'><img src="img\AniCon150px.png" />Animal Concern Cumbria is dedicated 
		    to supporting and caring for animals in need.  They offer a secure and caring environment for animals whose 
			owners, for whatever reason, are unable to care for them any longer and help them find permanent, loving homes 
			along with providing support and guidance for owners who wish to keep their animals.
		<a href="http://www.animalconcerncumbria.org">AnimalConcernCumbria.org</a></td>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
		  <td><img src="img\TeaEvi50px.png" /></td>
		  <td>Team Evie<br>(click for more)</td>
		  <td class='expanded-row-content hide-row'><img src="img\TeaEvi150px.png" />Team Evie help sick children and 
			their families in hospitals and in the community across the North East and Cumbria. 
			<br><br>For more info please visit <a href="http://www.teamevie.org">TeamEvie.org</a></td>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
		  <td><img src="img\WstHse50px.png" /></td>
		  <td>West House<br>(click for more)</td>
		  <td class='expanded-row-content hide-row'><img src="img\WstHse150px.png" />West House are Cumbria’s leading 
		    care and support provider for adults and children with disabilities. They provide individual, genuine and 
			caring support and enable all those in our care to lead a fulfilling, happy life in a homely environment. 
			<br><br>For more info please visit <a href="http://www.westhouse.org.uk">WestHouse.org.uk</a></td>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
			<td><img src="img\GrtNrt50px.png" /></td>
			<td>Great North Air Ambulance<br>(Click for more)</td>
			<td class='expanded-row-content hide-row'><img src="img\GrtNrt150px.png" />Great North Air Ambulance provide air ambulance services across the
			North-East, Cumbria and North Yorkshire.
			<br><br>For more info please visit <a href="http://www.greatnorthairambulance.co.uk">GreatNorthAirAmbulance.co.uk</a></td>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
			<td><img src="img\AndMan50px.png" /></td>
			<td>Andy's Man Club<br>(Click for more)</td>
			<td class='expanded-row-content hide-row'><img src="img\AndMan150px.png" />Andy's Man Club aim to eliminate 
			  the stigma surrounding mental health and create a judgment-free, confidential space where men can be open 
			  about the storms in their lives. They hold weekly, free-to-attend peer-to-peer support groups for men aged 
			  over 18. They are opening a Workington in January.
			<br><br>For more info please visit <a href="http://www.andysmanclub.co.uk">AndysManClub.co.uk</a></td>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
		  <td><img src="img\EvyLfe50px.png" /></td>
		  <td>Every Life Matters<br>(click for more)</td>
		  <td class='expanded-row-content hide-row'><img src="img\EvyLfe150px.png" />Promoting Suicide Safer Communities
		  and providing Suicide Bereavement Support across Cumbria
		  <br><br>For more info please visit <a href="http://www.every-life-matters.org.uk">Every-Life-Matters.org.uk</a></td>
		</tr>
		<tr class="charityBlurb" onClick='toggleRow(this)'>
		  <td><img src="img\CitAdv50px.png"/></td>
		  <td>Copeland Citizens Advice<br>(click for more)</td>
		  <td class='expanded-row-content hide-row'><img src="img\CitAdv150px.png" />Citizens Advice Copeland is an 
			independent charity which provides free, confidential, impartial, independent information and advice to
			people who live and work in the borough of Copeland and their rights and responsibilities.
			<br><br>For more info please visit: <a href="http://www.citizensadvicecopeland.org.uk">CitizensAdviceCopeland.org.uk</a></td>
		</tr>
	  </table>

	  <script>
		const toggleRow = (element) => {
		  element.getElementsByClassName('expanded-row-content')[0].classList.toggle('hide-row');
		  console.log(event);
		}
	  </script>
	</body>
</html>