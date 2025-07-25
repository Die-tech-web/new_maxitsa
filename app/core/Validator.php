<?php
namespace App\Core;

use App\Core\Session;

class Validator
{
    private static array $errors = [];
    private static ?Validator $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): Validator
    {
        if (self::$instance === null) {
            self::$instance = new Validator();
        }
        self::$errors = [];
        return self::$instance;
    }

    public function validateLogin(array $data): bool
    {
        $login = trim($data['login'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($login)) {
            self::$errors['login'] = 'Le login n est pas correcte';
        }

        if (empty($password)) {
            self::$errors['password'] = 'Le mot de passe est obligatoire';
        } elseif (strlen($password) < 4) {
            self::$errors['password'] = 'Le mot de passe doit contenir au moins 4 caractères';
        }

        if (!empty(self::$errors)) {
            Session::getInstance()->set('errors', self::$errors);
            return false;
        }

        return true;
    }
    public function validateCompteSecondaire(array $data): bool
    {
        $numerotel = trim($data['numerotel'] ?? '');

        if (empty($numerotel)) {
            self::$errors['numerotel'] = 'Le numéro est obligatoire.';
        } elseif (!preg_match('/^(77|78|76|70|75)[0-9]{7}$/', $numerotel)) {
            self::$errors['numerotel'] = 'Numéro non valide (ex: 77XXXXXXX).';
        }

        if (!empty(self::$errors)) {
            Session::getInstance()->set('errors', self::$errors);
            return false;
        }

        return true;
    }


    public function addError(string $field, string $message): void
    {
        self::$errors[$field] = $message;
        Session::getInstance()->set('errors', self::$errors);
    }

    public function getErrors(): array
    {
        return self::$errors;
    }
    public function setSuccess(string $message)
    {
        Session::getInstance()->set('success', $message);
    }

}
