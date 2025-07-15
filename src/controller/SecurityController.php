<?php
namespace App\Controller;
use App\Core\Abstract\AbstractController;
use PDO;
use PDOException;
use App\Service\UserService;
use App\Core\App;

class SecurityController extends AbstractController
{
    private UserService $userService;
    public function __construct()
    {
        // $this->baselayout = 'base.layout.html.php';
        parent::__construct(); // ✅ initialise $this->session
        $this->userService = new UserService();
        // var_dump($this->userService);
        // die;
    }
    public function login()
    {

        parent::__construct();
        return $this->renderHtml('security/login');
    }

    public function auth()
    {
        extract($_POST);

        $user = $this->userService->getUserByLoginAndPassword($login, $password);
        $this->session->set('user', $user);
        if ($user) {
            // var_dump(header('Location: /dashboard'));
            // die;
            header('Location: /dashboard');
        } else {
            header('Location: /login');
        }
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
