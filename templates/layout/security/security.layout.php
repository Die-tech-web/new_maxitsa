<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAX IT SA - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-orange-500 min-h-screen flex items-center justify-center">
    <div class="flex w-full max-w-6xl mx-auto">
        <!-- Formulaire de connexion -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md">
                <!-- Logo/Titre -->
                <div class="text-center mb-8">
                    <div class="inline-block bg-gray-100 rounded-2xl px-6 py-3">
                        <h1 class="text-xl font-bold text-gray-800">MAX IT</h1>
                        <p class="text-orange-500 text-sm font-medium">SA</p>
                    </div>
                </div>

                <!-- Formulaire -->
                <?php echo $content; ?>

            </div>
        </div>


    </div>
</body>

</html>