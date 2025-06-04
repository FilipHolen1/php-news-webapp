
    <?php
    $db_host = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "PWA_vijesti";
    $db_port = 3307;

    $db = new mysqli($db_host, $db_username, $db_password, $db_name, $db_port);

    if ($db->connect_error) {
        die("Konekcija nije uspjela: " . $db->connect_error);
    }
    ?>