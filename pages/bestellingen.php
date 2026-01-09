<?php
// pages/bestellingen.php
// Onderdeel A: Overzicht van de gedane bestellingen

// Check of de gebruiker is ingelogd
if (!isset($_SESSION["ID"])) {
    echo "<script>location.href='index.php?page=inloggen';</script>";
    exit();
}

// Opgave 108 stijl: Query voorbereiden
$query = "SELECT * FROM orders ORDER BY date DESC";
$stmt = $verbinding->prepare($query);

try {
    // Query uitvoeren
    $stmt->execute(array()); // Lege array omdat we geen parameters hebben
    
    // Opgave 108 stijl: Fetch mode instellen
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    echo "<script>alert('Fout bij ophalen bestellingen: " . $e->getMessage() . "');</script>";
    $stmt = []; // Lege array om fouten in foreach te voorkomen
}
?>

<div class="content">
    <h1>Bestellingen Overzicht</h1>
    <p>Hieronder vindt u een overzicht van alle bestellingen.</p>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Datum</th>
                <th>Klantnaam (Ontvanger)</th>
                <th>Land</th>
                <th>Status</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Opgave 108 stijl: Direct loopen door het statement object
            foreach($stmt as $bestelling) {
                ?>
                <tr>
                    <td><?php echo $bestelling['id']; ?></td>
                    <td><?php echo $bestelling['date']; ?></td>
                    <td><?php echo $bestelling['recipient']; ?></td>
                    <td><?php echo $bestelling['country']; ?></td>
                    <td>
                        <?php 
                        if(empty($bestelling['status'])) {
                            echo "Nieuw"; 
                        } else {
                            echo $bestelling['status'];
                        }
                        ?>
                    </td>
                    <td>
                        <a href="index.php?page=bestelling_wijzigen&id=<?php echo $bestelling['id']; ?>" 
                           style="background-color: #0056b3; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;">
                           Bewerken
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>