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
                $this->session->set('user', $user);
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
    function logout()
    {
        $this->session->destroy('user');
        header('Locaton: ' . $this->url . '/');
        exit();
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
