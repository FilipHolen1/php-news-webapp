<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Page</title>
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
            $id = $_GET['id'];
        
            $query = "SELECT * FROM vijesti WHERE id=?";
            $stmt = mysqli_prepare($db, $query);
        
            if ($stmt === false) {
                die('MySQL prepare error: ' . mysqli_error($db));
            }
        
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
        
            $result = mysqli_stmt_get_result($stmt);
        
            if($row = mysqli_fetch_array($result)) {
                echo '<div class="clanak">';
                echo '<h2>' . ucfirst($row['kategorija']) . '</h2>';
                echo '<article>';
                echo '<h3>'.$row['naslov'].'</h3>';
                echo '<img src="' . UPLPATH . $row['slika'] . '" alt="' . $row['naslov'] . '">';
                echo '<div>';
                echo '<h4>'.$row['sazetak'].'</h4>';
                echo '<p>'.$row['tekst'].'</p>';
                echo '</div>';
                echo '</article>';
                echo '</div>';
            } else {
                echo 'Nema rezultata za traženi ID.';
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
