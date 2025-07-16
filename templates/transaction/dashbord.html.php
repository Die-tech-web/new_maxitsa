<?php
$compte = $this->session->get('compte');
$user = $this->session->get('user');
?>

<div class="sticky top-0 bg-white z-10  border-b border-gray-200 pb-6">
    <div class="p-6 space-y-6">
        <div class="bg-gradient-to-r from-white to-gray-50 p-6 rounded-xl shadow-lg border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Solde actuel</h2>
                            <p id="solde" class="text-2xl font-bold text-gray-800">**** FCFA</p>
                        </div>
                        <button id="toggleSolde"
                                class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-black border border-[#D7560B] rounded-md hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B] cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Dépôt</h2>
                            <p class="text-2xl font-bold text-gray-800">Ajouter</p>
                        </div>
                        <div class="w-8 h-8 flex items-center justify-center text-[#D7560B] border border-[#D7560B] rounded-md">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B] cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Retrait</h2>
                            <p class="text-2xl font-bold text-gray-800">Retirer</p>
                        </div>
                        <div class="w-8 h-8 flex items-center justify-center text-[#D7560B] border border-[#D7560B] rounded-md">
                            <i class="fas fa-minus-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B] cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Paiement</h2>
                            <p class="text-2xl font-bold text-gray-800">Payer</p>
                        </div>
                        <div class="w-8 h-8 flex items-center justify-center text-[#D7560B] border border-[#D7560B] rounded-md">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B]">
            <div class="flex items-center justify-between">
                <div class="text-gray-700">
                    <p class="flex items-center gap-2">
                        <span class="text-xs bg-[#D7560B] text-white px-2 py-1 rounded-full flex flex-col items-center">
                            <span>Compte Principal</span>
                            <span class="font-mono"><?= htmlspecialchars($compte['numerotel']) ?></span>
                        </span>
                    </p>
                </div>
                <!-- <div class="flex items-center gap-2"> -->
                    <button class="px-3 py-1 bg-[#D7560B] text-white text-xs rounded hover:bg-[#c65a0b] transition-colors border-l-4 border-white">
                        <i class="fas fa-plus"></i> Ajouter Compte
                    </button>
                    <button class="px-3 py-1 bg-[#D7560B] text-white text-xs rounded hover:bg-[#c65a0b] transition-colors border-l-4 border-white">
                        <i class="fas fa-exchange-alt"></i> Changer
                    </button>
                     <button class="px-3 py-1 bg-[#D7560B] text-white text-xs rounded hover:bg-[#c65a0b] transition-colors border-l-4 border-white">
                        <i class="fas fa-eye"></i> Consulter compte
                    </button>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>

<div class="p-6 pt-0">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-[#D7560B] to-[#D7560B] text-white p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-history"></i>
                    <span id="transactionTitle">10 derniers transactions</span>
                </h3>
                <button id="voirPlusBtn"
                        class="px-4 py-2 bg-white text-[#D7560B] rounded-lg hover:bg-gray-100 transition-colors duration-200 flex items-center gap-2 shadow-md">
                    <i class="fas fa-eye"></i>
                    <span class="font-medium">Voir plus</span>
                </button>
            </div>
        </div>

        <!-- Filtres de recherche -->
        <div id="searchFilters" class="hidden p-4 bg-gray-50 border-b">
            <div class="flex flex-wrap gap-4 items-center">
                <button id="retourBtn" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </button>
                <div class="flex gap-2">
                    <select id="typeFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D7560B]">
                        <option value="">Tous les types</option>
                        <option value="depot">Dépôt</option>
                        <option value="retrait">Retrait</option>
                        <option value="paiement">Paiement</option>
                    </select>
                    <input type="date" id="dateFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D7560B]">
                    <button id="resetFilters" class="px-3 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 font-semibold text-gray-700 px-6 py-4 bg-gray-50 border-b">
            <span class="flex items-center gap-2">
                <i class="fas fa-calendar-alt text-[#D7560B]"></i>
                Date de création
            </span>
            <span class="text-center flex items-center justify-center gap-2">
                <i class="fas fa-coins text-[#D7560B]"></i>
                Montant
            </span>
            <span class="text-right flex items-center justify-end gap-2">
                <i class="fas fa-exchange-alt text-[#D7560B]"></i>
                Type de transaction
            </span>
        </div>

        <div id="transactionsBloc" class="text-sm text-gray-800">
            <?php if (!empty($transactions)): ?>
                <?php foreach (array_slice($transactions, 0, 10) as $t): ?>
                    <?php
                    $type = strtolower($t->getTypeTransaction()->value);
                    $typeColor = match($type) {
                        'depot' => 'text-green-600 bg-green-50',
                        'retrait' => 'text-blue-600 bg-blue-50',
                        'paiement' => 'text-orange-500 bg-orange-50',
                        default => 'text-gray-700 bg-gray-50'
                    };
                    ?>
                    <div class="grid grid-cols-3 px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors items-center">
                        <span class="text-sm font-medium text-gray-600">
                            <?= $t->getDate()->format('d/m/Y') ?>
                        </span>
                        <span class="text-sm font-bold text-center text-gray-800">
                            <?= number_format($t->getMontant(), 0, ',', ' ') ?> FCFA
                        </span>
                        <span class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase <?= $typeColor ?>">
                                <?= htmlspecialchars($t->getTypeTransaction()->value) ?>
                            </span>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucune transaction</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script>
    const solde = <?= json_encode(number_format($compte['solde'], 0, ',', ' ')) ?>;
    const soldeEl = document.getElementById('solde');
    const toggleSoldeBtn = document.getElementById('toggleSolde');
    let soldeVisible = false;

    toggleSoldeBtn.addEventListener('click', () => {
        soldeVisible = !soldeVisible;
        soldeEl.textContent = soldeVisible ? `${solde} FCFA` : '**** FCFA';
        toggleSoldeBtn.innerHTML = `<i class="fas fa-eye${soldeVisible ? '-slash' : ''}"></i>`;
    });

    const voirPlusBtn = document.getElementById('voirPlusBtn');
    const transactionsBloc = document.getElementById('transactionsBloc');
    const searchFilters = document.getElementById('searchFilters');
    const retourBtn = document.getElementById('retourBtn');
    const transactionTitle = document.getElementById('transactionTitle');
    const typeFilter = document.getElementById('typeFilter');
    const dateFilter = document.getElementById('dateFilter');
    const resetFilters = document.getElementById('resetFilters');

    let allTransactions = [];

    voirPlusBtn.addEventListener('click', () => {
        fetch('/transactions/all')
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                allTransactions = data;
                displayTransactions(data);
                searchFilters.classList.remove('hidden');
                voirPlusBtn.style.display = 'none';
                transactionTitle.textContent = 'Toutes les transactions';
            })
            .catch(() => alert('Erreur lors du chargement des transactions'));
    });

    retourBtn.addEventListener('click', () => {
        location.reload();
    });

    function displayTransactions(transactions) {
        const html = transactions.map(t => {
            let typeColor = 'text-gray-700 bg-gray-50';
            switch (t.type.toLowerCase()) {
                case 'depot': typeColor = 'text-green-600 bg-green-50'; break;
                case 'retrait': typeColor = 'text-blue-600 bg-blue-50'; break;
                case 'paiement': typeColor = 'text-orange-500 bg-orange-50'; break;
            }
            return `
                <div class="grid grid-cols-3 px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors items-center">
                    <span class="text-sm font-medium text-gray-600">${t.date}</span>
                    <span class="text-sm font-bold text-center text-gray-800">${t.montant} FCFA</span>
                    <span class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase ${typeColor}">
                            ${t.type}
                        </span>
                    </span>
                </div>
            `;
        }).join('');

        transactionsBloc.innerHTML = html || `
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune transaction trouvée</p>
            </div>
        `;
    }

    function filterTransactions() {
        const typeValue = typeFilter.value.toLowerCase();
        const dateValue = dateFilter.value;

        const filtered = allTransactions.filter(t => {
            const matchType = !typeValue || t.type.toLowerCase() === typeValue;
            const matchDate = !dateValue || t.date.includes(dateValue.split('-').reverse().join('/'));
            return matchType && matchDate;
        });

        displayTransactions(filtered);
    }

    typeFilter.addEventListener('change', filterTransactions);
    dateFilter.addEventListener('change', filterTransactions);

    resetFilters.addEventListener('click', () => {
        typeFilter.value = '';
        dateFilter.value = '';
        displayTransactions(allTransactions);
    });
</script>