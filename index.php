<?php

//VALORI INSERITI DALL'UTENTE
$prodotto="";
$prezzo="";
$sconto="";

//MESSAGGI DI ERRORE OUTPUTTATI NEL PARAGRAFO HTML
$errore_prodotto="";
$errore_prezzo="";
$errore_sconto="";

//FLAG PER ASSEGNAZIONE CLASSE ERRORE
$flagErrA="";
$flagErrB="";
$flagErrC="";

//VALORI DA CALCOLARE
$scontoeur=0;
$prezzoScontato=0;

//CLASSE DELLA CARD E RISPETTIVO TESTO
$classe_card = "";
$testo_fascia = "";


if($_SERVER["REQUEST_METHOD"]=="POST"){                                     //CONTROLLO APERTURA IN POST
    $prodotto=isset($_POST["prodotto"]) ? trim($_POST["prodotto"]) : "";    //CONDIZIONE ? SE VERA : SE FALSA
    $prezzo=isset($_POST["prezzo"]) ? trim($_POST["prezzo"]) : "";
    $sconto=isset($_POST["sconto"]) ? trim($_POST["sconto"]): "";

    // CONTROLLI SUI VALORI INSERITI
    if(empty($prodotto) || is_numeric($prodotto) || strlen($prodotto)>30){
        $errore_prodotto="Valore non valido, max 30 caratteri di testo";
    }
    if(empty($prezzo) || !is_numeric($prezzo) || $prezzo<=0){
        $errore_prezzo="Valore non valido, inserisci un numero >0";
    }
    if(empty($sconto) || !is_numeric($sconto) || $sconto != floor($sconto) || $sconto>90 || $sconto<1){  //SE IL VALORE APPROSSIMATO (floor) E' DIVERSO DA ESSO IL NUMERO NON E' INTERO 
        $errore_sconto="Valore non valido, inserisci un numero intero compreso tra 1 e 90";
    }


    //CONTROLLO ED EVENTUALE ASSEGNAZIONE DELLA CLASSE ERRORE
    $flagErrA=!empty($errore_prodotto) ? "errore" : "";
    $flagErrB=!empty($errore_prezzo) ? "errore" : "";
    $flagErrC=!empty($errore_sconto) ? "errore" : "";

    //CALCOLI VARI SULLO SCONTO
    $scontoeur=($sconto/100)*$prezzo;
    $prezzoScontato=$prezzo-$scontoeur;

    //DETERMINAZIONE CLASSE CARD E TESTO DELLA FASCIA IN BASE ALLO SCONTO EFFETTUATO
    if ($sconto < 20) {
        $classe_card = "piccolo";
        $testo_fascia = "PICCOLO";
    } elseif ($sconto >= 20 && $sconto <= 49) {
        $classe_card = "buono";
        $testo_fascia = "BUONO";
    } else{
        $classe_card = "super";
        $testo_fascia = "SUPER";
    }

    //SALVATAGGIO SUL FILE
    $nf="log.csv";
    $file = fopen($nf, "a");

    if($file)
    {
        $record = date("d/m/Y");
        $record .= ", ";
        $record .= date("g:i:s");
        $record .= ", ";
        $record .= $_SERVER['REMOTE_ADDR'];
        $record .= ", ";
        $record .= $prodotto;
        $record .= ", ";
        $record .= $prezzo;
        $record .= ", ";
        $record .= $sconto;
        $record .= ", ";
        $record .= $scontoeur;
        $record .= ", ";
        $record .= $prezzoScontato;
        $record .= ", ";
        $record .= $classe_card;
        $record .= ";\n";
        
        fwrite($file, $record);
        fclose($file);

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
        <input type="text" id="prodotto" name="prodotto" class="<?php echo($flagErrA)?>" value="<?php echo($prodotto)?>" required>
        <p class="msg-errore"><?php echo($errore_prodotto) ?></p>
        <label for="prezzo">Prezzo (€)</label>
        <input type="text" id="prezzo" name="prezzo" class="<?php echo($flagErrB)?>" value="<?php echo($prezzo)?>" required>
        <p class="msg-errore"><?php echo($errore_prezzo) ?></p>
        <label for="sconto">Sconto (%)</label>
        <input type="text" id="sconto" name="sconto" class="<?php echo($flagErrC)?>" value="<?php echo($sconto)?>" required>
        <p class="msg-errore"><?php echo($errore_sconto) ?></p>
        <button type="submit">Calcola</button>
    </form>

    <div class="container">
        <div class="card-box">
            <!-- La card cambia colore in base alla classe dinamica inserita con il PHP -->
            <div class="card <?php echo $classe_card; ?>">
                <div class="prezzo-fin">Prezzo finale: <?php echo number_format($prezzoScontato,2);?>€</div>
                <div class="risparmio">Risparmiato: <?php echo number_format($scontoeur,2); ?>€</div>
                <div class="fascia"><?php echo $testo_fascia; ?></div>
            </div>
        </div>
    </div>

</body>
</html>