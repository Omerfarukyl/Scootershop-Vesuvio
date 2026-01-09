<?php
if (!isset($_GET['id'])) {
    echo "<script>location.href='index.php?page=bestellingen';</script>";
    exit();
}

$id = $_GET['id'];
$melding = "";

if (isset($_POST['opslaan'])) {
    $query = "UPDATE orders 
              SET recipient = :recipient, 
                  addressline1 = :adres1, 
                  addressline2 = :adres2, 
                  country = :country 
              WHERE id = :id";
    
    $update = $verbinding->prepare($query);
    $data = array(
        "recipient" => $_POST['recipient'],
        "adres1" => $_POST['addressline1'],
        "adres2" => $_POST['addressline2'],
        "country" => $_POST['country'],
        "id" => $id
    );

    try {
        $update->execute($data);
        $melding = "Gegevens succesvol opgeslagen!";
    } catch (PDOException $e) {
        $melding = "Fout bij opslaan: " . $e->getMessage();
    }
}

$query = "SELECT * FROM orders WHERE id = :id";
$stmt = $verbinding->prepare($query);
$stmt->execute(array("id" => $id));
$bestelling = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bestelling) {
    echo "Bestelling niet gevonden.";
    exit();
}

$query_prod = "SELECT orderrules.*, parts.part, parts.sell_price 
               FROM orderrules 
               INNER JOIN parts ON orderrules.part_id = parts.id 
               WHERE orderrules.order_id = :id";
$stmt_prod = $verbinding->prepare($query_prod);
$stmt_prod->execute(array("id" => $id));

$stmt_prod->setFetchMode(PDO::FETCH_ASSOC);
?>

<div class="content">
    <h1>Bestelling Bewerken (#<?php echo $bestelling['id']; ?>)</h1>
    
    <p>
        <a href="index.php?page=bestellingen">&laquo; Terug naar overzicht</a>
    </p>

    <?php if ($melding != "") { echo "<p style='color: green; font-weight: bold;'>$melding</p>"; } ?>

    <form method="post" action="">
        <h3>Klantgegevens</h3>
        
        <label>Naam Ontvanger:</label>
        <input type="text" name="recipient" value="<?php echo $bestelling['recipient']; ?>" required>

        <label>Adresregel 1:</label>
        <input type="text" name="addressline1" value="<?php echo $bestelling['addressline1']; ?>" required>

        <label>Adresregel 2:</label>
        <input type="text" name="addressline2" value="<?php echo $bestelling['addressline2']; ?>" required>

        <label>Land:</label>
        <input type="text" name="country" value="<?php echo $bestelling['country']; ?>" required>

        <input type="submit" name="opslaan" value="Opslaan">
    </form>

    <hr>

    <h3>Bestelde Artikelen</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Prijs</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($stmt_prod as $regel) { ?>
            <tr>
                <td><?php echo $regel['part']; ?></td>
                <td>&euro; <?php echo $regel['sell_price']; ?></td>
                <td>
                    <?php 
                    if ($regel['packed'] == 1) {
                        echo "<span style='color: green;'>Ingepakt</span>";
                    } else {
                        echo "<span style='color: orange;'>Nog niet ingepakt</span>";
                    }
                    ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>