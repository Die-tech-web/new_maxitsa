<?php

namespace App\Controller;

use App\Core\Session;
use App\Core\App;

class WoyofalController
{
    // ✅ Méthode privée pour vérifier l'authentification
    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        return $_SESSION['user'];
    }

    // ✅ Méthode pour afficher le formulaire
    public function afficherFormulaire()
    {
        $user = $this->checkAuth();

        if ($user['typeuser'] !== 'client') {
            header('Location: /dashbord');
            exit;
        }

        require_once __DIR__ . '/../../templates/woyofal.php';
    }

    // ✅ Méthode pour traiter le paiement
    public function traiterPaiement()
    {
        $user = $this->checkAuth();
        // dd(data: $user);
        if ($user['typeuser'] !== 'client') {
            header('Location: /dashbord');
            exit;
        }

        $compteur = $_POST['numero_compteur'] ?? null;
        $montant = $_POST['montant'] ?? null;
        // dd($montant);
        if (!$compteur || !$montant) {
            $_SESSION['errors'] = ['Veuillez remplir tous les champs'];
            header('Location: /woyofal/acheter');
            exit;
        }

        // Validation du montant
        if (!is_numeric($montant) || $montant <= 0) {
            $_SESSION['errors'] = ['Le montant doit être un nombre positif'];
            header('Location: /woyofal/acheter');
            exit;
        }


        // 🔧 CORRECTION: Gestion flexible de l'ID du compte
        $compteId = null;
        //  dd($compteid);

        // Essayer différentes clés possibles pour l'ID du compte
        if (isset($user['compte_id']) && !empty($user['compte_id'])) {
            dd('ok');
            $compteId = $user['compte_id'];
        } elseif (isset($user['id']) && !empty($user['id'])) {
            $compteId = $user['id'];
        } elseif (isset($user['userid']) && !empty($user['userid'])) {
            $compteId = $user['userid'];
        }

        // 🔧 Si aucun ID trouvé, essayer de récupérer le compte par d'autres moyens
        if (!$compteId) {
            $compteRepo = App::getDependency('compteRepository');

            // Essayer de trouver le compte par email ou nom d'utilisateur
            if (isset($user['email'])) {
                $compte = $compteRepo->findByEmail($user['email']);
                if ($compte) {
                    $compteId = $compte['id'];
                    // Mettre à jour la session avec l'ID du compte
                    $_SESSION['user']['compte_id'] = $compteId;
                }
            }
        }

        // Si toujours pas d'ID, c'est une erreur de session
        if (!$compteId) {
            // 🔧 DEBUG: Afficher le contenu de la session pour diagnostiquer
            error_log('Contenu de la session user: ' . print_r($user, true));

            $_SESSION['errors'] = ['Erreur de session: compte non trouvé. Veuillez vous reconnecter.'];
            header('Location: /logout');
            exit;
        }

        $compteRepo = App::getDependency('compteRepository');

        // Vérifier le solde du compte
        $compte = $compteRepo->findById((int) $compteId);

        if (!$compte) {
            $_SESSION['errors'] = ['Compte non trouvé. Veuillez vous reconnecter.'];
            header('Location: /logout');
            exit;
        }

        if ($compte['solde'] < $montant) {
            $_SESSION['errors'] = ['Solde insuffisant pour effectuer cet achat'];
            header('Location: /woyofal/acheter');
            exit;
        }

        // Appel à l'API Woyofal
        $apiUrl = "http://localhost:8000/api/woyofal/acheter?numero_compteur=" . urlencode($compteur) . "&montant=" . urlencode($montant);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            $_SESSION['errors'] = ['Erreur lors de la communication avec le service Woyofal'];
            header('Location: /woyofal/acheter');
            exit;
        }

        $woyofalData = json_decode($response, true);

        if ($httpCode !== 200 || !$woyofalData || $woyofalData['statut'] !== 'success') {
            $errorMessage = $woyofalData['message'] ?? 'Erreur lors de l\'achat Woyofal';
            $_SESSION['errors'] = [$errorMessage];
            header('Location: /woyofal/acheter');
            exit;
        }

        $result = $compteRepo->createTransaction(
            (int) $compteId,
            'PAIEMENT',
            (float) $montant,
            'Achat Woyofal: ' . $compteur
        );

        if (!$result['success']) {
            $_SESSION['errors'] = ['Erreur lors de l\'enregistrement de la transaction: ' . $result['message']];
            header('Location: /woyofal/acheter');
            exit;
        }
        $_SESSION['user']['solde'] = $result['nouveau_solde'];
        $_SESSION['user']['compte_id'] = $compteId;

        $_SESSION['woyofal_receipt'] = $woyofalData;
        $_SESSION['success'] = 'Achat Woyofal effectué avec succès';
        header('Location: /woyofal/confirmation');
        exit;

    }

    public function afficherConfirmation()
    {
        $this->checkAuth();
        require_once __DIR__ . '/../../templates/woyofal-confirmation.php';
    }

    // ========== MÉTHODES ALTERNATIVES (garder pour compatibilité) ==========

    public function showWoyofalForm()
    {
        return $this->afficherFormulaire();
    }

    public function processWoyofalPayment()
    {
        return $this->traiterPaiement();
    }

    public function showConfirmation()
    {
        return $this->afficherConfirmation();
    }
}