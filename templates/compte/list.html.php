<?php

$compte = $this->session->get('compte');
$user = $this->session->get('user');

$session = \App\Core\Session::getInstance();
$success = $session->get('success');

if ($success): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        <?= $success ?>
    </div>
    <?php $session->unset('success'); ?>
<?php endif; ?>

<div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B] mt-4">
    <h2 class="text-xl font-bold text-[#D7560B] mb-4">
        <i class="fas fa-credit-card mr-2"></i>
        Mes Comptes
    </h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-hashtag mr-1"></i>
                        Numéro de compte
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-phone mr-1"></i>
                        Numéro téléphone
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-tag mr-1"></i>
                        Type de compte
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-wallet mr-1"></i>
                        Solde
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-exchange-alt mr-1"></i>
                        Basculer
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                usort($comptes, function ($a, $b) {
                    $aPrincipal = ($a['typecompte'] === 'principal');
                    $bPrincipal = ($b['typecompte'] === 'principal');
                    return $aPrincipal === $bPrincipal ? 0 : ($aPrincipal ? -1 : 1);
                });
                ?>

                <?php foreach ($comptes as $index => $c): ?>
                    <?php $isPrincipal = ($c['typecompte'] === 'principal'); ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-200 <?= $isPrincipal ? 'bg-orange-50 border-l-4 border-[#D7560B]' : '' ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <?php if ($isPrincipal): ?>
                                    <i class="fas fa-star text-[#D7560B] mr-2" title="Compte Principal"></i>
                                <?php endif; ?>
                                <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($c['numero']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900"><?= htmlspecialchars($c['numerotel']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $isPrincipal ? 'bg-[#D7560B] text-white' : 'bg-blue-100 text-blue-800' ?>">
                                <?= ucfirst(htmlspecialchars($c['typecompte'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-semibold text-gray-900" id="solde-<?= $index ?>"><?= number_format($c['solde'], 0, ',', ' ') ?> FCFA</span>
                                <button 
                                    onclick="toggleSoldeVisibility(<?= $index ?>)" 
                                    class="ml-2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                    title="Masquer/Afficher le solde"
                                >
                                    <i class="fas fa-eye" id="eye-icon-<?= $index ?>"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if (!$isPrincipal): ?>
                                <form method="POST" action="/compte/basculer-principal" style="display: inline;">
                                    <input type="hidden" name="compte_id" value="<?= $c['id'] ?>">
                                    <button 
                                        type="submit"
                                        onclick="return confirm('Êtes-vous sûr de vouloir définir ce compte comme compte principal ?')"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-[#D7560B] hover:bg-[#B8490A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D7560B] transition-all duration-200 transform hover:scale-105"
                                        title="Définir comme compte principal"
                                    >
                                        <i class="fas fa-exchange-alt mr-1"></i>
                                        Changer
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-[#D7560B] bg-orange-100 rounded-md">
                                    <i class="fas fa-crown mr-1"></i>
                                    Principal
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <i class="fas fa-star text-[#D7560B] mr-1"></i>
                <span>Compte Principal</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-eye mr-1"></i>
                <span>Cliquez pour masquer/afficher le solde</span>
            </div>
        </div>
        <div class="text-right">
            <span class="font-medium">Total des comptes: <?= count($comptes) ?></span>
        </div>
    </div>
</div>

<script>
    <?php if ($compte): ?>
    const solde = <?= json_encode(number_format($compte['solde'], 0, ',', ' ')) ?>;
    const soldeEl = document.getElementById('solde');
    const toggleSoldeBtn = document.getElementById('toggleSolde');
    let soldeVisible = false;

    if (toggleSoldeBtn) {
        toggleSoldeBtn.addEventListener('click', () => {
            soldeVisible = !soldeVisible;
            soldeEl.textContent = soldeVisible ? `${solde} FCFA` : '**** FCFA';
            toggleSoldeBtn.innerHTML = `<i class="fas fa-eye${soldeVisible ? '-slash' : ''}"></i>`;
        });
    }
    <?php endif; ?>

    function toggleSoldeVisibility(index) {
        const eyeIcon = document.getElementById(`eye-icon-${index}`);
        const soldeSpan = document.getElementById(`solde-${index}`);
        
        if (eyeIcon.classList.contains('fa-eye')) {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
            soldeSpan.setAttribute('data-original-solde', soldeSpan.textContent);
            soldeSpan.textContent = '**** FCFA';
        } else {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
            const originalSolde = soldeSpan.getAttribute('data-original-solde');
            if (originalSolde) {
                soldeSpan.textContent = originalSolde;
            }
        }
    }
</script>

<style>
    .table-hover tr:hover {
        background-color: #f8f9fa;
    }
    
    .btn-principal {
        background: linear-gradient(135deg, #D7560B 0%, #B8490A 100%);
        box-shadow: 0 2px 4px rgba(215, 86, 11, 0.2);
    }
    
    .btn-principal:hover {
        box-shadow: 0 4px 8px rgba(215, 86, 11, 0.3);
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .animate-pulse-hover:hover {
        animation: pulse 0.3s ease-in-out;
    }
</style>