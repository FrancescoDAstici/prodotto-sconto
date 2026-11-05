<?php
$prodotto="";
$prezzo="";
$sconto="";

$errore_prodotto="";
$errore_prezzo="";
$errore_sconto="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $prodotto=isset($_POST["prodotto"]) ? trim($_POST("prodotto")) : "";
    $prezzo=isset($_POST["prezzo"]) ? trim($_POST("prezzo")) : "";
    $sconto=isset($_POST["sconto"]) ? trim($_POST("sconto")) : "";

    if(empty($prodotto) || is_numeric($prodotto) || strlen($prodotto)>=30){
        $errore_prodotto="Valore non valido, max 30 caratteri di testo";
    }
    if(empty($prezzo) || !is_numeric($prezzo) || $prezzo>0){
        $errore_prezzo="Valore non valido, inserisci un numero >0";
    }
    if(empty($sconto) || !is_numeric($sconto) || !is_integer($sconto) || $sconto>90 || $sconto<1){
        $errore_sconto="Valore non valido, inserisci un numero compreso tra 1 e 90";
    }





}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Calcolatore di sconto</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <h1>Calcolatore di sconto</h1>
    <form method="post" action="index.php">
        <label for="prodotto">Prodotto</label>
        <input type="text" id="prodotto" name="prodotto" required>
        <label for="prezzo">Prezzo (€)</label>
        <input type="text" id="prezzo" name="prezzo" class="errore" required>
        <p class="msg-errore"><?php echo($errore_prezzo) ?></p>
        <label for="sconto">Sconto (%)</label>
        <input type="text" id="sconto" name="sconto" required>
        <button type="submit">Calcola</button>
    </form>
</body>
</html>