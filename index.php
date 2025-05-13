<?php
include 'includes/auth.php';
?>

<!DOCTYPE html>
<html lang="hr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="Weather info" />
    <meta name="keywords" content="weather" />
    <link rel="stylesheet" href="./style.css" />

    <title>Virtualna Videoteka</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse
/5.4.1/papaparse.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
  </head>
  <body>
    <header style="width: 100vw">
      <h1>Dobrodosli <?php echo $_SESSION['username'] ?></h1>
      <nav>
        <ul>
          <li class="active"><a href="index.php">Pocetna</a></li>
          <li><a href="slike.php">Galerija</a></li>
          <li><a href="grafikon.html">Grafikon</a></li>
          <?php if ($_SESSION['isAdmin'] == 1) { ?>
              <li><a href="admin.php">Admin</a></li>
            <?php } ?>
        </ul>
        <div class="dropdown">
          <i class="fa fa-bars"></i>
          <ul>
            <li class="active"><a href="index.php">Pocetna</a></li>
            <li><a href="slike.php">Galerija</a></li>
            <li><a href="grafikon.html">Grafikon</a></li>
            <?php if ($_SESSION['isAdmin'] == 1) { ?>
              <li><a href="admin.php">Admin</a></li>
            <?php } ?>
          </ul>
        </div>
      </nav>
      <form action="includes/auth.php" method="POST" style="display: inline;">
    <button type="submit" name="logout" style="margin-right:30.px;width:100%;padding: 10px 20px; background-color: #f00; color: #fff; text-decoration: none; border-radius: 5px;">Logout</button>
  </form>
</header>

    <h1>Vremenski Podaci</h1>

    <section
      id="filter"
      style="
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 20px 20px;
      "
    >
      <div
        style="
          display: flex;
          flex-direction: row;
          width: 100vw;
          justify-content: space-around;
        "
      >
        <div style="display: flex; flex-direction: column">
          <label for="weatherType">Tip Vremena:</label>
          <select name="weatherType" id="weatherType">
            <option value="all">Any</option>
            <option value="Sunny">Sunčano</option>
            <option value="Rainy">Kišovito</option>
            <option value="Cloudy">Oblačno</option>
          </select>
        </div>

        <div style="display: flex; flex-direction: column">
          <label for="minTemp">Minimalna temperatura zraka:</label>
          <input
            type="text"
            id="minTemp"
            name="minTemp"
            style="width: 100%"
            placeholder="20"
          />
        </div>

        <div>
          <div>Odaberi Sezonu</div>
          <div style="display: flex; flex-direction: row">
            <label for="spring"> Prolječe</label>
            <input type="checkbox" id="spring" name="spring" value="Spring" />
          </div>
          <div style="display: flex; flex-direction: row">
            <label for="vehicle2"> Ljeto</label>
            <input type="checkbox" id="summer" name="summer" value="Summer" />
          </div>
          <div style="display: flex; flex-direction: row">
            <label for="vehicle2"> Jesen</label>
            <input type="checkbox" id="autumn" name="autumn" value="Autumn" />
          </div>
        </div>
      </div>
      <button style="width: 300px; height: 50px" onclick="onFilter()">
        Filtriraj
      </button>
    </section>

    <section class="main_section" style="width: 100%; justify-content: center">
      <table id="weather_data" style="width: 90%">
        <thead></thead>
        <tbody></tbody>
      </table>
    </section>

    <div class="plan" style="width: 100%">
      <div>Plan:</div>
      <div
        id="plan_list"
        style="
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 15px;
          width: 100%;
        "
      ></div>
      <button id="send_button" style="width: 100%" onclick="sendPlans()">
        Send plan to mail
      </button>
    </div>

    <footer style="text-align: center">
      <p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
    </footer>

    <script src="script.js"></script>
  </body>
</html>
