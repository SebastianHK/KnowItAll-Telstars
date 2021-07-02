<?php
class zendWeetje {
    public $titel;
    public $inhoud;
    public $ingDatum;
    public $gebruiker;
    public $image;
    public $conn;

    public function __construct($titel, $inhoud, $ingDatum, $image, $gebruiker, $conn)
    {
        require "mail.php";
        $this->titel = $titel;
        $this->inhoud = $inhoud;
        $this->gebruiker = $gebruiker;
        $this->ingDatum = $ingDatum;
        $this->image = $image;
        $this->conn = $conn;

        $sql = "INSERT INTO weetjesdb (titel, weetjes, gebruiker, geb_datum, plaatje) VALUES ('$titel','$inhoud','$gebruiker','$ingDatum','$image')";
        if (mysqli_query($conn, $sql)) {
            sendMail('lexbrinkman2002@gmail.com','Nieuw weetje toegevoegd', 'Er is een nieuw weetje toegevoed. Dit weetje is toegevoegd door '. $gebruiker . '.' );
        }
    }
}