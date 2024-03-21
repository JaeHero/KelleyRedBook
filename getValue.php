<?php
    $displayForm = true;
    $page_title = 'Get Value';
    require_once ('config.php'); 
    include ('header.html');


    if (isset($_POST['submit'])) {
        require_once ('mysql_connect.php');
        $trimmed = array_map('trim', $_POST);

        $year = $_POST['yearSelection'];
        $make = $_POST['makeSelection'];
        $model = $_POST['modelSelection'];
        
    
        if ($year == "Select" or $make == "Select" or $model == "Select") {
            echo '<p class="error">You must make a selection for the Year Make and Model.</p>';
        }        
        else { // If everything's OK.
            // Query the database: 
            $query_car = "SELECT car_id, base_price FROM car WHERE make='$make' AND model='$model' AND model_year='$year'";		
            try {
                $pdo = new PDO($dsn, $dbUser, $dbPassword);
            }
            catch (PDOException $e){
              die("Fatal Error - Could not connect to the database" . "</body></html>" );
            }
            $result = $pdo->query($query_car);

                
            /* if ($result->rowCount() == 1) { // A match was found
            
              $row = $result->fetch(PDO::FETCH_NUM);
              print(htmlspecialchars($row));
              print($result);
              print(htmlspecialchars($result));
              
              echo"<h1>Car was found and it is...<h1>";


              }	else { // No match was made.
                    echo '<p class="error">Car is not found. Please try again.</p>';
              } */

              if ($result->rowCount() == 1) { // A match was made
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $basePrice = $row['base_price'];
                $carID = $row['car_id'];
                $userID = $_SESSION['user_id'];

                
                // Determine price adjustment based on condition
                $condition = $_POST['carCondition'];
                $conditionAdjustment = 0; // No adjustment by default
                switch ($condition) {
                    case 'Fair':
                        $conditionAdjustment = 0; // base price
                        break;
                    case 'Good':
                        $conditionAdjustment = 0.05; // 5% more
                        break;
                    case 'Very Good':
                        $conditionAdjustment = 0.10; // 10% more
                        break;
                    case 'Excellent':
                        $conditionAdjustment = 0.15; // 15% more
                        break;
                }
                
                // Determine price adjustment based on mileage
                $mileage = $_POST['mileage'];
                $mileageAdjustment = 0; // No adjustment by default
                if ($mileage >= 0 && $mileage <= 10000) {
                    $mileageAdjustment = 0; // price is unaffected
                } elseif ($mileage >= 11000 && $mileage <= 40000) {
                    $mileageAdjustment = -0.05; // price decreases by 5%
                } elseif ($mileage >= 41000 && $mileage <= 100000) {
                    $mileageAdjustment = -0.10; // price decreases by 10%
                } elseif ($mileage > 100000) {
                    $mileageAdjustment = -0.15; // price decreases by 15%
                }

                $additionalOptions = 0;

                if (isset($_POST['cleanTitle'])) {
                    $additionalOptions += 50;
                }
                if (isset($_POST['newTires'])) {
                    $additionalOptions += 50;
                }
                if (isset($_POST['newBattery'])) {
                    $additionalOptions += 50;
                }
                if (isset($_POST['syntheticOil'])) {
                    $additionalOptions += 50;
                }
                if (isset($_POST['newFilters'])) {
                    $additionalOptions += 50;
                }
                



                // Calculate additional cost based on selected options
                /* $additionalOptionsCost = 0;
                if (!empty($_POST['options']) && is_array($_POST['options'])) {
                    $selectedOptions = $_POST['options'];
                    $additionalOptionsCost = count($selectedOptions) * 50; // $50 for each option
                } */

                // Ensure $_POST['options'] is always treated as an array.
                $selectedOptions = isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : [];

                // Calculate additional cost based on selected options

                /* $numOfOptions = 0;
                foreach($selectedOptions as $option){
                    $numOfOptions++;
                    $additionalOptionsCost = $numOfOptions * 50;
                }


 */
                
                // Calculate the final price
                $adjustedPrice = $basePrice * (1 + $conditionAdjustment) * (1 + $mileageAdjustment);
                $adjustedPrice += $additionalOptions;

                // Display the details
                echo "<p>Year: " . htmlspecialchars($_POST['yearSelection']) . "</p>";
                echo "<p>Make: " . htmlspecialchars($_POST['makeSelection']) . "</p>";
                echo "<p>Model: " . htmlspecialchars($_POST['modelSelection']) . "</p>";
                echo "<p>Condition: " . htmlspecialchars($condition) . "</p>";
                echo "<p>Mileage: " . htmlspecialchars($mileage) . " miles</p>";
                echo "<p>Adjusted Price: $" . number_format($adjustedPrice, 2) . "</p>";
                
                // Display the basic adjusted price details
                echo "<p>Adjusted Price for Private Owner: $" . number_format($adjustedPrice, 2) . "</p>";

                // Calculating the Suggested Retail Price (15% more than the adjusted price)
                $suggestedRetailPrice = $adjustedPrice * 1.15;
                echo "<p>Suggested Retail Price: $" . number_format($suggestedRetailPrice, 2) . "</p>";

                // Calculating the Certified Pre-Owned Value (10% more than the Private Owner Value)
                $cpoValue = $adjustedPrice * 1.10;
                echo "<p>Certified Pre-Owned Value: $" . number_format($cpoValue, 2) . "</p>";
                

                $query = "INSERT INTO car_users (adjusted_price, car_ID, user_ID) VALUES (:adjustedPrice, :carID, :userID)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':adjustedPrice', $adjustedPrice, PDO::PARAM_INT);
                    $stmt->bindParam(':carID', $carID, PDO::PARAM_INT);
                    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

                $stmt->execute();


                /* $query_books_authors = "INSERT INTO books_authors (BID, AID)";
				$query_books_authors .= "VALUES ('$bookid' , '$aid')";
 */



            } else { // No match was made.
                echo '<p class="error">Car is not found. Please try again</p>';
            }
            
            } 
            
           } 


if($displayForm) {
?>
        <h2 style="text-align:center">Get your Red Book Value</h2>
        <div>
            <form name="getValuePage" action="getValue.php" method="post">
                <h2 style="text-align:center">Tell me which car you own</h2>
                <hr>
                <h3>...Select the Year, Make, then Model of the car...</h3>
                <select name = "yearSelection">
                    <option value="Select" selected>Select</option>
                    <option value="2010">2010</option>
                    <option value="2011">2011</option>
                    <option value="2012">2012</option>
                    <option value="2013">2013</option>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
                <select name="makeSelection">
                    <option value="Select" selected>Select</option>
                    <option value="bmw">BMW</option>
                    <option value="cadillac">Cadillac</option>
                    <option value="ford">Ford</option>
                    <option value="honda">Honda</option>
                    <option value="hyundai">Hyundai</option>
                    <option value="kia">Kia</option>
                    <option value="lexus">Lexus</option>
                    <option value="nissan">Nissan</option>
                    <option value="subaru">Subaru</option>
                    <option value="tesla">Tesla</option>
                    <option value="toyota">Toyota</option>
                    <option value="vaz">Vaz</option>
                    <option value="volkswagon">Volkswagon</option>
                    <option value="volvo">Volvo</option>
                </select>
                <select name = "modelSelection">
                    <option value="Select" selected>Select</option>
                    <option value="3series">3 series</option>
                    <option value="2101">2101</option>
                    <option value="beetle">Beetle</option>
                    <option value="brz">BRZ</option>
                    <option value="civic">Civic</option>
                    <option value="corolla">Corolla</option>
                    <option value="cybertruck">Cybertruck</option>
                    <option value="escalade">Escalade</option>
                    <option value="escort">Escort</option>
                    <option value="ex30">EX30</option>
                    <option value="fiesta">Fiesta</option>
                    <option value="focus">Focus</option>
                    <option value="gx">GX</option>
                    <option value="hiluk">Hiluk</option>
                    <option value="legacy">Legacy</option>
                    <option value="passat">Passat</option>
                    <option value="rouge">Rouge</option>
                    <option value="sonata">Sonata</option>
                    <option value="sorento">Sorento</option>
                    <option value="tacoma">Tacoma</option>
                </select>

                <div>
                    <h4>Select the condition of the car:</h4>
                    <input type="radio" id="fair" name="carCondition" value="Fair" checked>
                    <label for="fair">Fair</label><br>
                    <input type="radio" id="good" name="carCondition" value="Good">
                    <label for="good">Good</label><br>
                    <input type="radio" id="veryGood" name="carCondition" value="Very Good">
                    <label for="veryGood">Very Good</label><br>
                    <input type="radio" id="excellent" name="carCondition" value="Excellent">
                    <label for="excellent">Excellent</label><br>
                </div>

                <div>
                    <h4>Enter the car's mileage:</h4>
                    <input type="number" name="mileage" min="0" required>
                    <p>Please enter the mileage in miles.</p>
                </div>
                
                <div>
                    <h4>Select any additional options </h4>
                    <input type="radio" id="CleanTitle" name="cleanTitle" value="cleanTitle">
                    <label for="CleanTitle">Clean Title</label><br>
                    <input type="radio" id="NewTires" name="newTires" value="NewTires">
                    <label for="NewTires">New Tires</label><br>
                    <input type="radio" id="NewBattery" name="newBattery" value="NewBattery">
                    <label for="NewBattery">New Battery</label><br>
                    <input type="radio" id="SyntheticOil" name="syntheticOil" value="SyntheticOil">
                    <label for="SyntheticOil">Synthetic Oil</label><br>
                    <input type="radio" id="NewFilters" name="newFilters" value="NewFilters">
                    <label for="NewFilters">New Filters</label><br>
                </div>
                    
                

                <p><input type ="submit" name = "submit" value = "Check Value"></p>

            </form>
        </div>
        <?php
}
?>

<?php // Include the HTML footer.
include ('footer.html');
                            #TODO USE RADIO BUTTON IF NO FIX FOR CHECKBOX BUG #
?>
