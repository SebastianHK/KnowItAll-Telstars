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

        $query = "SELECT * FROM gebruikers WHERE gebruiker='$gebruiker'";
        $results = mysqli_query($conn, $query);
        if (mysqli_num_rows($results) == 1) {
            $result = mysqli_fetch_assoc($results);
            $email = $result['email'];
        }

        $sql = "INSERT INTO weetjesdb (titel, weetjes, gebruiker, geb_datum, plaatje) VALUES ('$titel','$inhoud','$gebruiker','$ingDatum','$plaatje')";
        mysqli_query($conn, $sql);
    }
}