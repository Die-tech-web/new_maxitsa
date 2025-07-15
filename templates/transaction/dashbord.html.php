<?php
$compte = $this->session->get('compte');
$user = $this->session->get('user');
?>

<div class="p-6 space-y-6">

    <!-- Solde avec œil -->
    <div class="flex justify-start">
        <div class="bg-white p-4 rounded-md shadow border-l-4 border-[#8B4513] w-full max-w-xs">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-600 text-sm">Solde actuel</h2>
                    <p id="solde" class="text-2xl font-bold">**** FCFA</p>
                </div>
                <button id="toggleSolde"
                    class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-black border border-[#8B4513] rounded-md">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Numéro du client connecté -->
    <div class="text-gray-700">
        <p><span class="font-semibold">Numéro :</span> <?= htmlspecialchars($compte['numerotel']) ?></p>
    </div>

    <!-- Transactions avec encadré -->
    <div class="mt-8 bg-white border border-gray-300 rounded-md shadow-md">
        <!-- Titre en texte simple -->
        <div class="px-4 pt-4">
            <h3 class="text-lg font-semibold text-[#8B4513]">10 dernières transactions</h3>
        </div>

        <!-- Bloc transactions affiché directement -->
        <div id="transactionsBloc" class="divide-y divide-gray-300 text-sm text-gray-800">
            <?php if (!empty($transactions)): ?>
                <?php foreach (array_slice($transactions, 0, 10) as $t): ?>
                    <?php
                        $type = strtolower($t->getTypeTransaction()->value);
                        $typeColor = match($type) {
                            'depot'     => 'text-green-600',
                            'retrait'   => 'text-blue-600',
                            'paiement'  => 'text-orange-500',
                            default     => 'text-gray-700'
                        };
                    ?>
                    <div class="flex justify-between items-center px-4 py-3">
                        <span class="text-xs"><?= $t->getDate()->format('d/m/Y') ?></span>
                        <span class="text-sm font-medium"><?= number_format($t->getMontant(), 0, ',', ' ') ?> FCFA</span>
                        <span class="text-xs font-semibold uppercase <?= $typeColor ?>">
                            <?= htmlspecialchars($t->getTypeTransaction()->value) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 px-4 py-3">Aucune transaction</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bouton Voir plus -->
    <div class="flex justify-center mt-4">
        <button id="voirPlusBtn" class="px-4 py-2 bg-[#8B4513] text-white rounded hover:bg-[#6f3610] transition">
            Voir plus
        </button>
    </div>

</div>

<!-- Scripts -->
<script>
    // Toggle du solde
    const solde = <?= json_encode(number_format($compte['solde'], 0, ',', ' ')) ?>;
    const soldeEl = document.getElementById('solde');
    const toggleSoldeBtn = document.getElementById('toggleSolde');
    let soldeVisible = false;

    toggleSoldeBtn.addEventListener('click', () => {
        soldeVisible = !soldeVisible;
        soldeEl.textContent = soldeVisible ? `${solde} FCFA` : '**** FCFA';
        toggleSoldeBtn.innerHTML = `<i class="fas fa-eye${soldeVisible ? '-slash' : ''}"></i>`;
    });

    // Voir plus : charger toutes les transactions en AJAX
    const voirPlusBtn = document.getElementById('voirPlusBtn');
    const transactionsBloc = document.getElementById('transactionsBloc');

    voirPlusBtn.addEventListener('click', () => {
        fetch('/transactions/all')
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const html = data.map(t => {
                    let typeColor = 'text-gray-700';
                    switch (t.type.toLowerCase()) {
                        case 'depot': typeColor = 'text-green-600'; break;
                        case 'retrait': typeColor = 'text-blue-600'; break;
                        case 'paiement': typeColor = 'text-orange-500'; break;
                    }
                    return `
                        <div class="flex justify-between items-center px-4 py-3">
                            <span class="text-xs">${t.date}</span>
                            <span class="text-sm font-medium">${t.montant} FCFA</span>
                            <span class="text-xs font-semibold uppercase ${typeColor}">${t.type}</span>
                        </div>
                    `;
                }).join('');
                transactionsBloc.innerHTML = html;

                // Désactiver le bouton
                voirPlusBtn.disabled = true;
                // voirPlusBtn.textContent = 'Chargé';
                // voirPlusBtn.classList.add('opacity-50', 'cursor-not-allowed');
            })
            .catch(() => alert('Erreur lors du chargement des transactions'));
    });
</script>
