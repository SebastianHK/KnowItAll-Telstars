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
        $this->titel = $titel;
        $this->inhoud = $inhoud;
        $this->gebruiker = $gebruiker;
        $this->ingDatum = $ingDatum;
        $this->image = $image;
        $this->conn = $conn;

        $query = "SELECT * FROM gebruikers WHERE gebruiker='$gebruiker'";
        $results = mysqli_query($conn, $query);
        if (mysqli_num_rows($results) == 1) {
            $result = mysqli_fetch_assoc($results);
            $email = $result['email'];
        }

        $sql = "INSERT INTO weetjesdb (titel, weetjes, gebruiker, geb_datum, plaatje) VALUES ('$titel','$inhoud','$gebruiker','$ingDatum','$image')";
        mysqli_query($conn, $sql);
    }
}