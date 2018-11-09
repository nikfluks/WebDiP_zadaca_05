<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Početna</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna">
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
            echo "Morate se prijaviti da bi mogli vidjeti stranicu!";
            header("Location:prijava.php");
        } else {
            echo "Sesija postoji: " . $_SESSION["korisnik"] . ", " . $_SESSION["tip"] . ", " . $_SESSION["greske"] . "!";
        }

        //error_reporting(2);
        ?>

        <header>
            <h1>Početna</h1>
            <a href="slike/web_stress.jpg" target="stress">
                <figure id="logoSlika">
                    <img src="slike/web_stress.jpg" alt="Stress" width="400" height="200">
                    <figcaption id="logoCap">Web Stress Test</figcaption>
                </figure>
            </a>
        </header>

        <nav>
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

        <section class="osobni_podaci">
            <h2 id="heding2_podaci">Osobni podaci</h2>
            <ul id="podaci">
                <li>Ime: Nikola</li>
                <li>Prezime: Fluks</li>
                <li>E-Mail: nikfluks@foi.hr</li>
                <li>Broj indeksa: 42117/13-R</li>
            </ul>
            <figure id="profil">
                <img src="slike/profil2.jpg" alt="profil" width="150" height="200">
                <figcaption>Nikola Fluks</figcaption>
            </figure> 
        </section>

        <section class="omiljeni_proizvodi">
            <br>
            <h2 id="heding2_proizvodi">Mojih 5 omiljenih proizvoda:</h2>
            <article>
                <h3>1. proizvod</h3>
                <p>Naslov: Laptop</p>
                <p>Opis: Popis 5 omiljenih prozvoda počet ću sa laptopom Acer Aspire E5-774G-55LD. 
                    On nije u mojem vlasništvu, još, ali nudi odlične karakteristike za cijenu od cca 5100kn.
                    Naime, dolazi sa najnovijom genaracijom, sedmoj po redu, intel i5 procesora (i5 7200U),
                    također ima 8GB RAM-a, 1TB čvrstog diska. Osim tih "osnovnih" stvari koje i možemo očekivati 
                    za laptop s tom cijenom, ovaj model dolazi sa Nvidia GeForce GTX 950MX gračikom karticom koja
                    je vrlo respektabilna te, ono što još posebno veseli svakog "gejmera", laptop ima 17,3" FullHD
                    ekran, tako da doživljaj igranja bude potpun.
                </p>
                <figure>
                    <img class="slike_omiljenih_proizvoda" src="slike/acer.png" alt="Acer Aspire">
                    <figcaption class="sjena">Acer Aspire E5-774G-55LD</figcaption>
                </figure> 
            </article>

            <article>
                <br>
                <h3>2. proizvod</h3>
                <p>Naslov: Mobitel</p>
                <p>Opis: Sljedeći proizvod je također iz područja tehnike. Radi se o mobitelu Motorola Moto G4,
                    koji nudi sve što je potrebno za prosječnog (a i za zahtjevnije) korisnika uz povoljnu cijenu.
                    Za cijenu od cca 1500kn dobivamo: 5,5" FullHD ekran s Gorilla Glass 3 zaštitom, Snapdragon 617
                    čip s 8-jezgrenim procesorom, Adreno 405 grafičku karticu, 16 ili 32 GB interne memorije i 2GB
                    RAM-a. Također ima pristojnu stražnju kameru od 13MP i prednju od 5MP te bateriju od 3000mAh
                    što uz, praktički, stock Android 6.0.1 (nadogradiv na 7.0) znači da se možemo koristiti mobitelom
                    cijeli dan bez da nam se isprazni.  
                </p>
                <figure>
                    <img class="slike_omiljenih_proizvoda" src="slike/motog4.jpg" alt="Moto G4">
                    <figcaption class="sjena">Moto G4</figcaption>
                </figure> 
            </article>

            <article>
                <br>
                <h3>3. proizvod</h3>
                <p>Naslov: SSD</p>
                <p>Opis: Još malo tehnike koje nikad dosta. Ovaj puta riječ je o Solid State Disku (SSD)
                    bez kojih današnji život na laptopu i/ili PC-u postaje nezamisliv (barem za mene). Kad
                    se jednom proba tako nešto, teško se kasnije može bez njega, jer ubrza naše računalo
                    maksimalno. Ovdje sam stavio primjer Samsungovog SSD EVO 850 Basic diska iz razloga što
                    i sam posjedujem Samsungov SSD i pokazao se kao vrlo pouzdan i naravno brz. On ima brzinu
                    čitanja od 540, a pisanja od 520 MB/s, što je najmanje 10 puta brže od brzina običnog HDD-a
                    tako da svakome preporučujem da u svoj laptop/PC ugradi SSD (nebitno za koji točno se odlučite,
                    jer svi su oni puuno brži od običnih HDD-a) i počne uživati u brzini.
                </p>
                <figure>
                    <img class="slike_omiljenih_proizvoda" src="slike/samsung850.jpg" alt="Samsung 850 EVO Basic">
                    <figcaption class="sjena">Samsung 850 EVO Basic</figcaption>
                </figure> 
            </article>

            <article>
                <br>
                <h3>4. proizvod</h3>
                <p>Naslov: Proteini</p>
                <p>Opis: Da se pomaknemo malo s tehnike i skočimo na zdravu prehranu. Danas postoji jako puno
                    teorija što jesti, koliko, u kojim vremenskim razmacima i slično. Ali polako se počinje 
                    prihvačati prijedlog koji kaže da se treba jesti dnevno više obroka (5-6), umjesto klasičnih
                    3 i da bi svi oni trebali biti proteinski dosta bogati (pogotovo to vrijedi za sportaše/aktivne osobe.
                    Budući da je ovo naše doba vrlo brzo, teško je uvijek paziti da se pojede više obroka dnevno, a kamoli 
                    još da budu proteinski bogati. Na sreću postoje dodaci prehrani (suplementi) pomoću kojih je to lakše 
                    ostvariti, a jedan od njih je Amino Whey. To nije ništa drugo nego protein sirutke (mlijeka) koja je
                    posušena i spremljena u obliku praha. Ovaj konkretni protein je vrlo kvalitetan (sadrži 86% proteina)
                    te pomaže u oporavku, ali i u izgradnji mišića te ga preporučujem svim aktivnim ljudima.
                </p>
                <figure>
                    <img class="slike_omiljenih_proizvoda" src="slike/amino.jpg" alt="Amino Whey">
                    <figcaption class="sjena">Amino Whey</figcaption>
                </figure> 
            </article>

            <article>
                <br>
                <h3>5. proizvod</h3>
                <p>Naslov: Milka</p>
                <p>Opis: Kao što se i kaže, šećer na kraju, tako sam i ja ostavio najslađi proizvod za kraj.
                    Naravno radi se o svima dobro poznatoj, i finoj, čokoladi Milka. Nije posebno ništa puno
                    tu objašnjavati, samo treba pronaći okus koji vam se trenutno najviše sviđa i to je to. 
                    Kao i vjerujem većini ljudi, teško se odlučiti za jedan, najbolji okus, ali evo izdvojio
                    bih Noisette, vjerojatno zato jer je jedna od najslađih Milki. Naravno ne treba pretjerivati,
                    ali ako se ne možete zaustaviti dok jednom krenete, onda je najbolje opcija da se kupi mala
                    (80g) Milka, pa će te unijeti manje kalorija ako i pojedete cijelu, nego da to isto napravite
                    sa onom od 300g.
                </p>
                <figure >
                    <img class="slike_omiljenih_proizvoda" src="slike/milka.jpg" alt="Milka">
                    <figcaption class="sjena">Milka Noisette</figcaption>
                </figure> 
            </article>
        </section>

        <footer>
            <p>Vrijeme potrebno za rješavanje aktivnog dokumenta: 3h </p>
            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_05%2Fnikfluks%2Findex.php" target="html5">
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



