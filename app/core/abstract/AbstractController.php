<?php
namespace App\Core\Abstract;

use App\Core\Session;
use App\Core\App;


abstract class AbstractController
{

    protected string $baselayout = 'security/security.layout.php';
    protected Session $session;
    public function __construct()
    {

        $this->session = Session::getInstance();

        //mis a jour du solde  
        $user = $this->session->get('user');
        if ($user) {
            $compteService = App::getDependency('compteService');
            $compte = $compteService->getSolde($user['id']);
            $this->session->set('compte', $compte);
        }
    }

    public function renderHtml($view, $data = []): void
    {
        // var_dump(require_once "../templates/$view.html.php");
        // die;

        if (!empty($data)) {
            extract($data);
        }
        // var_dump(require_once "../templates/$view.html.php");
        // die;
        ob_start();
        require_once "../templates/$view.html.php";
        $content = ob_get_clean();
        require_once "../templates/layout/" . $this->baselayout;
    }

    abstract public function index();

    abstract public function store();

    abstract public function create();

    abstract public function destroy();

    abstract public function show($id); // ✅ signature correcte

    abstract public function edit();
    abstract public function update();

    abstract public function delete();


}