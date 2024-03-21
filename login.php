<?php
    $displayForm = true;
    $page_title = 'Login Page';
    require_once ('config.php'); 
    include ('header.html');


    if (isset($_POST['login'])) {
        require_once ('mysql_connect.php');
        $trimmed = array_map('trim', $_POST);
    
        // Validate the email address:
        if (!empty($_POST['email'])) {
            $email = $trimmed['email'];
        } else {
            $email = FALSE;
            echo '<p class="error">You forgot to enter your email address!</p>';
        }
        
        // Validate the password:
        if (!empty($_POST['pass'])) {
            $password = $trimmed['pass'];
        } else {
            $password = FALSE;
            echo '<p class="error">You forgot to enter your password!</p>';
        }
        
        if ($email && $password) { // If everything's OK.
            // Query the database: 
            $query_user = "SELECT user_id, first_name, email, pass FROM usersKelly WHERE email='$email'";		
            try {
                $pdo = new PDO($dsn, $dbUser, $dbPassword);
            }
            catch (PDOException $e){
              die("Fatal Error - Could not connect to the database" . "</body></html>" );
            }
            $result  = $pdo->query($query_user);
                
            if ($result->rowCount() == 1) { // A match was found
            
              $row = $result->fetch(PDO::FETCH_NUM);
              if (password_verify($password, $row[3])) { 
                // Register the values & redirect:
                $_SESSION['user_id'] = $row[0];
                $_SESSION['name'] = $row[1];
                echo htmlspecialchars("Hi $row[1], you are now logged in as '$row[2]'");
                $displayForm = false;


              }	else { // No match was made.
                    echo '<p class="error">Either the email address and password entered do not match those on file or you have no account yet.</p>';
              }
            } else { 
                echo '<p class="error">Either the email address and password entered do not match those on file or you have no account yet.</p>';
          }
            
           } else { 
            echo '<p class="error">Please try again.</p>';
        }
    
    }


if($displayForm) {
?>
        <h2 style="text-align:center">Login Page</h2>
        <div>
            <form name="loginpage" action="login.php" method="post">
                <h2 style="text-align:center">Login</h2>
                <hr>
                <h3>Returning Users Login</h3>
                <p>Enter your email address: <input type="text" name="email" /></p>
                <p>Enter your password: <input type="password" name="pass" /></p>
                <p class="password">(Passwords are case-sensitive and must be at least 6 characters long )</p>
                <p> <input type="submit" name="login" value="Log In" class="submit-button" />&nbsp;
                    <input type="reset" value="Reset Form" class="reset-button" />
                </p>
            </form>

        </div>
        <?php
}
?>

<?php // Include the HTML footer.
include ('footer.html');
?>
