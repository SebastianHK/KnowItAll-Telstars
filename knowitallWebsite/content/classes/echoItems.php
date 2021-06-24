<?php
class weetje {
    public $title;
    public $info;
    public $datum;
    public $status;
    public $user_id;

    public function __construct($title, $info, $datum)
    {
        $this->title = $title;
        $this->info = $info;
        $this->datum = $datum;

    }
}

$weetje1 = new Weetje('weetje 1','weetje text',new DateTime());
$weetje2 = new Weetje('weetje 2','weetje text',new DateTime());

var_dump($weetje1);