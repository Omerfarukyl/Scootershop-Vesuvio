<?php
// index.php
// Het centrale punt van de applicatie (Front Controller)
// Bevat Onderdeel F: Beveiliging (Toegangscontrole op basis van rollen)

session_start();
include_once("DBconfig.php");

// 1. WELKE PAGINA WORDT GEVRAAGD?
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    // Standaard pagina is inloggen
    $page = "inloggen";
}

// 2. BEVEILIGING & LOGIN CHECK (Onderdeel F)
// Als de pagina NIET inloggen of uitloggen is, checken of we zijn ingelogd
if ($page != "inloggen" && $page != "uitloggen" && !isset($_SESSION["ID"])) {
    header("Location: index.php?page=inloggen");
    exit();
}

// Uitloggen pagina moet direct worden uitgevoerd (zonder HTML wrapper)
if ($page == "uitloggen") {
    if (file_exists("pages/uitloggen.php")) {
        include("pages/uitloggen.php");
    }
    exit();
}

// 3. ROL-GEBASEERDE TOEGANGSCONTROLE (Wie mag waarheen?)
if (isset($_SESSION["ROL"])) {
    $rol = $_SESSION["ROL"];

    // A. Regels voor Magazijn medewerkers
    if ($rol == "magazijn") {
        // Magazijn mag ALLEEN naar: magazijn, inloggen, uitloggen
        $toegestaan = array("magazijn", "inloggen", "uitloggen");
        
        if (!in_array($page, $toegestaan)) {
            echo "<script>alert('Toegang geweigerd! U heeft geen rechten voor deze pagina.'); location.href='index.php?page=magazijn';</script>";
            exit();
        }
    }

    // B. Regels voor Verzending medewerkers
    if ($rol == "verzending") {
        // Verzending mag ALLEEN naar: verzending, inloggen, uitloggen
        // (Etiket wordt via bibliotheek gegenereerd, dat gaat buiten index om)
        $toegestaan = array("verzending", "inloggen", "uitloggen");
        
        if (!in_array($page, $toegestaan)) {
            echo "<script>alert('Toegang geweigerd! U heeft geen rechten voor deze pagina.'); location.href='index.php?page=verzending';</script>";
            exit();
        }
    }
    
    // C. Management mag ALLES zien (geen restricties)
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Scootershop Vesuvio</title>
    <link rel="stylesheet" href="css/webshop.css">
</head>
<body>

    <?php include("header.php"); ?>

    <?php
    // Controleer of het bestand bestaat in de map 'pages'
    if (file_exists("pages/" . $page . ".php")) {
        include("pages/" . $page . ".php");
    } else {
        echo "<div class='content'><p style='color:red;'>De pagina <strong>$page</strong> bestaat niet.</p></div>";
    }
    ?>


</body>
</html>