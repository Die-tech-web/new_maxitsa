<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\Session;
use App\Service\CompteService;
class CompteController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
        $this->baselayout = 'base.layout.html.php';

    }

    public function index(): void
    {
        // var_dump("ok");
        // die;
        // extract($_POST);

        $user = $this->session->get('user');
        if (!$user) {
            header("Location: /");
            exit;
        }
        $compteService = new CompteService();
        $compte = $compteService->getSolde($user['id']);
        
        $this->session->set('compte', $compte);

        header("Location: /dashbord");


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


}