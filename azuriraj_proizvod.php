<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ažuriraj proizvod</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Azuriraj proizvod">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">    
    </head>

    <body>
        <?php
        include("sesija.class.php");
        Sesija::kreirajSesiju();

        if (!isset($_SESSION["korisnik"])) {
            die("Niste prijavljeni pa ne možete pristupiti stranici!");
        } else if ($_SESSION["tip"] == 3) {
            echo "Administrator: " . $_SESSION["korisnik"] . "<br>";
        } else if ($_SESSION["tip"] == 2) {
            echo "Moderator: " . $_SESSION["korisnik"] . "<br>";
        } else {
            die("Niste administrator/moderator pa ne možete pristupiti stranici!");
        }
        ?>
        <header>
            <h1>Ažuriraj proizvod</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.php" alt="logo" shape="rect" coords="0,0,200,200" target="index" />
                    <area href="#prijava_nav" alt="logo" shape="rect" coords="200,0,400,200" />
                </map>
                <figcaption id="logoCap">Interaktivna slika</figcaption>
            </figure>
        </header>

        <nav id="prijava_nav">
            <ul>
                <?php
                if (isset($_SESSION["korisnik"])) {
                    echo '<li><a href="index.php"> Početna</a></li>';
                }
                ?>

                <li><a href="registracija.php"> Registracija</a></li>
                <li><a href="prijava.php"> Prijava</a></li>

                <?php
                if (isset($_SESSION["korisnik"])) {
                    echo '<li><a href="novi_proizvod.php"> Novi proizvod</a></li>';
                }
                ?>

                <li><a href="dnevnik.php"> Dnevnik</a></li>

                <?php
                if (isset($_SESSION["korisnik"]) && $_SESSION["tip"] == 3) {
                    echo '<li><a href="otkljucavanje_korisnika.php"> Otključavanje korisnika</a></li>';
                }
                ?>

                <li><a href="aktivacija.php"> Aktivacija</a></li>

                <?php
                if (isset($_SESSION["korisnik"]) && ($_SESSION["tip"] == 3 || $_SESSION["tip"] == 2)) {
                    echo '<li><a href="azuriraj_proizvod.php"> Ažuriraj proizvod</a></li>';
                }
                ?>
                
                <?php
                if (isset($_SESSION["korisnik"])) {
                    echo '<li><a href="odjava.php"> Odjava</a></li>';
                }
                ?>
            </ul> 
        </nav>

        <div class="azuriraj_proizvod">
            <?php
            if (isset($_POST["azurirajSpremi"])) {
                include_once("baza.class.php");
                $bp = new Baza();

                $id = $_POST["id"];
                $naziv = $_POST["naziv"];
                $opis = $_POST["opis"];
                $datum = $_POST["datum"];
                $vrijeme = $_POST["vrijeme"];
                $kolicina = $_POST["kolicina"];
                $tezina = $_POST["tezina"];
                $kategorija = $_POST["kategorija"];

                $sql = "UPDATE proizvod SET naziv='$naziv', opis='$opis', datum='$datum', vrijeme='$vrijeme', kolicina='$kolicina', tezina='$tezina', kategorija='$kategorija' WHERE proizvod_id='$id'";

                $bp->spojiDB();
                $rs = $bp->ostaliUpitiDB($sql);

                if ($bp->pogreskaDB()) {
                    echo "Problem kod upita na bazu podataka!";
                    exit;
                }
                $bp->zatvoriDB();
                echo "Uspješno ažuriran proizvod!<br>";
            }

            if (isset($_POST["proizvod"])) {
                include_once("baza.class.php");
                $bp = new Baza();

                $id = $_POST["proizvod"];
                $sql = "SELECT * FROM `proizvod` WHERE proizvod_id='$id'";

                $bp->spojiDB();
                $rs = $bp->selectDB($sql);

                if ($bp->pogreskaDB()) {
                    echo "Problem kod upita na bazu podataka!";
                    exit;
                }

                echo '<form action="azuriraj_proizvod.php" method="post">';

                while (list($id, $naziv, $opis, $datum, $vrijeme, $kolicina, $tezina, $kategorija) = $rs->fetch_array()) {
                    echo '<label class="azurirajLabele" for=' . $id . '>Id:</label>';
                    echo "<input type='text' id='$id' name='id' value='$id' readonly=" . 'readonly' . "><br>";

                    echo '<label class="azurirajLabele" for=' . $naziv . '>Naziv:</label>';
                    echo "<input type='text' id='$naziv' name='naziv' value='$naziv'><br>";

                    echo '<label class="azurirajLabele" for=' . $opis . '>Opis:</label>';
                    echo "<input type='text' id='$opis' name='opis' value='$opis'><br>";

                    echo '<label class="azurirajLabele" for=' . $datum . '>Datum:</label>';
                    echo "<input type='text' id='$datum' name='datum' value='$datum'><br>";

                    echo '<label class="azurirajLabele" for=' . $vrijeme . '>Vrijeme:</label>';
                    echo "<input type='text' id='$vrijeme' name='vrijeme' value='$vrijeme'><br>";

                    echo '<label class="azurirajLabele" for=' . $kolicina . '>Količina:</label>';
                    echo "<input type='text' id='$kolicina' name='kolicina' value='$kolicina'><br>";

                    echo '<label class="azurirajLabele" for=' . $tezina . '>Težina:</label>';
                    echo "<input type='text' id='$tezina' name='tezina' value='$tezina'><br>";

                    echo '<label class="azurirajLabele" for=' . $kategorija . '>Kategorija:</label>';
                    echo "<input type='text' id='$kategorija' name='kategorija' value='$kategorija'><br>";
                }
                $bp->zatvoriDB();
                echo '<input type="submit" id="posaljiPrijavu" name="azurirajSpremi" value="Spremi">';
                echo "</form>";
            }
            ?>
        </div>

        <div class="popis_proizvoda_azuriraj">            
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <?php
                include_once("baza.class.php");
                $bp = new Baza();
                $sql = "SELECT * FROM `proizvod`";

                $bp->spojiDB();
                $rs = $bp->selectDB($sql);

                if ($bp->pogreskaDB()) {
                    echo "Problem kod upita na bazu podataka!";
                    exit;
                }

                print "<table><tr><th>Odaberi</th><th>Naziv</th><th>Opis</th><th>Datum</th><th>Vrijeme</th><th>Količina</th><th>Težina</th><th>Kategorija</th></tr>\n";

                while (list($id, $naziv, $opis, $datum, $vrijeme, $kolicina, $tezina, $kategorija) = $rs->fetch_array()) {
                    print "<tr><td><input type='radio' id='$id' name='proizvod' value='$id' /></td><td>$naziv</td><td>$opis</td><td>$datum</td><td>$vrijeme</td><td>$kolicina</td><td>$tezina</td><td>$kategorija</td></tr>\n";
                }
                print "</table>\n";

                $bp->zatvoriDB();
                ?>
                <input type="submit" id="posaljiPrijavu" name="azuriraj" value="Ažuriraj proizvod">
            </form>
        </div>

        <footer>
            <p>Vrijeme potrebno za rješavanje aktivnog dokumenta: 3h </p>
            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_05%2Fnikfluks%2Fazuriraj_proizvod.php" target="html5">
                <figure id="html5">
                    <img src="slike/HTML5.png" alt="HTML5" width="100" height="100">
                    <figcaption>HTML5 validator</figcaption>
                </figure>
            </a>
            <a href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_05%2Fnikfluks%2Fcss%2Fnikfluks.css&profile=css3&usermedium=all&warning=1&vextwarning=&lang=en" target="css3">
                <figure id="css3">
                    <img src="slike/CSS3.png" alt="CSS3" width="100" height="100">
                    <figcaption>CSS3 validator</figcaption>
                </figure> 
            </a>
            <address id="mail"><strong>Kontakt: <a href="mailto:nikfluks@foi.hr">Nikola Fluks</a></strong></address>
            <p><small>&copy; 2017. N. Fluks </small></p>
        </footer>
    </body>
</html>

