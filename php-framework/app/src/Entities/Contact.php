<?php



namespace App\Entities;

use App\Lib\Annotations\ORM\AutoIncrement;
use App\Lib\Annotations\ORM\Column;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\ORM;
use App\Lib\Entities\AbstractEntity;


#[ORM]
class Contact extends AbstractEntity {

    #[Id]
    #[AutoIncrement]
    #[Column(type: 'int')]
    public int $id;

    #[Column(type: 'varchar', size: 255)]
    public string $email;

    #[Column(type: 'varchar', size: 255)]
    public string $subject;
    
    #[Column(type: 'varchar', size: 255)]
    public string $message;
    

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getSubject(): string {
        return $this->subject;
    }

    public function getMessage(): string {
        return $this->message;
    } 
}

?>
