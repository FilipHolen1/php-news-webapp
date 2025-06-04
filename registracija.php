<?php
include 'connect.php';

if (isset($_POST['register'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $razina = 0; //razinu 1 ima samo (username: admin, pswd: adminpass)

    $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ssssi', $ime, $prezime, $username, $password, $razina);
    if ($stmt->execute()) {
        echo "Registracija uspješna!";
    } else {
        echo "Greška: " . $stmt->error;
    }
    $stmt->close();
}
?>
<link rel="stylesheet" href="style.css">
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
<form method="post" action="" style="padding: 15px">
    Ime: <input type="text" name="ime" required><br><br>
    Prezime: <input type="text" name="prezime" required><br><br>
    Korisničko ime: <input type="text" name="username" required><br><br>
    Lozinka: <input type="password" name="password" required><br><br>
    <button type="submit" name="register">Registriraj se</button>
</form>

<footer style="margin-top: 488px;">
    <p>&copy; 2024 Filip Holen | fholen@tvz.hr</p>
</footer>
