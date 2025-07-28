<?php
$session = App\Core\Session::getInstance();
$errors = $session->get('errors');
$success = $session->get('success');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Popup Achat Woyofal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<!-- Bouton -->
<div class="p-8">
    <button onclick="document.getElementById('popup-woyofal').classList.remove('hidden')"
            class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-5 rounded-xl shadow">
        Achat Woyofal
    </button>
</div>

<!-- Popup -->
<div id="popup-woyofal" class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center hidden">
    <div class="relative bg-white rounded-2xl shadow-lg w-full max-w-2xl animate-fade-in overflow-hidden">
        <!-- Bouton de fermeture -->
        <button onclick="document.getElementById('popup-woyofal').classList.add('hidden')"
                class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-xl font-bold z-20">
            &times;
        </button>

        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-400 to-orange-500 text-white text-center py-8 px-4 relative">
            <h1 class="text-3xl font-semibold">Achat Woyofal</h1>
            <p class="text-base font-light opacity-90 mt-1">Rechargez votre compteur électrique</p>
        </div>

        <!-- Contenu -->
        <div class="p-6 sm:p-8">
            <?php if (!empty($errors)): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-md text-sm text-red-700">
                    <?php foreach ($errors as $error): ?>
                        <?= htmlspecialchars($error) ?><br>
                    <?php endforeach; ?>
                </div>
                <?php $session->remove('errors'); ?>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4 rounded-md text-sm text-green-700">
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php $session->remove('success'); ?>
            <?php endif; ?>

            <!-- Formulaire -->
            <form action="/woyofal/payer" method="POST" class="space-y-5">
                <div>
                    <label for="numero_compteur" class="block text-sm font-medium text-gray-700 mb-1">
                        Numéro du compteur <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="numero_compteur" name="numero_compteur"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                        placeholder="Ex: CPT123456"
                        value="<?= isset($_POST['numero_compteur']) ? htmlspecialchars($_POST['numero_compteur']) : '' ?>"
                        required>
                </div>

                <div>
                    <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">
                        Montant (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="montant" name="montant" min="100" step="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                        placeholder="Minimum 100 FCFA"
                        value="<?= isset($_POST['montant']) ? htmlspecialchars($_POST['montant']) : '' ?>"
                        required>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-amber-500 to-orange-500 text-white py-3 rounded-lg font-medium hover:from-amber-600 hover:to-orange-600 transition-all">
                    Payer maintenant
                </button>

                <div class="text-center pt-2">
                    <a href="/dashbord" class="text-amber-500 hover:text-amber-600 text-sm font-medium">
                        Retour au tableau de bord
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const errorsExist = <?= json_encode(!empty($errors)) ?>;
    const successExist = <?= json_encode(!empty($success)) ?>;

    if (errorsExist || successExist) {
        document.getElementById('popup-woyofal').classList.remove('hidden');
    }
});
</script>
