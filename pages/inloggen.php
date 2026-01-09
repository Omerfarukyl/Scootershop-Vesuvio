<?php


if (isset($_POST["submit"])) {
    $gebruikersnaam = trim($_POST["username"]);
    $wachtwoord = $_POST["password"];
    $melding = "";

    try {
        if (!isset($verbinding)) {
            $melding = "Database verbinding niet gevonden.";
        } else {
            $sql = "SELECT * FROM users WHERE username = :user";
            $stmt = $verbinding->prepare($sql);
            $stmt->bindParam(':user', $gebruikersnaam);
            $stmt->execute();

            $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($gebruiker && password_verify($wachtwoord, $gebruiker["password"])) {
                $_SESSION["ID"] = $gebruiker["id"];
                $_SESSION["USER_NAAM"] = $gebruiker["full_name"];
                $_SESSION["ROL"] = $gebruiker["role"]; 
                $_SESSION["STATUS"] = "ACTIEF";

                header("Location: index.php?page=bestellingen");
                exit();
            } else {
                $melding = "Onjuiste gebruikersnaam of wachtwoord.";
            }
        }
    } catch (PDOException $e) {
        $melding = "Er is een fout opgetreden: " . $e->getMessage();
    }
}
?>

<div class="content">
    <h1>Inloggen</h1>
    
    <?php 
    if (isset($melding) && $melding != "") {
        echo "<p style='color: red;'>" . $melding . "</p>";
    }
    ?>

    <form action="index.php?page=inloggen" method="post">
        <p>
            <label for="username">Gebruikersnaam:</label><br>
            <input type="text" name="username" id="username" required>
        </p>
        <p>
            <label for="password">Wachtwoord:</label><br>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <input type="submit" name="submit" value="Inloggen">
        </p>
    </form>
</div>