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
            <button
                class="w-full flex items-center justify-start gap-3 bg-[#D7560B] px-3 py-2 rounded text-white font-bold shadow border-l-2 border-white">
                <i class="fa-solid fa-house text-xl"></i> HOME
            </button>
            <button
                class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded font-semibold shadow border-l-2"
                style="border-left-color: #D7560B">
                <i class="fa-solid fa-user text-xl"></i> Mes Comptes
            </button>
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