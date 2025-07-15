<?php


namespace App\Entity;
use App\Core\Abstract\AbstractEntity;

use App\Entity\TypeCompte;

class Compte extends AbstractEntity
{
    private ?int $id = null;
    private string $numero;
    private \DateTime $dateCreation;
    private float $solde;
    private string $numeroTel;
    private TypeCompte $typeCompte;
    private int $userId;
    private Users $user;
    private array $transactions = [];

    public function __construct(
        string $numero,
        \DateTime $dateCreation,
        float $solde,
        string $numeroTel,
        TypeCompte $typeCompte,
        int $userId,
        Users $user
    ) {
        $this->numero = $numero;
        $this->dateCreation = $dateCreation;
        $this->solde = $solde;
        $this->numeroTel = $numeroTel;
        $this->typeCompte = $typeCompte;
        $this->userId = $userId;
        $this->user = $user;
        $this->transactions = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNumero(): string
    {
        return $this->numero;
    }
    public function setNumero(string $numero): void
    {
        $this->numero = $numero;
    }
    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(Users $user): void
    {
        $this->user = $user;
        $this->userId = $user->getId();
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }
    public function addTransaction(TypeTransaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function getDateCreation(): \DateTime
    {
        return $this->dateCreation;
    }
    public function setDateCreation(\DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getSolde(): float
    {
        return $this->solde;
    }
    public function setSolde(float $solde): void
    {
        $this->solde = $solde;
    }

    public function getNumeroTel(): string
    {
        return $this->numeroTel;
    }
    public function setNumeroTel(string $numeroTel): void
    {
        $this->numeroTel = $numeroTel;
    }

    public function getTypeCompte(): TypeCompte
    {
        return $this->typeCompte;
    }
    public function setTypeCompte(TypeCompte $typeCompte): void
    {
        $this->typeCompte = $typeCompte;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'datecreation' => $this->dateCreation->format('Y-m-d H:i:s'),
            'solde' => $this->solde,
            'numerotel' => $this->numeroTel,
            'typecompte' => $this->typeCompte->value,
            'userid' => $this->userId,
        ];
    }

    public static function toObject(array $data): self
    {
        $userData = $data['user'] ?? null;

        $user = $userData ? Users::toObject($userData) : new Users(
            '',
            '',
            '',
            '',
            '',
            '',
            TypeUser::from('client')
        );

        $compte = new self(
            $data['numero'] ?? '',
            new \DateTime($data['datecreation'] ?? 'now'),
            (float)($data['solde'] ?? 0),
            $data['numerotel'] ?? '',
            TypeCompte::from($data['typecompte'] ?? 'principal'),
            (int)($data['userid'] ?? 0),
            $user
        );

        if (isset($data['id'])) {
            $reflection = new \ReflectionClass(self::class);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($compte, (int)$data['id']);
        }

        return $compte;
    }


  
}