<?php
$session = App\Core\Session::getInstance();
$errors = $session->get('errors');
$success = $session->get('success');
$comptes = $session->get('comptes_user') ?? [];

// Debug pour vérifier si les comptes sont récupérés
// var_dump($comptes); // Décommentez temporairement pour débugger
?>

<!-- Overlay du Modal - Fixed pour empêcher le scroll -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-hidden">

    <!-- Container du Modal avec hauteur contrôlée -->
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 relative animate-bounce-in">

        <!-- Bouton de fermeture (lien vers la page précédente) -->
        <a href="/dashboard"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>

        <!-- Messages d'erreur/succès -->
        <?php if (!empty($errors)): ?>
            <div class="absolute -top-16 left-0 right-0 mx-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg animate-bounce-in">
                    <?php foreach ($errors as $error): ?>
                        <p class="text-sm">• <?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="absolute -top-16 left-0 right-0 mx-4">
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg animate-bounce-in">
                    <p class="text-sm"><?= htmlspecialchars($success) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Contenu du formulaire -->
        <div class="p-8">
            <!-- En-tête -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#D7560B]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Effectuer un dépôt</h2>
                <p class="text-gray-600 mt-2">Ajoutez des fonds à votre compte</p>
            </div>

            <!-- Formulaire -->
            <form method="POST" action="/transaction/store" class="space-y-6">

                <!-- Sélection du compte source -->
                <div>
                    <label for="compte_source" class="block text-sm font-semibold text-gray-700 mb-2">
                        Compte source (optionnel)
                    </label>
                    <select name="compte_source" id="compte_source"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-[#D7560B] focus:ring-0 text-sm">
                        <option value="">-- Utiliser le compte principal par défaut --</option>
                        <?php if (!empty($comptes)): ?>
                            <?php foreach ($comptes as $compte): ?>
                                <option value="<?= htmlspecialchars($compte['numerotel']) ?>"
                                    data-solde="<?= htmlspecialchars($compte['solde']) ?>"
                                    data-type="<?= htmlspecialchars($compte['typecompte']) ?>"
                                    data-numero="<?= htmlspecialchars($compte['numero']) ?>"
                                    data-id="<?= htmlspecialchars($compte['id']) ?>">
                                    <?= htmlspecialchars($compte['numero']) ?>
                                    (<?= ucfirst(htmlspecialchars($compte['typecompte'])) ?>) -
                                    <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                                </option> <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <?php if (empty($comptes)): ?>
                        <p class="text-sm text-gray-600 mt-1">Utilisation du compte principal par défaut</p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="montant" class="block text-sm font-semibold text-gray-700 mb-2">
                        Montant à déposer
                    </label>
                    <div class="relative">
                        <input type="number" name="montant" id="montant" step="0.01" min="1" required placeholder="0.00"
                            class="w-full px-4 py-3 pr-16 border-2 border-gray-200 rounded-lg focus:border-[#D7560B] focus:ring-0 transition-colors text-lg outline-none">
                        <span
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium text-sm">FCFA</span>
                    </div>
                </div>

                <!-- Type de transaction -->
                <div>
                    <label for="typetransaction" class="block text-sm font-semibold text-gray-700 mb-2">
                        Type de transaction
                    </label>
                    <select name="typetransaction" id="typetransaction" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-[#D7560B] focus:ring-0 text-sm">
                        <option value="">-- Choisir un type --</option>
                        <option value="depot">Dépôt</option>
                        <option value="retrait">Retrait</option>
                        <option value="paiement">Paiement</option>
                    </select>
                </div>

                <!-- Informations sur les frais -->
                <div id="frais-info" class="bg-orange-50 p-4 rounded-lg border border-orange-200"
                    style="display: none;">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-orange-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,7H13V9H11V7M11,11H13V17H11V11Z" />
                        </svg>
                        <div class="text-sm text-orange-700">
                            <p class="font-medium mb-1">Information sur les frais</p>
                            <p id="frais-details"></p>
                        </div>
                    </div>
                </div>

                <!-- Message d'information général -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,7H13V9H11V7M11,11H13V17H11V11Z" />
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium mb-1">Information importante</p>
                            <p>Le montant sera ajouté immédiatement à votre solde après validation.</p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-3 pt-4">
                    <a href="/dashboard"
                        class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors text-center">
                        Annuler
                    </a>
                    <button type="submit"
                        class="flex-1 bg-[#D7560B] text-white font-semibold py-3 px-4 rounded-lg hover:bg-[#b24509] transition-colors shadow-lg flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                        </svg>
                        Déposer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const compteSelect = document.getElementById('compte_source');
        const typeSelect = document.getElementById('typetransaction');
        const montantInput = document.getElementById('montant');
        const fraisInfo = document.getElementById('frais-info');
        const fraisDetails = document.getElementById('frais-details');

        function calculerFrais() {
            const compteOption = compteSelect.selectedOptions[0];
            const type = typeSelect.value;
            const montant = parseFloat(montantInput.value) || 0;

            if (!compteOption || !type || !montant) {
                fraisInfo.style.display = 'none';
                return;
            }

            const typeCompte = compteOption.dataset.type;
            const solde = parseFloat(compteOption.dataset.solde) || 0;
            let frais = 0;
            let message = '';

            if (type === 'depot') {
                if (typeCompte === 'principal') {
                    frais = montant * 0.0085;
                    message = `Frais de dépôt depuis compte principal: ${frais.toFixed(0)} FCFA (0,85%)`;
                } else {
                    message = 'Pas de frais pour un dépôt depuis un compte secondaire';
                }
            } else if (type === 'depot_principal_to_principal') {
                frais = Math.min(montant * 0.08, 5000);
                message = `Frais de transfert: ${frais.toFixed(0)} FCFA (max 5000 FCFA)`;
            }

            const total = montant + frais;
            if (total > solde) {
                message += ` - ⚠️ Solde insuffisant (besoin: ${total.toFixed(0)} FCFA, disponible: ${solde.toFixed(0)} FCFA)`;
                fraisInfo.className = fraisInfo.className.replace('bg-orange-50 border-orange-200', 'bg-red-50 border-red-200');
                fraisDetails.className = fraisDetails.className.replace('text-orange-700', 'text-red-700');
            } else {
                fraisInfo.className = fraisInfo.className.replace('bg-red-50 border-red-200', 'bg-orange-50 border-orange-200');
                fraisDetails.className = fraisDetails.className.replace('text-red-700', 'text-orange-700');
            }

            fraisDetails.textContent = message;
            fraisInfo.style.display = 'block';
        }

        compteSelect.addEventListener('change', calculerFrais);
        typeSelect.addEventListener('change', calculerFrais);
        montantInput.addEventListener('input', calculerFrais);
    });
</script>

<style>
    /* Empêcher le scroll du body */
    html,
    body {
        overflow: hidden;
        height: 100vh;
    }

    /* Animation d'entrée */
    @keyframes bounce-in {
        0% {
            transform: scale(0.9) translateY(-20px);
            opacity: 0;
        }

        50% {
            transform: scale(1.02) translateY(-10px);
            opacity: 1;
        }

        100% {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-bounce-in {
        animation: bounce-in 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    /* Animation pour l'overlay */
    .fixed.inset-0 {
        animation: fade-in 0.3s ease-out;
    }

    /* Style focus personnalisé pour les inputs */
    input:focus,
    select:focus {
        box-shadow: 0 0 0 3px rgba(215, 86, 11, 0.1);
    }

    /* Hover effects */
    .hover\:bg-gray-200:hover {
        background-color: #e5e7eb;
    }

    .hover\:bg-\[\#b24509\]:hover {
        background-color: #b24509;
    }

    .hover\:text-gray-600:hover {
        color: #4b5563;
    }

    .hover\:bg-gray-100:hover {
        background-color: #f3f4f6;
    }

    /* Responsive adjustments */
    @media (max-height: 600px) {
        .fixed.inset-0.flex {
            align-items: flex-start;
            padding-top: 2rem;
            overflow-y: auto;
        }

        html,
        body {
            overflow-y: auto;
        }
    }

    @media (max-width: 480px) {
        .mx-4 {
            margin-left: 1rem;
            margin-right: 1rem;
        }
    }
</style>