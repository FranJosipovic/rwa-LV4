<?php
include 'includes/auth.php';
include 'includes/db.php';

$sql = "SELECT * FROM slika";
$result = $conn->query($sql);

$ratings = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $rating_sql = "SELECT * FROM ocjena WHERE user_id = $user_id";
    $rating_result = $conn->query($rating_sql);
    while ($row = $rating_result->fetch_assoc()) {
        $ratings[$row['slika_id']] = $row['ocjena']; 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate'])) {
    $slika_id = $_POST['slika_id'];
    $ocjena = $_POST['ocjena'];

    $check_sql = "SELECT * FROM ocjena WHERE slika_id = $slika_id AND user_id = $user_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE ocjena SET ocjena = $ocjena WHERE slika_id = $slika_id AND user_id = $user_id";
        $conn->query($update_sql);
    } else {
        $insert_sql = "INSERT INTO ocjena (slika_id, user_id, ocjena) VALUES ($slika_id, $user_id, $ocjena)";
        $conn->query($insert_sql);
    }
    header('Location: slike.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Weather info" />
    <meta name="keywords" content="weather" />
    <link rel="stylesheet" href="./style_slike.css" />
    <link rel="stylesheet" href="./style.css" />
    <title>Weather Info</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>
<body>
<header style="width: 100vw">
    <h1>Dobrodošli <?php echo $_SESSION['user_id']; ?></h1>
    <nav>
        <ul>
            <li class="active"><a href="index.php">Početna</a></li>
            <li><a href="slike.php">Galerija</a></li>
            <li><a href="grafikon.html">Grafikon</a></li>
        </ul>
        <div class="dropdown">
            <i class="fa fa-bars"></i>
            <ul>
                <li class="active"><a href="index.php">Početna</a></li>
                <li><a href="slike.php">Galerija</a></li>
                <li><a href="grafikon.html">Grafikon</a></li>
            </ul>
        </div>
    </nav>
    <form action="includes/auth.php" method="POST" style="display: inline;">
        <button type="submit" name="logout" style="margin-right:30.px;width:100%;padding: 10px 20px; background-color: #f00; color: #fff; text-decoration: none; border-radius: 5px;">Logout</button>
    </form>
</header>

<h1>Galerija slika</h1>
<section class="galerija">
    <?php if ($result->num_rows > 0) : ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <figure class="galerija_slika">
                <a href="#img<?php echo $row['id']; ?>">
                    <img src="<?php echo $row['source']; ?>" alt="<?php echo $row['name']; ?>" />
                </a>
                <figcaption><?php echo $row['name']; ?></figcaption>
                 <?php if(isset($ratings[$row['id']])){
                  $rating  = $ratings[$row['id']];
                    echo "<div>Ocjena: $rating/5</div>";
                  }
                 ?>  
            </figure>

            <div class="lightbox" id="img<?php echo $row['id']; ?>">
                <a href="#" class="close">×</a>
                <img src="<?php echo $row['source']; ?>" alt="<?php echo $row['name']; ?>" />
                <div>
                    <form action="slike.php" method="POST">
                        <input type="hidden" name="slika_id" value="<?php echo $row['id']; ?>" />
                        <div>
                            <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    $checked = (isset($ratings[$row['id']]) && $ratings[$row['id']] == $i) ? 'checked' : '';
                                    echo "<label><input type='radio' name='ocjena' value='$i' $checked />$i</label> ";
                                }
                            ?>
                        </div>
                        <button type="submit" name="rate">Submit Rating</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Nema slika za prikaz.</p>
    <?php endif; ?>
</section>

</body>
</html>
