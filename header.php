<?php
// pages/header.php
// De navigatiebalk die zich aanpast aan de gebruiker (Onderdeel F)
?>

<div class="header">
    <div class="logo">
        <h2>Scootershop Vesuvio</h2>
    </div>

    <div class="nav">
        <?php
        if (isset($_SESSION["ID"])) {
            $rol = $_SESSION["ROL"];

            // MENU VOOR MANAGEMENT (Alles)
            if ($rol == "management") {
                echo '<a href="index.php?page=bestellingen">Bestellingen</a>';
                echo '<a href="index.php?page=management">Rapportage</a>';
                echo '<a href="index.php?page=personeel">Personeel</a>';
                // Management mag ook even in het magazijn kijken als ze willen
                echo '<a href="index.php?page=magazijn">Magazijn</a>';
                echo '<a href="index.php?page=verzending">Verzending</a>';
            }

            // MENU VOOR MAGAZIJN (Alleen Magazijn)
            if ($rol == "magazijn") {
                echo '<a href="index.php?page=magazijn">Magazijn Overzicht</a>';
            }

            // MENU VOOR VERZENDING (Alleen Verzending)
            if ($rol == "verzending") {
                echo '<a href="index.php?page=verzending">Verzending Overzicht</a>';
            }
        }
        ?>
    </div>

    <div class="user-info">
        <?php
        if (isset($_SESSION["ID"])) {
            echo "Ingelogd als: <strong>" . $_SESSION["USER_NAAM"] . "</strong> (" . ucfirst($_SESSION["ROL"]) . ")";
            echo " | <a href='index.php?page=uitloggen' style='color: #ffcccc;'>Uitloggen</a>";
        } else {
            echo "Niet ingelogd";
        }
        ?>
    </div>
</div>