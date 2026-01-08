<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Scootershop Vesuvio</title>
    <link rel="stylesheet" href="css/webshop.css">
    <script>
    function uitloggen() {
        var loguit = confirm('Weet u zeker dat u wilt uitloggen?');
        if(loguit) {
            location.href = 'index.php?page=uitloggen';
        }
    }
    </script>
</head>
<body>
    <div class="header">
        <div class="icon_container">
            <div class="icon">&#x266C;</div> 
            <h2>Vesuvio</h2>
        </div>
        
        <div class="user-info">
            <?php
            if(isset($_SESSION["ID"]) && $_SESSION["STATUS"] == "ACTIEF") {
                echo "Gebruiker: " . $_SESSION["USER_NAAM"];
                echo " | <a href='#' onclick='uitloggen()'>Uitloggen</a>";
            }
            ?>
        </div>
    </div>