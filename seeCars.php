<?php

$page_title = 'Inventory';
require_once ('config.php'); // Make sure this file provides access to $pdo
include ('header.html');

// Check if the user is logged in by checking if user_id is set in $_SESSION
if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id']; // Retrieve user_id from session

    require_once ('mysql_connect.php');
    $trimmed = array_map('trim', $_POST);

    try {
        $pdo = new PDO($dsn, $dbUser, $dbPassword);
    }
    catch (PDOException $e){
      die("Fatal Error - Could not connect to the database" . "</body></html>" );
    }

    $query = "SELECT c.make, c.model, c.model_year, cu.adjusted_price
              FROM car_users cu
              JOIN car c ON cu.car_ID = c.car_id
              WHERE cu.user_ID = :userID";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($cars) > 0) {
        foreach ($cars as $car) {
            // Display each car and its details
            echo "Make: " . htmlspecialchars($car['make']) . "<br>";
            echo "Model: " . htmlspecialchars($car['model']) . "<br>";
            echo "Year: " . htmlspecialchars($car['model_year']) . "<br>";
            echo "Adjusted Price: $" . htmlspecialchars($car['adjusted_price']) . "<br>";

            $basePrice = $car['adjusted_price'];
            // Calculate prices for different selling options
            $privateOwnerValue = $basePrice; // Assuming the adjusted price is for private sale
            $suggestedRetailPrice = $basePrice * 1.15; // 15% more than base price
            $certifiedPreOwnedValue = $privateOwnerValue * 1.10; // 10% more than private owner value

            // Display prices for different selling options
            echo "Adjusted Price for Private Owner: $" . number_format($privateOwnerValue, 2) . "<br>";
            echo "Suggested Retail Price: $" . number_format($suggestedRetailPrice, 2) . "<br>";
            echo "Certified Pre-Owned Value: $" . number_format($certifiedPreOwnedValue, 2) . "<br><br>";

            
        }
    } else {
        echo "You have not priced any cars yet.<br>";
    }
} else {
    // Redirect user to login page or display a message if they are not logged in
    echo "Please <a href='login.php'>log in</a> to view your inventory.";
}

include ('footer.html');
?>
