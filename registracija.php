<!DOCTYPE html>
<?php

include_once("aplikacijskiOkvir.php");

if (!isset($_COOKIE[$cookieIme])) {
    header("Location: uvjeti_koristenja_provjera.php");
}

$uri = $_SERVER["REQUEST_URI"];
$pos = strrpos($uri, "/");
$dir = $_SERVER["SERVER_NAME"] . substr($uri, 0, $pos + 1);

if (!isset($_SERVER["HTTPS"]) || strtolower($_SERVER["HTTPS"]) != "on") {
    $adresa = 'https://' . $dir . 'registracija.php';
    header("Location: $adresa");
}
?>

<html>
    <head>
        <title>Registracija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Registracija">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/nikfluksJquery.js"></script>
    </head>

    <body>
        <?php
        /* foreach ($_SERVER as $k => $vr) {
          echo "$k=$vr<br>\n";
          } */

        //4 Validacija na strani servera
        if (isset($_POST["submit"])) {
            /* foreach ($_POST as $key => $value) {
              echo "Kljuc: " . $key . ", vrijednost: " . $value . "<br>";
              } */
            $predlozakSpecZnak = "/[(){}\'!#\"\\/]/";
            $predlozakLozinka = "/^(?=(.*[A-Z]){2,})(?=(.*[a-z]){2,})(?=(.*[0-9]){1,})[^\s]{5,15}$/";
            $predlozakEmail = "/^[A-ZČĆŠĐŽa-zčćšđž0-9]+@\w+\.\w+$/";
            $predlozakKorIme = "/^[A-ZČĆŠĐŽa-zčćšđž0-9]{3,}$/";

            //3. zadatak a i
            if (empty($_POST["ime"])) {
                $greskaIme = "Ime nije uneseno!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["ime"])) {
                    $greskaImeSpec = "Ime sadrži specijalni znak!";
                }
            }
            if (empty($_POST["prezime"])) {
                $greskaPrezime = "Prezime nije uneseno!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["prezime"])) {
                    $greskaPrezimeSpec = "Prezime sadrži specijalni znak!";
                }
            }
            if (empty($_POST["korIme"])) {
                $greskaKorIme = "Korisničko ime nije uneseno!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["korIme"])) {
                    $greskaKorImeSpec = "Korisničko ime sadrži specijalni znak!";
                } elseif (!preg_match_all($predlozakKorIme, $_POST["korIme"])) {
                    $greskaKorImeDuljina = "Korisničko ime mora biti duljine minimalno 3 znaka!";
                }
            }
            if (empty($_POST["email"])) {
                $greskaEmail = "Email nije unesen!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["email"])) {
                    $greskaEmailSpec = "Email sadrži specijalni znak!";
                } else {
                    //3. zadatak a iv
                    if (!preg_match_all($predlozakEmail, $_POST["email"])) {
                        $greskaEmailFormat = "Email nije formata nesto@nesto.nesto!";
                    }
                }
            }
            if (empty($_POST["lozinka"])) {
                $greskaLozinka = "Lozinka nije unesena!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["lozinka"])) {
                    $greskaLozinkaSpec = "Lozinka sadrži specijalni znak!";
                } else {
                    //3. zadatak a ii
                    if (!preg_match_all($predlozakLozinka, $_POST["lozinka"])) {
                        $greskaLozinkaKriva = "Lozinka mora sadržavati barem 2 velika i 2 mala slova, 1 broj i duljina 5-15 znakova, bez razmaka!";
                    }
                }
            }
            if (empty($_POST["lozinka2"])) {
                $greskaPonLozinka = "Ponovljena lozinka nije unesena!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["lozinka2"])) {
                    $greskaPonLozinkaSpec = "Ponovljena lozinka sadrži specijalni znak!";
                }
            }
            //3. zadatak a iii
            if (!isset($greskaLozinka) && !isset($greskaLozinkaSpec) && !isset($greskaLozinkaKriva) && !isset($greskaPonLozinka) && !isset($greskaPonLozinkaSpec)) {
                if ($_POST["lozinka"] !== $_POST["lozinka2"]) {
                    $razliciteLozinke = "Lozinka i ponovljena lozinka se ne podudaraju!";
                }
            }

            //3. zadatak a v
            $sql = "SELECT * FROM korisnik WHERE korisnicko_ime='" . $_POST["korIme"] . "' or email='" . $_POST["email"] . "'";
            $rezultat = $dbc->selectDB($sql);
            $red = $rezultat->fetch_array();

            if ($dbc->pogreskaDB()) {
                echo "Problem kod upita na bazu!<br>";
            }

            if (!empty($red)) {
                if ($red["korisnicko_ime"] == $_POST["korIme"]) {
                    $postojeceKorIme = "Korisničko ime već postoji!";
                }
                if ($red["email"] == $_POST["email"]) {
                    $postojeciEmail = "Email već postoji!";
                }
            }
            
            //2 Provjera reCAPTCHA
            if ($_POST["g-recaptcha-response"] == false) {
                $reCAPTCHA = "Morate označiti nisam robot(reCAPTCHA)!";
            }

            //3. zadatak b i
            if (!isset($greskaIme) && !isset($greskaImeSpec) && !isset($greskaPrezime) && !isset($greskaPrezimeSpec) && !isset($greskaKorIme) && !isset($greskaKorImeSpec) && !isset($greskaEmail) && !isset($greskaEmailSpec) && !isset($greskaEmailFormat) && !isset($greskaLozinka) && !isset($greskaLozinkaSpec) && !isset($greskaLozinkaKriva) && !isset($greskaPonLozinka) && !isset($greskaPonLozinkaSpec) && !isset($razliciteLozinke) && !isset($reCAPTCHA) && !isset($postojeceKorIme) && !isset($postojeciEmail) && !isset($greskaKorImeDuljina)) {
                $salt = sha1(time());
                $kriptirana_lozinka = sha1($salt . "--" . $_POST["lozinka"]);

                $saltAkt = sha1(time());
                $aktKod = sha1($saltAkt . $_POST["korIme"]);
                $datumIstekaAktivacije = date("Y-m-d H:i:s", strtotime("+5 hours"));

                $sql = "INSERT INTO korisnik (ime,prezime,email,korisnicko_ime,lozinka,tip_korisnika_id,dvorazinska_prijava,aktivacijski_link,kriptirana_lozinka,adresa,datum_isteka_aktivacije) "
                        . "VALUES ('" . $_POST["ime"] . "','" . $_POST["prezime"] . "','" . $_POST["email"] . "','" . $_POST["korIme"] . "','" . $_POST["lozinka"] . "',1," . $_POST["PrijavaU2"] . ",'$aktKod', '$kriptirana_lozinka','" . $_SERVER["REMOTE_ADDR"] . "','$datumIstekaAktivacije')";
                $uspjeh = $dbc->ostaliUpitiDB($sql);

                $skripta = substr($uri, $pos + 1);
                $kor_id = dohvatiKorisnikId($_POST["korIme"]);

                $prviRazmak = strpos($sql, " ");
                $tipUpita = substr($sql, 0, $prviRazmak);

                dnevnik_zapis($tipUpita, $skripta, $kor_id);
                dnevnik_zapis("Registracija", $skripta, $kor_id);


                echo "Slanje maila!<br>";

                $mail_to = $_POST["email"];
                $mail_subject = "Aktivacijski link";

                $mail_from = "From: WebDiP0x38@foi.hr" . "\r\n";
                $mail_from .= "MIME-Version: 1.0" . "\r\n";
                $mail_from .= "Content-type: text/html; charset=utf-8" . "\r\n";

                $mail_body = "
                <html>
                <head>
                    <title>Test Mail</title>
                </head>
                <body>
                    <p>Za aktivaciju kliknite na link: <a href='http://" . $dir . "aktivacija.php?aktkod=" . $aktKod . "&id=" . $kor_id . "' target='akt'>Link</a></p>
                </body>
                </html>
                ";

                echo $mail_body . "<br>";
                
                if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                    echo("Poslana poruka za: '$mail_to'!");
                } else {
                    echo("Problem kod poruke za: '$mail_to'!");
                }
            }
        }
        $dbc->zatvoriDB();
        ?>


        <header>
            <h1 id="status">Registracija</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.php" alt="logo" shape="rect" coords="0,0,200,200" target="index"/>
                    <area href="#registracija_nav" alt="logo" shape="rect" coords="200,0,400,200" />
                </map>
                <figcaption id="logoCap">Interaktivna slika</figcaption>
            </figure>
        </header>

        <nav id="registracija_nav">
            <ul>
                <li><a href="privatno/korisnici.php"> Korisnici</a></li>
                <li><a href="index.php"> Početna</a></li>

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

        <section class="registracija">
            <h2>Registracija</h2>

            <div id="greskeReg">
                <?php
                if (isset($greskaIme)) {
                    echo $greskaIme . "<br>";
                } else {
                    if (isset($greskaImeSpec)) {
                        echo $greskaImeSpec . "<br>";
                    }
                }
                if (isset($greskaPrezime)) {
                    echo $greskaPrezime . "<br>";
                } else {
                    if (isset($greskaPrezimeSpec)) {
                        echo $greskaPrezimeSpec . "<br>";
                    }
                }
                if (isset($greskaKorIme)) {
                    echo $greskaKorIme . "<br>";
                } else {
                    if (isset($greskaKorImeSpec)) {
                        echo $greskaKorImeSpec . "<br>";
                    } elseif (isset($greskaKorImeDuljina)) {
                        echo $greskaKorImeDuljina . "<br>";
                    } elseif (isset($postojeceKorIme)) {
                        echo $postojeceKorIme . "<br>";
                    }
                }
                if (isset($greskaEmail)) {
                    echo $greskaEmail . "<br>";
                } else {
                    if (isset($greskaEmailSpec)) {
                        echo $greskaEmailSpec . "<br>";
                    } else {
                        if (isset($greskaEmailFormat)) {
                            echo $greskaEmailFormat . "<br>";
                        } elseif (isset($postojeciEmail)) {
                            echo $postojeciEmail . "<br>";
                        }
                    }
                }
                if (isset($greskaLozinka)) {
                    echo $greskaLozinka . "<br>";
                } else {
                    if (isset($greskaLozinkaSpec)) {
                        echo $greskaLozinkaSpec . "<br>";
                    } else {
                        if (isset($greskaLozinkaKriva)) {
                            echo $greskaLozinkaKriva . "<br>";
                        }
                    }
                }
                if (isset($greskaPonLozinka)) {
                    echo $greskaPonLozinka . "<br>";
                } else {
                    if (isset($greskaPonLozinkaSpec)) {
                        echo $greskaPonLozinkaSpec . "<br>";
                    }
                }
                if (isset($razliciteLozinke)) {
                    echo $razliciteLozinke . "<br>";
                }
                if (isset($reCAPTCHA)) {
                    echo $reCAPTCHA . "<br>";
                }
                ?>
            </div>
            <form method="POST" name="registracija"
                  action="<?php echo $_SERVER["PHP_SELF"]; ?>" novalidate>
                <label for="ime">Ime:</label>
                <input type="text" id="ime" name="ime" size="30" placeholder="Ime"
                <?php
                if (isset($_POST["ime"])) {
                    echo "value='" . $_POST["ime"] . "'";
                }
                ?>><br>

                <label for="prezime">Prezime:</label>
                <input type="text" id="prezime" name="prezime" size="30" placeholder="Prezime"
                <?php
                if (isset($_POST["prezime"])) {
                    echo "value='" . $_POST["prezime"] . "'";
                }
                ?>><br>

                <label for="korIme">Korisničko ime:</label>
                <input type="text" id="korIme" name="korIme" size="30" placeholder="Korisničko ime" required="required"
                <?php
                if (isset($_POST["korIme"])) {
                    echo "value='" . $_POST["korIme"] . "'";
                }
                ?>><br>

                <label for="email">E-mail adresa:</label>
                <input type="email" id="email" name="email" size="30" placeholder="nesto@nesto.nesto" required="required"
                <?php
                if (isset($_POST["email"])) {
                    echo "value='" . $_POST["email"] . "'";
                }
                ?>><br>

                <label for="lozinka">Lozinka:</label>
                <input type="password" id="lozinka" name="lozinka" size="30" placeholder="Lozinka" required="required"><br>
                <label for="lozinka2">Ponovi lozinku:</label>
                <input type="password" id="lozinka2" name="lozinka2" size="30" placeholder="Ponovi lozinku" required="required"><br>

                <label id="prijava2Radio" for="prijava2RadioDa">Prijava u 2 koraka?</label>
                <input type="radio" id="prijava2RadioDa" name="PrijavaU2" value="1" />DA
                <label id="prijava2NeBrisi" for="prijava2RadioNe">Prijava u 2 koraka?</label>
                <input type="radio" id="prijava2RadioNe" name="PrijavaU2" value="0" checked="checked"/>NE<br>

                <input type="submit" id="posaljiReg" name="submit" value="Registracija"> 
                <div class="g-recaptcha" data-sitekey="6LfZuh8UAAAAAHdIhxrq4UGlUBDrCtHNjDqVIV4M"></div>
            </form>
        </section>

        <footer>
            <p>Vrijeme potrebno za rješavanje aktivnog dokumenta: 4h </p>
            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_05%2Fnikfluks%2Fregistracija.php" target="html5">
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


