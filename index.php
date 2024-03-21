<?php
// This is the main page for the site.

// Include the configuration file:
require_once ('config.php'); 

// Set the page title and include the HTML header:
$page_title = 'Welcome to this Site!';
include ('header.html');

// Welcome the user (by name if they are logged in):
if (isset($_SESSION['user_id'])) {
	$name = htmlspecialchars($_SESSION['name']);
	echo "<h1>Welcome, $name! </h1>";
    echo "<h2>Select Check Car Value to check the value of a vehicle</h2>";
    echo "<h2>Select See Car Inventory to see all of your cars </h2>";
}
else {
    echo "<h1>Hello!</h1>";
    echo "<h2>Please register or login to get started </h2>";
}
?>
<?php // Include the HTML footer file:
include ('footer.html');
?>
