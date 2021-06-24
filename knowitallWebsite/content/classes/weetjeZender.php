<?php
class zendWeetje {
    public $titel;
    public $inhoud;
    public $gebruiker;
    public $ingDatum;
    public $plaatje;
    public $conn;

    public function __construct($titel, $inhoud, $ingDatum, $plaatje, $gebruiker,$conn)
    {
        $this->titel = $titel;
        $this->inhoud = $inhoud;
        $this->gebruiker = $gebruiker;
        $this->ingDatum = $ingDatum;
        $this->plaatje = $plaatje;
        $this->conn = $conn;

        $sql = "INSERT INTO weetjesdb (titel, weetjes, gebruiker, geb_datum, plaatje) VALUES ('$titel','$inhoud','$gebruiker','$ingDatum','$plaatje')";
        mysqli_query($conn, $sql);
    }
}