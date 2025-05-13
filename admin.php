<?php
    include 'includes/auth.php';
    function isAdmin() {
        return $_SESSION['isAdmin'] == 1;
    }

    if (!isAdmin()) {
        header('Location: login.php');
        exit();
    }

    // Handle form submission for inserting weather data
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_weather'])) {
        $date = $_POST['date'];
        $humidity = (int)$_POST['humidity'];
        $season = $_POST['season'];
        $temperature = (int)$_POST['temperature'];
        $weatherType = $_POST['weatherType'];

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO weather (date, humidity, season, temperature, weatherType) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $date, $humidity, $season, $temperature, $weatherType);

        if ($stmt->execute()) {
            $message = "Weather data added successfully.";
        } else {
            $error = "Error: Could not add weather data.";
        }
        $stmt->close();
    }

    // Handle form submission for deleting weather data by date
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_weather'])) {
        $delete_date = $_POST['delete_date'];

        $stmt = $conn->prepare("DELETE FROM weather WHERE date = ?");
        $stmt->bind_param("s", $delete_date);

        if ($stmt->execute()) {
            $message = "Weather data deleted successfully.";
        } else {
            $error = "Error: Could not delete weather data. Please check the date.";
        }
        $stmt->close();
    }
?>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="Weather info" />
    <meta name="keywords" content="weather" />
    <link rel="stylesheet" href="./style.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse
/5.4.1/papaparse.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Weather Admin</title>
    <style>
        label { font-weight: bold; margin-bottom: 10px; display: block; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd; }
        .message { color: green; margin-bottom: 15px; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <header style="width: 100vw">
      <h1>Dobrodosli <?php echo $_SESSION['username'] ?></h1>
      <nav>
        <ul>
          <li ><a href="index.php">Pocetna</a></li>
          <li><a href="slike.php">Galerija</a></li>
          <li><a href="grafikon.html">Grafikon</a></li>
          <li class="active"><a href="admin.php">Admin</a></li>
        </ul>
        <div class="dropdown">
          <i class="fa fa-bars"></i>
          <ul>
            <li class="active"><a href="index.php">Pocetna</a></li>
            <li><a href="slike.php">Galerija</a></li>
            <li class="active"><a href="grafikon.html">Grafikon</a></li>
            <li><a href="admin.php">Admin</a></li>
          </ul>
        </div>
      </nav>
      <form action="includes/auth.php" method="POST" style="display: inline;">
    <button type="submit" name="logout" style="margin-right:30.px;width:100%;padding: 10px 20px; background-color: #f00; color: #fff; text-decoration: none; border-radius: 5px;">Logout</button>
  </form>
</header>
    <h2>Add Weather Data</h2>
    <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
        <label>Date:</label>
        <input type="date" name="date" required>
        <label>Humidity (%):</label>
        <input type="number" name="humidity" min="0" max="100" required>
        <label>Season:</label>
        <select name="season" required>
            <option value="Summer">Summer</option>
            <option value="Autumn">Autumn</option>
            <option value="Spring">Spring</option>
        </select>
        <label>Temperature (°C):</label>
        <input type="number" name="temperature" required>
        <label>Weather Type:</label>
        <select name="weatherType" required>
            <option value="Sunny">Sunny</option>
            <option value="Rainy">Rainy</option>
            <option value="Cloudy">Cloudy</option>
        </select>
        <button type="submit" name="add_weather">Add Weather</button>
    </form>

    <h2>Delete Weather Data</h2>
    <form method="POST" action="">
        <label>Enter Date to Delete:</label>
        <input type="date" name="delete_date" required>
        <button type="submit" name="delete_weather">Delete Weather</button>
    </form>
</body>
</html>
