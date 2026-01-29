<?php

namespace App\Entities;

class Contact
{
    public string $email;
    public string $subject;
    public string $message;
    public int $creationDate;
    public int $lastUpdateDate;

    public function __construct(string $email, string $subject, string $message)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
        $this->creationDate = time();
        $this->lastUpdateDate = time();
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'CreationDate' => $this->creationDate,
            'LastUpdateDate' => $this->lastUpdateDate,
        ];
    }

    public function saveToFile(string $filepath): void
    {
        $data = json_encode($this->toArray(), JSON_PRETTY_PRINT);
        file_put_contents($filepath, $data);
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDateOfCreation(): int
    {
        return $this->creationDate;
    }

    public function getDateOfLastUpdate(): int
    {
        return $this->lastUpdateDate;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setLastUpdateDate(int $timestamp): void
    {
        $this->lastUpdateDate = $timestamp;
    }

    public function setCreationDate(int $timestamp): void
    {
        $this->creationDate = $timestamp;
    }
}