<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <a href="index.php"><div class="logo">BRZE VIJESTI</div></a>
        <nav>
            <ul>
                <li><a href="index.php">Naslovna</a></li>
                <li><a href="kategorija.php?id=sport">Sport</a></li>
                <li><a href="kategorija.php?id=kultura">Kultura</a></li>
                <li><a href="administracija.php">Administracija</a></li>
            </ul>
        </nav>
    </header>
    <main>
    <?php
include 'connect.php';
define('UPLPATH', 'img/');

if(isset($_GET['id'])) {
    $category = $_GET['id'];

    // ne sortira po datumu, tj. pokazuje clanke po redu kako su dodavani 
    $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija=?";
    $stmt = mysqli_prepare($db, $query);

    if ($stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($db));
    }
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) > 0) {
        echo "<section class='category $category' id='$category'>";
        echo "<h2>" . ucfirst($category) . "</h2>";
        while($row = mysqli_fetch_array($result)) {
            echo '<article>';
            echo '<img src="' . UPLPATH . $row['slika'] . '" alt="' . $row['naslov'] . '">';
            echo '<div>';
            echo '<h3><a href="clanak.php?id='.$row['id'].'">'.$row['naslov'].'</a></h3>';
            echo '<p>'.$row['sazetak'].'</p>';
            echo '</div>';
            echo '</article>';
            echo "<hr>";
        }

        echo "</section>";
    } else {
        echo "<p>Nema članaka za kategoriju: " . ucfirst($category) . "</p>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($db);
?>

    </main>
    <footer>
        <p>&copy; 2024 Filip Holen | fholen@tvz.hr</p>
    </footer>
</body>
</html>
