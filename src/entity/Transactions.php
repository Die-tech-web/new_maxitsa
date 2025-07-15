<?php

namespace App\Entity;

use App\Core\Abstract\AbstractEntity;
use App\Entity\TypeTransaction;

class Transactions extends AbstractEntity
{
    private int $id;
    private \DateTime $date;
    private TypeTransaction $typeTransaction;
    private float $montant;

    public function __construct(
        int $id,
        \DateTime $date,
        TypeTransaction $typeTransaction,
        float $montant
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->typeTransaction = $typeTransaction;
        $this->montant = $montant;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDate(): \DateTime
    {
        return $this->date;
    }
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }
    public function setCompte(?Compte $compte): void
    {
        $this->compte = $compte;
    }

    public function getTypeTransaction(): TypeTransaction
    {
        return $this->typeTransaction;
    }
    public function setTypeTransaction(TypeTransaction $typeTransaction): void
    {
        $this->typeTransaction = $typeTransaction;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }
    public function setMontant(float $montant): void
    {
        $this->montant = $montant;
    }



    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'typeTransaction' => $this->typeTransaction->value,
            'montant' => $this->montant,
        ];
    }

    public static function toObject(array $data): static
    {
        return new static(
            $data['id'],
            new \DateTime($data['date']),
            TypeTransaction::from($data['typetransaction']),
            (float) $data['montant']
        );
    }
}