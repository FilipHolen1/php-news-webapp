<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracija</title>
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
        <div class="admin-panel">

            <?php
            session_start();
            include 'connect.php';

            
            define('UPLPATH', 'img/');

            // login
            if (isset($_POST['prijava'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $stmt->bind_result($db_username, $db_password, $db_razina);
                $stmt->fetch();

                if (password_verify($password, $db_password)) {
                    $_SESSION['username'] = $db_username;
                    $_SESSION['razina'] = $db_razina;
                } else {
                    echo "Neispravno korisničko ime ili lozinka.";
                }
                $stmt->close();
            }

            // logout
            if (isset($_POST['logout'])) {
                session_destroy();
                header("Location: administracija.php");
            }

            // provjera razine prava za korisnika
            if (isset($_SESSION['username']) && $_SESSION['razina'] == 1) {
                echo '<a href="unos.html"><button>Dodaj novu vijest</button></a>';

                
                $query = "SELECT * FROM vijesti";
                $result = mysqli_query($db, $query);

                while($row = mysqli_fetch_array($result)) {
                    echo '<form enctype="multipart/form-data" action="" method="POST">';
                    echo '<div class="form-item">';
                    echo '<label for="title">Naslov vijesti:</label>';
                    echo '<div class="form-field">';
                    echo '<input type="text" name="title" class="form-field-textual" value="'.$row['naslov'].'">';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<label for="about">Kratki sadržaj vijesti (do 50 znakova):</label>';
                    echo '<div class="form-field">';
                    echo '<textarea name="about" cols="30" rows="3" class="form-field-textual">'.$row['sazetak'].'</textarea>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<label for="content">Sadržaj vijesti:</label>';
                    echo '<div class="form-field">';
                    echo '<textarea name="content" cols="70" rows="16" class="form-field-textual">'.$row['tekst'].'</textarea>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<label for="slika">Slika:</label>';
                    echo '<br>';
                    echo '<img src="' . UPLPATH . $row['slika'] . '" width="500px">';
                    echo '<br>';
                    echo '<div class="form-field">';
                    echo '<input type="file" class="input-text" id="slika" name="slika"/> <br>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<label for="category">Kategorija vijesti:</label>';
                    echo '<div class="form-field">';
                    echo '<select name="category" class="form-field-textual">';
                    echo '<option value="sport"'.($row['kategorija']=='sport' ? ' selected' : '').'>Sport</option>';
                    echo '<option value="kultura"'.($row['kategorija']=='kultura' ? ' selected' : '').'>Kultura</option>';
                    echo '</select>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<label>Spremiti u arhivu:</label>';
                    echo '<div class="form-field">';
                    if($row['arhiva'] == 0) {
                        echo '<input type="checkbox" name="archive" id="archive"/> Arhiviraj?';
                    } else {
                        echo '<input type="checkbox" name="archive" id="archive" checked/> Arhiviraj?';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<input type="hidden" name="id" class="form-field-textual" value="'.$row['id'].'">';
                    echo '<button type="reset" value="Poništi">Poništi</button>';
                    echo '<button type="submit" name="update" value="Prihvati">Izmjeni</button>';
                    echo '<button type="submit" name="delete" value="Izbriši">Izbriši</button>';
                    echo '<br><hr><br>';
                    echo '</div>';
                    echo '</form>';
                }
                echo '<form method="post" action=""><button type="submit" name="logout">Odjavi se</button></form>';

                // Brisanje vijesti
                if(isset($_POST['delete'])){
                    $id=$_POST['id'];
                    $query = "DELETE FROM vijesti WHERE id=?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param('i', $id);
                    $result = $stmt->execute();
                    if($result) {
                        echo '<p class="success-message">Vijest je uspješno izbrisana.</p>';
                    } else {
                        echo '<p class="error-message">Greška prilikom brisanja vijesti.</p>';
                    }
                    $stmt->close();
                }

                // Ažuriranje vijesti
                if (isset($_POST['update'])) {
                    $id = $_POST['id'];
                    $title = $_POST['title'];
                    $about = $_POST['about'];
                    $content = $_POST['content'];
                    $category = $_POST['category'];
                    $archive = isset($_POST['archive']) ? 1 : 0;

                    if ($_FILES['slika']['name'] != "") {
                        $picture = $_FILES['slika']['name'];
                        $target_dir = 'img/' . $picture;
                        move_uploaded_file($_FILES["slika"]["tmp_name"], $target_dir);
                        //bez slike
                        $query = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, slika=?, kategorija=?, arhiva=? WHERE id=?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('ssssssi', $title, $about, $content, $picture, $category, $archive, $id);
                    } else {
                        // sa slikom
                        $query = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, kategorija=?, arhiva=? WHERE id=?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('ssssii', $title, $about, $content, $category, $archive, $id);
                    }

                    if ($stmt->execute()) {
                        echo '<p class="success-message">Vijest je uspješno ažurirana.</p>';
                    } else {
                        echo '<p class="error-message">Greška prilikom ažuriranja vijesti.</p>';
                    }

                    $stmt->close();
                }
            } else if (isset($_SESSION['username']) && $_SESSION['razina'] != 1) {
                echo "Bok " . $_SESSION['username'] . "! Uspješno ste prijavljeni, ali niste administrator.";
                echo '<form method="post" action=""><button type="submit" name="logout">Odjavi se</button></form>';
            } else {
            ?>
                <form method="post" action="">
                    Korisničko ime: <input type="text" name="username" required><br><br>
                    Lozinka: <input type="password" name="password" required><br><br>
                    <button type="submit" name="prijava">Prijavi se</button>
                    <a href="registracija.php"><button type="button">Registriraj se</button></a>
                </form>
            <?php
            }
            ?>
        </div>
    </main>

    <footer >
    <p>&copy; 2024 Filip Holen | fholen@tvz.hr</p>
    </footer>

</body>
</html>
