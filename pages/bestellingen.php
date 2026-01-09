<?php
if (!isset($_SESSION["ID"])) {
    echo "<script>location.href='index.php?page=inloggen';</script>";
    exit();
}

$query = "SELECT * FROM orders ORDER BY date DESC";
$stmt = $verbinding->prepare($query);

try {
    $stmt->execute(array());
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    echo "<script>alert('Fout bij ophalen bestellingen: " . $e->getMessage() . "');</script>";
    $stmt = [];
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