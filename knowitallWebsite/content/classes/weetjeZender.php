<?php

class zendWeetje {
    public $titel;
    public $inhoud;
    public $ingDatum;
    public $gebruiker;
    public $image;

    public function __construct($titel, $inhoud, $ingDatum, $image, $gebruiker)
    {

        require dirname(__FILE__).'/../connectie.php';
        $this->titel = $titel;
        $this->inhoud = $inhoud;
        $this->gebruiker = $gebruiker;
        $this->ingDatum = $ingDatum;
        $this->image = $image;
        require_once "mail.php";
        if ($ingDatum == '') {
            $ingDatum = '0000-00-00';
        }
        $sql = "INSERT INTO weetjesdb (titel, weetjes, gebruiker, geb_datum, plaatje) VALUES ('$titel','$inhoud','$gebruiker','$ingDatum','$image')";
        if (mysqli_query($conn, $sql)) {
            if (sendMail($aMail, 'Nieuw weetje toegevoegd', '
<h1>Er is een nieuw weetje toegevoed.</h1>
<p>weetje is toegevoegd door <b>'. $gebruiker . '</b>.</p>
<hr>
<div>
    <h2>'.$titel.'</h2>
    <p>'.$inhoud.'</p>
</div>
<hr>
//<a href="http://'.$sdn.'.student4a0.ao-ica.nl/knowitallWebsite/content/admin_control_panel.php">Naar Admin Control Pagina</a>
<a href="http://'.$sdn.'.student4a0.ao-ica.nl/content/admin_control_panel.php">Naar Admin Control Pagina</a>
    
    ', 'KnowItAll<558674@edu.rocmn.nl>' )) {
                $mailStatus = "Er is een bevestigings email naar je gestuurd.";
            } else {
                $mailStatus = "Er ging iets fout bij het sturen van een bevestigings email.";
            }

            echo '<script>errorr(false, "Weetje is succesvol toegevoegd.")</script>';
        } else {
            echo '<script>errorr(true, "Er ging iets fout bij het toevoegen van het weetje.")</script>';
        }
    }

}