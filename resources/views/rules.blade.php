<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Règlement Intérieur - Centrum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans antialiased">

    <nav class="p-6">
        <a href="{{ url('/about') }}" class="flex items-center text-blue-400 hover:text-blue-300 transition">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour à l'équipe
        </a>
    </nav>

    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500 mb-4">
                📜 Règlement Intérieur
            </h1>
            <p class="text-gray-400">Les règles à respecter pour l'utilisation du Data Center.</p>
        </div>

        <div class="space-y-6">
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-blue-500 transition">
                <h3 class="text-xl font-bold text-yellow-400 mb-2">1. Accès Sécurisé</h3>
                <p class="text-gray-300">L'accès aux serveurs est strictement réservé au personnel autorisé. Tout partage de mot de passe est interdit.</p>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-blue-500 transition">
                <h3 class="text-xl font-bold text-yellow-400 mb-2">2. Respect du Matériel</h3>
                <p class="text-gray-300">Il est interdit de modifier la configuration physique des machines sans un ticket validé par un Super Admin.</p>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-blue-500 transition">
                <h3 class="text-xl font-bold text-yellow-400 mb-2">3. Confidentialité</h3>
                <p class="text-gray-300">Les données stockées sur Centrum sont confidentielles. Aucune extraction de données n'est permise sans autorisation.</p>
            </div>
        </div>
        
        <div class="mt-12 text-center text-sm text-gray-500">
            Dernière mise à jour : Janvier 2026
        </div>
    </div>

</body>
</html>