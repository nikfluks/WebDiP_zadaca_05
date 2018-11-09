<!DOCTYPE html>

<html>
    <head>
        <title>Prijava</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Prijava">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">
    </head>

    <body>
        <?php
        include("baza.class.php");
        $bp = new Baza();
        include("sesija.class.php");
        Sesija::kreirajSesiju();

        /*if (isset($_GET["odjava"])) {
            Sesija::obrisiSesiju();
            unset($_SESSION["korisnik"]);
            echo "Brišem sesiju!";
        }*/

        if (isset($_POST["posaljiPrijavu"])) {
            $kor_ime = $_POST["korImePrijava"];
            $lozinka = $_POST["lozPrijava"];

            $sql = "SELECT * FROM korisnik WHERE `korisnicko_ime`='$kor_ime'";
            $bp->spojiDB();
            $rs = $bp->selectDB($sql);
            $odgovor = mysqli_fetch_array($rs);
            if (empty($kor_ime)) {
                echo "Korisničko ime nije uneseno!";
            } else if ($odgovor["korisnicko_ime"] == $kor_ime) {
                // echo "Korisnik postoji!";

                if ($odgovor["aktiviran"] == 0) {
                    echo "Korisnik nije aktiviran pa se ne može prijaviti!";
                } else if ($odgovor["broj_unosa"] >= 3) {
                    echo "Korisnik je zaključan pa se ne može prijaviti!";
                } else if ($odgovor["lozinka"] != $lozinka) {
                    echo "Pogrešna lozinka!<br>";
                    $brojPogresnihUnosa = $odgovor["broj_unosa"] + 1;
                    echo "Pogrešno ste se prijavili " . $brojPogresnihUnosa . ". put zaredom!<br>";
                    $sql = "UPDATE `korisnik` SET `broj_unosa`='$brojPogresnihUnosa'  WHERE `korisnicko_ime`='$kor_ime'";
                    $rs = $bp->ostaliUpitiDB($sql);
                } else {
                    if ($odgovor["prijava_2koraka"] == 0) {
                        echo "Prijavili ste se!";
                        $sql = "UPDATE `korisnik` SET `broj_unosa`='0'  WHERE `korisnicko_ime`='$kor_ime'";
                        $rs = $bp->ostaliUpitiDB($sql);

                        $tipKorisnika = $odgovor["tip_korisnika_id"];
                        $brojUnosa = 0;
                        Sesija::kreirajKorisnika($kor_ime, $tipKorisnika, $brojUnosa);
                        echo "Kreiram sesiju!";
                        header("Location:index.php");
                    } else {
                        echo "Imas prijavu u 2 koraka!<br>";
                        if ($_POST["tokenDaNe"] == 1) {//korisnik hoce novi token
                            $salt = sha1(time());
                            $token = sha1($salt);

                            echo "Slanje maila! <br>";
                            $mail_to = $odgovor["email"];
                            $mail_from = "From: nikfluks@zadaca04.hr";
                            $mail_subject = "Token";
                            $mail_body = "Vaš token: " . $token;

                            $sql = "UPDATE `korisnik` SET `token`='$token'  WHERE `korisnicko_ime`='$kor_ime'";
                            $rs = $bp->ostaliUpitiDB($sql);

                            echo $mail_body . "<br>";

                            if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                                echo("Poslana poruka za: '$mail_to'!");
                            } else {
                                echo("Problem kod poruke za: '$mail_to'!");
                            }
                        } else {//korisnik nece novi token
                            if ($odgovor["token"] == null) {
                                echo "Nemate kreirani token, morate ga kreirati!";
                            } elseif ($odgovor["token"] != $_POST["token"]) {
                                echo "Pogrešan token!";
                            } else {
                                echo "Prijavili ste se <br>";
                                $sql = "UPDATE `korisnik` SET `broj_unosa`='0'  WHERE `korisnicko_ime`='$kor_ime'";
                                $rs = $bp->ostaliUpitiDB($sql);

                                $tipKorisnika = $odgovor["tip_korisnika_id"];
                                $brojUnosa = 0;
                                Sesija::kreirajKorisnika($kor_ime, $tipKorisnika, $brojUnosa);
                                echo "Kreiram sesiju!";
                                header("Location:index.php");
                            }
                        }
                    }
                }
            } else {
                echo "Korisnik s unesenim korisničkim imenom ne postoji!";
            }
        }
        ?>
        <header>
            <h1>Prijava</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.php" alt="logo" shape="rect" coords="0,0,200,200" target="index" />
                    <area href="#korImeL" alt="logo" shape="rect" coords="200,0,400,200" />
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

        <section class="prijava">
            <h2>Prijava</h2>
            <form method="POST" name="prijava" id="prijava"
                  action="prijava.php" novalidate="">
                <label id="korImeL" for="korImePrijava">Korisničko ime:</label>
                <input type="text" id="korImePrijava" name="korImePrijava" size="20" placeholder="Korisničko ime" required="required" autofocus="autofocus"
                       <?php if (isset($_POST["korImePrijava"])){echo "value='".$_POST["korImePrijava"]."'";}?>><br>
                <label id="lozinkaL" for="lozPrijava">Lozinka:</label>
                <input type="password" id="lozPrijava" name="lozPrijava" size="20" placeholder="Lozinka" required="required"><br>

                <label id="tokenRadio" for="tokenRadioDa">Novi token?</label>
                <input type="radio" id="tokenRadioDa" name="tokenDaNe" value="1" />DA
                <label id="tokenNeBrisi" for="tokenRadioNe">Novi token?</label>
                <input type="radio" id="tokenRadioNe" name="tokenDaNe" value="0" checked="checked"/>NE<br>

                <label id="tokenL" for="token">Token:</label>
                <input type="text" id="token" name="token" placeholder="token" required="required" ><br>

                <label id="zapamtiMeRadio" for="zapamtiMeDa">Zapamti me:</label>
                <input type="radio" id="zapamtiMeDa" name="zapamtiMe" value="DA" />DA
                <label id="zapamtiMeLBrisi" for="zapamtiMeNe">Zapamti me?</label>
                <input type="radio" id="zapamtiMeNe" name="zapamtiMe" value="NE" checked="checked"/>NE<br>

                <input type="submit" id="posaljiPrijavu" name="posaljiPrijavu" value="Prijava">
                <a id="neReg" href="registracija.php"> Registracija</a>
            </form>
        </section>

        <div id="korisnici">
            <?php
            echo "Administrator -> korisničko ime: nikfluks, lozinka: NNnn11" . "<br>";
            echo "Moderator -> korisničko ime: ninafluks, lozinka: NIna12" . "<br>";
            echo "Obični korisnik -> korisničko ime: nik12, lozinka: NIko12" . "<br>";
            ?>
        </div>

        <footer>
            <p>Vrijeme potrebno za rješavanje aktivnog dokumenta: 3h </p>
            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_05%2Fnikfluks%2Fprijava.php" target="html5">
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
