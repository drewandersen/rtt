<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>RTT | Log Workout</title>
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

      // Get the count of total entries in the training log
      $sql = "SELECT COUNT(*) FROM training_log;";
      $count_res = $conn->query($sql);
      $count = $count_res->fetch_array()[0] - 3;

      // Get the last three exercises
      $sql = "SELECT * FROM training_log WHERE id > " . $count . ";";
      $prev_ex_res = $conn->query($sql);

      $day = 'A';

      $sql = "SELECT exercise from training_log where id = (select 
              max(id) from training_log where exercise != 'squats');";
      $last_non_squat_res = $conn->query($sql);
      while ($row = $last_non_squat_res->fetch_assoc()) {
        if ($row['exercise'] === "bench_press" || $row['exercise'] === "upright_row") {
          $day = 'B';
        }
      }

      // Get most recent weight for squats
      $sql = "SELECT weight from training_log where reg_date = 
      (select max(reg_date) from training_log where exercise = 
      'squats') and exercise = 'squats';";
      $last_squat_res = $conn->query($sql)->fetch_array();
      $last_squat = $last_squat_res[0];

      if($day === 'A') {
        // set exercise names
        $ex1_name = "Bench Press";
        $ex1_form = "bench_press";
        $ex2_name = "Upright Row";
        $ex2_form = "upright_row";

        // Get most recent weight for bench press
        $sql = "SELECT weight from training_log where reg_date = 
        (select max(reg_date) from training_log where exercise = 
        'bench_press') and exercise = 'bench_press';";
        $last_bench_press_res = $conn->query($sql)->fetch_array();
        $ex1_last = $last_bench_press_res[0];

        // Get most recent weight for upright row 
        $sql = "SELECT weight from training_log where reg_date = 
        (select max(reg_date) from training_log where exercise = 
        'upright_row') and exercise = 'upright_row';";
        $last_upright_row_res = $conn->query($sql)->fetch_array();
        $ex2_last = $last_upright_row_res[0];

      }  else {
        // set exercise names
        $ex1_name = "Overhead Press";
        $ex1_form = "overhead_press";
        $ex2_name = "Deadlift";
        $ex2_form = "deadlift";

        // Get most recent weight for bench press
        $sql = "SELECT weight from training_log where reg_date = 
        (select max(reg_date) from training_log where exercise = 
        'overhead_press') and exercise = 'overhead_press';";
        $last_overhead_press_res = $conn->query($sql)->fetch_array();
        $ex1_last = $last_overhead_press_res[0];

        // Get most recent weight for upright row 
        $sql = "SELECT weight from training_log where reg_date = 
        (select max(reg_date) from training_log where exercise = 
        'deadlift') and exercise = 'deadlift';";
        $last_deadlift_res = $conn->query($sql)->fetch_array();
        $ex2_last = $last_deadlift_res[0];
        
      }
	  ?>

  </head>

  <body>
	  
	  <header>
	    <h1><a class="plain" href="index.php">Resistance Training Tracker</a></h1>
    </header>
    
    <div id="body">
    <p> Workout type: <?php echo $day; ?> </p>

    <form method="post" action="process.php">
      Squats (<?php echo $last_squat; ?>)
      <br>
      <input type="number" min="0" max="1000" step="5" name="squats" autocomplete="off" maxlength="4">
      <br>
      <br>
      <?php echo $ex1_name?> (<?php echo $ex1_last?>)
      <br>
      <input name="<?php echo $ex1_form?>" type="number" min="0" max="1000" step="5" autocomplete="off" maxlength="4"> 
      <br>
      <br>
      <?php echo $ex2_name?> (<?php echo $ex2_last?>)
      <br>
      <input name="<?php echo $ex2_form?>" type="number" min="0" max="1000" step="5" autocomplete="off" maxlength="4"> 
      <br>
      <br>
      <input type="submit" value="Submit">
    </form>

    <p><a href="index.php">Home</a></p>
    </div> 

  </body>
</html>
