<?php
// This is the registration page for the site, which uses a sticky form

require_once ('config.php');
$page_title = 'Registeration Main';
include ('header.html');

if (isset($_POST['register'])) { // Handle the form.

	require_once ('mysql_connect.php');

    $trimmed = array_map('trim', $_POST);

    $first_name = $last_name = $email = $password = FALSE;

    if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$first_name = $trimmed['first_name'];
	} else {
		echo '<p class="error">Please enter your first name!</p>';
	}
	
	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$last_name = $trimmed['last_name'];
	} else {
		echo '<p class="error">Please enter your last name!</p>';
	}
	
	// Check for an email address:
	if (preg_match ('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $trimmed['email'])) {
		$email = $trimmed['email'];
	} else {
		echo '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$password = $trimmed['password1'];
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}

    if ($first_name && $last_name && $email && $password) { // If everything's OK...
		  
		//Query to check if the email address is available:
		try {
			$pdo = new PDO($dsn, $dbUser, $dbPassword);
		}
		catch (PDOException $e){
		  die("Fatal Error - Could not connect to the database" . "</body></html>" );
		}

        $hash = password_hash($password, PASSWORD_DEFAULT);
        if (add_user($pdo, $email, $hash, $first_name, $last_name, date("Y-m-d"))) { // If it ran OK.
            $displayForm = false;
            echo '<h3>Thank you for registering! You may now login!</h3>';
            include ('footer.html');
            exit(); //Stop the page
        }
        else {
            echo '<p class="error">You could not be registered due to a system error!</p>';
        }
    }
    else {
		echo '<p class="error">Please re-enter your passwords and try again.</p>';
    }

    }


 function add_user($pdo, $email, $hash, $first_name, $last_name, $registration_date){
    //PHP Supports executing a prepared statement, which is used to execute the same statement repeatedly with high efficiency.
    $stmt = $pdo->prepare('INSERT INTO usersKelly(first_name, last_name, email, pass, registration_date) VALUES(?,?,?,?,?)');
    //Binds variables to a prepared statement as parameters
    //PARAM_STR: Used to represents the SQL CHAR, VARCHAR, or other string data type
    //$stmt->bindParam($first_name, $last_name, $email, $hash, $registration_date);
    $stmt->bindParam(1, $first_name, PDO::PARAM_STR, 40);
    $stmt->bindParam(2, $last_name, PDO::PARAM_STR, 80);
    $stmt->bindParam(3, $email, PDO::PARAM_STR, 80);
    $stmt->bindParam(4, $hash, PDO::PARAM_STR, 256);
    $stmt->bindParam(5, $registration_date, PDO::PARAM_STR, 256);
    //if ($stmt->execute([$email, $hash, $first_name, $last_name, $registration_date]))
    if ($stmt->execute())
        return true;
    else 
        return false;
    //echo "<h3>User " . $last_name . " has been added to the database successfully.</h3>";
}
?>
        <h2 style="text-align:center">Register Page</h2>
        <div>
            <form name="loginpage" action="register.php" method="post">
                <h2 style="text-align:center">Register</h2>
                <p style="text-align:center">New users, please complete the top form to register as a user. Returning user,
                    please complete the second
                    form to log in</p>
                <hr>
                <h3>New User Registration</h3>
                <p>First Name: <input type="text" name="first_name" value="<?php
                echo $firstName; ?>" /></p>
                <p>Last Name: <input type="text" name="last_name" value="<?php
                echo $lastName; ?>" /></p>
                <p>E-Mail Address: <input type="text" name="email" /></p>
                <p>Create a Password: <input type="password" name="password1" /></p>
                <p>Confirm Password: <input type="password" name="password2" /></p>
                <p class="password">(Passwords are case-sensitive and must be at least 6 characters long )</p>
                <p> <input type="submit" name="register" value="Register" class="submit-button" />&nbsp;
                    <input type="reset" value="Reset Form" class="reset-button" />
                    <hr>
                <br>
                <!-- <h3>Returning Users Login</h3>
                <p>Enter your email address: <input type="text" name="email" /></p>
                <p>Enter your password: <input type="text" name="password" /></p>
                <p class="password">(Passwords are case-sensitive and must be at least 6 characters long )</p>
                <p> <input type="submit" name="login" value="Log In" class="submit-button" />&nbsp;
                    <input type="reset" value="Reset Form" class="reset-button" />
                </p> -->
            </form>

        </div>

<?php // Include the HTML footer.
include ('footer.html');
?>
