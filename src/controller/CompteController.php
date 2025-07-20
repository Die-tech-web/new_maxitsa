<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Core\Session;
use App\Service\CompteService;
use Dotenv\Validator;
class CompteController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
        $this->baselayout = 'base.layout.html.php';
        $this->compteService = App::getDependency('compteService');

    }

    public function index(): void
    {

        $user = $this->session->get('user');
        if (!$user) {
            header("Location: /");
            exit;
        }
        $compteService = App::getDependency('compteService');
        $compte = $compteService->getSolde($user['id']);

        $this->session->set('compte', $compte);

        header("Location: /dashbord");


    }

    public function listeComptes()
    {
        
        $user = $this->session->get('user');
        $compteService = App::getDependency('compteService');
        $compte = $compteService->getSolde($user['id']);
        $this->renderHtml('compte/list', ['compte' => $compte]);
    }


    public function ajouterCompteSecondaire()
    {
        $user = $this->session->get('user');
        if (!$user) {
            header('Location: /');
            exit;
        }

        $validator = App::getDependency('validator');
        $data = [
            'userid' => $user['id'],
            'numerotel' => trim($_POST['numerotel'] ?? ''),
            'numero' => uniqid('CPT-'),
            'datecreation' => date('Y-m-d H:i:s'),
            'solde' => (float) ($_POST['solde'] ?? 0)

        ];

        if (!$validator->validateCompteSecondaire($data)) {
            header('Location: /compte/list');
            exit;
        }

        $compteService = App::getDependency('compteService');
        if ($compteService->ajouterCompteSecondaire($data)) {
            $validator->setSuccess("Compte secondaire ajouté avec succès.");
        } else {
            $validator->addError('compte', "Erreur lors de l'ajout du compte.");
        }

        header('Location: /compte/list');
        exit;
    }


    public function listComptes()
    {
        $user = $this->session->get('user');
        $compteService = App::getDependency('compteService');

        $comptes = $compteService->getAllComptesByUserId($user['id']);

        $this->renderHtml('compte/list', ['comptes' => $comptes]);
    }

  public function changerComptePrincipal()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $this->session->get('user');
        $userId = $user['id'];
        $compteSecondaireId = $_POST['compte_id'] ?? null;

        if ($compteSecondaireId) {
            $this->compteService->basculerEnprincipal($userId, (int) $compteSecondaireId);
            $this->session->set('success', 'Le compte secondaire est maintenant principal.');
        }
        header('Location: /compte/list');
        exit;
    }
}





    public function store()
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
    public function create()
    {
    }


}