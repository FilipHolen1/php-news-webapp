<?php
include 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naslov = isset($_POST['naslov']) ? $_POST['naslov'] : '';
    $sazetak = isset($_POST['sazetak']) ? $_POST['sazetak'] : '';
    $tekst = isset($_POST['tekst']) ? $_POST['tekst'] : '';
    $kategorija = isset($_POST['kategorija']) ? $_POST['kategorija'] : '';
    if(isset($_POST['arhiva'])){
        $arhiva=1;
        }else{
        $arhiva=0;
        }

    $datum=date("d.m.Y H:i");

    $slika = $_POST['slika'];
}

$query = "INSERT INTO Vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva)
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($query);
$stmt->bind_param("ssssssi", $datum, $naslov, $sazetak, $tekst, $slika, $kategorija, $arhiva);

if ($stmt->execute()) {
    echo "Record inserted successfully.";
} else {
    echo "Error: " . $db->error;
}

$stmt->close();
$db->close();

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pregled vijesti</title>
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
    <section role="main" class="clanak">
            <div class="row">
                <p><?php echo $kategorija; ?></p>
                <h1><?php echo $naslov; ?></h1>
                <p>OBJAVLJENO: <?php echo date("d.m.Y H:i"); ?></p>
                <p>ARHIVA: <?php echo htmlspecialchars($arhiva); ?></p>
            </div>
            <article>
            <?php
           
            echo "<img src='img/$slika'";
                ?>
            </article>
            <section class="">
                <h4><?php echo $sazetak; ?></h4>
            </section>
            <section class="">
                <p><?php echo nl2br(htmlspecialchars($tekst)); ?></p>
             </section>
    </section>
</main>
    <footer>
        <p>&copy; 2024 Filip Holen | fholen@tvz.hr</p>
    </footer>
</body>
</html>
