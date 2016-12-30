<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>RTT | Workout Logged</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
  </head>

    <?php
      $servername = "localhost";
      $username = $_ENV["MYSQL_LOGIN"];
      $password = $_ENV["MYSQL_PASSWORD"];
      $dbname = $_ENV["MYSQL_RTT_DB"];
      
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $keys = array_keys($_POST); // 
      $res = array();
      $success;
      
      // Read values from _POST into MySQL 
      for ($i = 0; $i < count($keys); $i++) {
        $sql = "INSERT INTO training_log (exercise, weight) VALUES ('" .
         $keys[$i] . "'," . $_POST[$keys[$i]] . ");";

        $success = ($conn->query($sql));
      }

      // Output whether or not workout logged successfully.
      $result; // string to hold output
      if ($success)
        $result = "Workout logged successfully.";
      else {
        $result = "Error logging workout: " . $conn->error . ".";
      }  

      $conn->close();

    ?>

  </head>

  <body>
	  
	  <header>
  	  <h1><a class="plain" href="index.php">Resistance Training Tracker</a></h1>
    </header>

    <div class="body">
    <p><?php echo $result ?></p>
    <p><a href="index.php">Home</a></p>
    </div>

  </body>
</html> 
