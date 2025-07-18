<?php
$session = App\Core\Session::getInstance();
$errors = $session->get('errors');
$success = $session->get('success');?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MAXITSA - Accueil Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body class="flex h-screen bg-gray-100 font-sans overflow-hidden">
    <aside class="w-48 bg-[#D7560B] text-white flex flex-col h-full">
        <div class="text-white-700 items-center">
            <p><span class="font-semibold">👤Numéro▼: </span> <?= htmlspecialchars($compte['numerotel']) ?></p>
        </div>


        <nav class="flex flex-col gap-4 p-3 flex-1 justify-center">
            <a href="/dashbord">
                <button
                    class="w-full flex items-center justify-start gap-3 bg-[#D7560B] px-3 py-2 rounded text-white font-bold shadow border-l-2 border-white">
                    <i class="fa-solid fa-house text-xl"></i> HOME
                </button>
            </a>
            <a href="/compte/list">
                <button
                    class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded font-semibold shadow border-l-2"
                    style="border-left-color: #D7560B">
                    <i class="fa-solid fa-user text-xl"></i> Mes Comptes
                </button>
            </a>
            <button
                class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded font-semibold shadow border-l-2"
                style="border-left-color: #D7560B">
                <i class="fa-solid fa-money-bill-transfer text-xl"></i> Paiements
            </button>
            <button
                class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded font-semibold shadow border-l-2"
                style="border-left-color: #D7560B">
                <i class="fa-solid fa-wallet text-xl"></i> Solde
            </button>
        </nav>
        <div class="p-3">
            <a href="/logout">
                <button class="w-full bg-white text-black px-3 py-2 rounded text-sm flex items-center gap-2">
                    <i class="fa-solid fa-right-from-bracket"></i> Deconnexion
                </button>
            </a>

        </div>
    </aside>

    <main class="flex-1 bg-white flex flex-col h-full">
        <header class="flex justify-between items-center p-4 bg-white border-b">
            <div>
                <h1 class="text-2xl font-bold">MAXITSA</h1>
                <p class="text-gray-600 text-sm">Systèmes de transfert et de paiements</p>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span>🔄</span>
                <span class="font-medium text-gray-800">
                    <?php
                    $user = $this->session->get('user');
                    if ($user && isset($user['nom']) && isset($user['prenom'])) {
                        echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']);
                    } else {
                        echo 'Client';
                    }
                    ?>
                </span>
                <span class="text-lg">👤</span>
            </div>

        </header>
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

                        <div
                            class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B] cursor-pointer hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-gray-600 text-sm font-medium">Dépôt</h2>
                                    <p class="text-2xl font-bold text-gray-800">Ajouter</p>
                                </div>
                                <div
                                    class="w-8 h-8 flex items-center justify-center text-[#D7560B] border border-[#D7560B] rounded-md">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B] cursor-pointer hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-gray-600 text-sm font-medium">Retrait</h2>
                                    <p class="text-2xl font-bold text-gray-800">Retirer</p>
                                </div>
                                <div
                                    class="w-8 h-8 flex items-center justify-center text-[#D7560B] border border-[#D7560B] rounded-md">
                                    <i class="fas fa-minus-circle"></i>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white p-4 rounded-lg shadow-md border-l-4 border-[#D7560B] cursor-pointer hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-gray-600 text-sm font-medium">Paiement</h2>
                                    <p class="text-2xl font-bold text-gray-800">Payer</p>
                                </div>
                                <div
                                    class="w-8 h-8 flex items-center justify-center text-[#D7560B] border border-[#D7560B] rounded-md">
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
                                <span
                                    class="text-xs bg-[#D7560B] text-white px-2 py-1 rounded-full flex flex-col items-center">
                                    <span>Compte Principal</span>
                                    <span class="font-mono"><?= htmlspecialchars($compte['numerotel']) ?></span>
                                </span>
                            </p>
                        </div>
                       
                        <button
                            class="px-3 py-1 bg-[#D7560B] text-white text-xs rounded hover:bg-[#c65a0b] transition-colors border-l-4 border-white">
                            <i class="fas fa-plus"></i> Ajouter Compte
                        </button>
                        <div id="popupCompte"
                            class="hidden fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center z-50">
                            <form method="post" action="/compte/ajouter-secondaire"
                                class="bg-white p-6 rounded-lg shadow-md w-[350px]">
                                <h2 class="text-lg font-bold mb-4 text-[#D7560B]">Ajouter Compte secondaire</h2>
                                <input type="text" name="numerotel" placeholder="Numéro de téléphone"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-[#D7560B]"
                                    required />
                                     <h2 class="text-lg font-bold mb-4 text-[#D7560B]">Ajouter solde</h2>
                                <input type="text" name="solde" placeholder="ajouter solde"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-[#D7560B]"
                                    required />
                                <?php if (!empty($errors['numerotel'])): ?>
                                    <p class="mt-1 text-red-600 text-sm"><?= htmlspecialchars($errors['numerotel']) ?></p>
                                <?php endif; ?>
                                <input type="hidden" name="typecompte" value="secondaire" />
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                        onclick="document.getElementById('popupCompte').classList.add('hidden')"
                                        class="px-3 py-2 bg-gray-400 text-white rounded">Annuler</button>
                                    <button type="submit"
                                        class="px-3 py-2 bg-[#D7560B] text-white rounded">Ajouter</button>
                                </div>
                            </form>
                        </div>

                        <!-- Script pour ouvrir le popup -->
                        <script>
                            const btnAjouterCompte = [...document.querySelectorAll('button')].find(btn =>
                                btn.innerHTML.includes('fa-plus') && btn.textContent.includes('Ajouter Compte')
                            );
                            if (btnAjouterCompte) {
                                btnAjouterCompte.addEventListener('click', () => {
                                    document.getElementById('popupCompte').classList.remove('hidden');
                                });
                            }
                        </script>
                        <button
                            class="px-3 py-1 bg-[#D7560B] text-white text-xs rounded hover:bg-[#c65a0b] transition-colors border-l-4 border-white">
                            <i class="fas fa-eye"></i> Consulter compte
                        </button>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <?php
            // var_dump($content);
            // die;
            echo $content;
            ?>
        </div>
    </main>



</body>

</html>
