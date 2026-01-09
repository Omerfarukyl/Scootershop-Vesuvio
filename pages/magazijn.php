<?php

if (!isset($_SESSION["ID"])) {
    echo "<script>location.href='index.php?page=inloggen';</script>";
    exit();
}

$melding = "";


if (isset($_POST['inpakken'])) {
    $regel_id = $_POST['regel_id'];
    
    $query = "UPDATE orderrules SET packed = 1 WHERE id = :id";
    $stmt = $verbinding->prepare($query);
    
    $data = array("id" => $regel_id);
    
    try {
        $stmt->execute($data);
        $melding = "Item succesvol ingepakt.";
    } catch(PDOException $e) {
        $melding = "Fout: " . $e->getMessage();
    }
}



$sql_filter = "SELECT DISTINCT order_id FROM orderrules WHERE packed = 0";
$stmt_filter = $verbinding->prepare($sql_filter);
$stmt_filter->execute(array());
$open_orders = $stmt_filter->fetchAll(PDO::FETCH_COLUMN);

if (empty($open_orders)) {
    $order_ids_string = "0";
} else {
   
    $order_ids_string = implode(',', $open_orders);
}
?>

<div class="content">
    <h1>Magazijn - Order Picker</h1>
    <p>Hieronder staan de bestellingen die nog ingepakt moeten worden.</p>

    <?php if ($melding != "") { echo "<p style='color: green;'>$melding</p>"; } ?>

    <?php

    if ($order_ids_string != "0") {
        
        $query = "SELECT orderrules.id as regel_id, orderrules.order_id, orderrules.packed, parts.part 
                  FROM orderrules 
                  INNER JOIN parts ON orderrules.part_id = parts.id 
                  WHERE orderrules.order_id IN ($order_ids_string) 
                  ORDER BY orderrules.order_id ASC";
                  
        $stmt = $verbinding->prepare($query);
        $stmt->execute(array());
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 


        $huidige_order = 0;
        
        echo "<table>";
        echo "<thead><tr><th>Order Nr</th><th>Onderdeel</th><th>Status</th><th>Actie</th></tr></thead>";
        echo "<tbody>";

        foreach($stmt as $rij) {
      
            ?>
            <tr style="<?php if($rij['packed'] == 1) { echo 'background-color: #e6fffa; color: #aaa;'; } ?>">
                <td><strong>#<?php echo $rij['order_id']; ?></strong></td>
                <td><?php echo $rij['part']; ?></td>
                <td>
                    <?php 
                    if ($rij['packed'] == 1) {
                        echo "✔ Ingepakt";
                    } else {
                        echo "<span style='color: red;'>Nog inpakken</span>";
                    }
                    ?>
                </td>
                <td>
                    <?php if ($rij['packed'] == 0) { ?>
                        <form method="post" action="">
                            <input type="hidden" name="regel_id" value="<?php echo $rij['regel_id']; ?>">
                            <input type="submit" name="inpakken" value="Inpakken" 
                                   style="background-color: orange; border: none; padding: 5px 10px; cursor: pointer;">
                        </form>
                    <?php } else { ?>
                        <small>Klaar</small>
                    <?php } ?>
                </td>
            </tr>
            <?php
        }
        echo "</tbody></table>";
        
    } else {
        echo "<p><strong>Geweldig! Alle bestellingen zijn ingepakt. Het magazijn is leeg.</strong></p>";
    }
    ?>
</div>