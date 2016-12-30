<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>RTT | Bench Press Analysis</title>
    <link rel="stylesheet" type="text/css" href="../stylesheet.css"/>
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

    // Get the count of total entries in the training log
    $sql = "SELECT weight, reg_date FROM training_log WHERE exercise = 'bench_press';";
    $res = $conn->query($sql);

    $output = ""; // string to hold table for results
    $current = $res->fetch_assoc();

    // Create output for HTML
    if ($current == null) {
      $output = "No results found.";
    }
    else {
      while ($current != null) {
        $output = $output . "<tr>
                  <td>" . substr($current["reg_date"], 0, 10) . "</td>
                  <td>" . $current["weight"] . "</td>
                  <tr>";
        $current = $res->fetch_assoc();
      }
    }

  ?>

  <body>

    <header>
      <h1><a href="../index.php" class="plain">Resistance Training Tracker</a></h1>
    </header>

    <div class="body">

      <h2>Bench Press Analysis</h2>

      <table border="1" padding-left="5px" padding-right="5px">
      <tr>
        <td><b>Date</b></td>
        <td><b>Weight</b></td>
      </tr>
      <?php echo $output;?>
      </table>

      <br>
      <p><a href="index.php">Back to Analysis Overview</a></p>
      <p><a href="../index.php">Home</a></p>

    </div>

  </body>
</html>
