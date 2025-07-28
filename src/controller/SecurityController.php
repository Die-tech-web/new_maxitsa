<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Service\UserService;
use App\Core\Validator;

class SecurityController extends AbstractController
{
    private UserService $userService;
    private Validator $validator;

    public function __construct()
    {

        parent::__construct();

        $this->userService = App::getDependency('userService');

        $this->validator = App::getDependency('validator');

    }

    public function login()
    {
        return $this->renderHtml('security/login');
    }

    public function auth()
    {
        $loginData = [
            'login' => $_POST['login'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        if ($this->validator->validateLogin($loginData)) {
            $user = $this->userService->getUserByLoginAndPassword($loginData['login'], $loginData['password']);

            if ($user) {
                $test=$_SESSION['user'] = $user;
                header('Location: /dashboard');
                exit();
            } else {

                $this->validator->addError('login', "le login est incorrecte");
                header('Location: /');
                exit();
            }
        } else {
            header('Location: /');
            exit();
        }
    }
    public function logout()
    {
        $this->session->destroy('user');
        
        header('Location: /');
        exit();
    }

public function register()
{
    $this->baselayout = 'inscription.layout.php';
    return $this->renderHtml('security/inscription');
}


    public function handleRegister()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];

        // Validation de base
        if (empty($_POST['telephone'])) {
            $errors['telephone'] = 'Le téléphone est requis';
        }

        if (empty($_POST['nom'])) {
            $errors['nom'] = 'Le nom est requis';
        }

        if (empty($_POST['prenom'])) {
            $errors['prenom'] = 'Le prénom est requis';
        }

        if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
            $errors['password'] = 'Mot de passe trop court (min 6 caractères)';
        }

        if ($_POST['password'] !== $_POST['confirm_password']) {
            $errors['confirm_password'] = 'Les mots de passe ne correspondent pas';
        }

        if (empty($_POST['cni'])) {
            $errors['cni'] = 'Le numéro CNI est requis';
        }

        if (empty($_POST['adresse'])) {
            $errors['adresse'] = 'L’adresse est requise';
        }

        if (!isset($_FILES['photo_recto']) || $_FILES['photo_recto']['error'] !== 0) {
            $errors['photo_recto'] = 'Photo recto manquante ou invalide';
        }

        if (!isset($_FILES['photo_verso']) || $_FILES['photo_verso']['error'] !== 0) {
            $errors['photo_verso'] = 'Photo verso manquante ou invalide';
        }

        // Si erreurs -> redirection
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['message'] = 'Veuillez corriger les erreurs du formulaire';
            $_SESSION['type'] = 'error';
            header('Location: /inscription');
            exit;
        }

        // Traitement du fichier (démo, à adapter)
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filenameRecto = uniqid() . '-' . $_FILES['photo_recto']['name'];
        $filenameVerso = uniqid() . '-' . $_FILES['photo_verso']['name'];
        move_uploaded_file($_FILES['photo_recto']['tmp_name'], $uploadDir . $filenameRecto);
        move_uploaded_file($_FILES['photo_verso']['tmp_name'], $uploadDir . $filenameVerso);

        // Enregistrement en base (à adapter à ton modèle)
        // ex: Utilisateur::create(...)

        $_SESSION['message'] = 'Inscription réussie !';
        $_SESSION['type'] = 'success';

        // Redirige vers login
        header('Location: /');
        exit;
    }

    // Méthode non-POST
    header('Location: /inscription');
    exit;
}


    public function store()
    {
    }
    public function create()
    {
    }
    public function destroy()
    {
    }
    public function show($id)
    {
    }
    public function edit()
    {
    }
    public function update()
    {
    }
    public function delete()
    {
    }
    public function index()
    {
    }
}
