<?php use App\Core\Session;  $session = Session::getInstance(); $errors = $session->get('errors') ?? []; $session->destroy(key: 'errors'); $this->session->unset('errors'); ?>

<?php foreach ($errors as $error): ?>
    <div style="color: red"><?= htmlspecialchars($error) ?></div>
<?php endforeach; ?>

<style>
.test-credentials {
    border-left: 4px solid #f97316;
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.1);
    background: linear-gradient(135deg, #fff7ed 0%, #ffffff 100%);
}
.copy-btn {
    transition: all 0.2s ease;
}
.copy-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(249, 115, 22, 0.3);
}
.credential-item {
    background: rgba(249, 115, 22, 0.05);
    border: 1px solid rgba(249, 115, 22, 0.1);
}
</style>

<form class="space-y-6" action="/auth" method="post">
    <?php if (!empty($errors['global'])): ?>
        <div class="mb-4 text-red-600 font-semibold text-center">
            <?= htmlspecialchars($errors['global']) ?>
        </div>
    <?php endif; ?>

    <div>
        <label class="block text-gray-700 text-sm font-medium mb-2">
            login:
        </label>
        <input type="tel" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
            class="w-full px-4 py-3 rounded-xl border <?= !empty($errors['login']) ? 'border-red-500' : 'border-gray-200' ?> focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            placeholder="">

        <?php if (!empty($errors['login'])): ?>
            <p class="mt-1 text-red-600 text-sm"><?= htmlspecialchars($errors['login']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label class="block text-gray-700 text-sm font-medium mb-2">
            Mot de passe:
        </label>
        <input type="password" name="password"
            class="w-full px-4 py-3 rounded-xl border <?= !empty($errors['password']) ? 'border-red-500' : 'border-gray-200' ?> focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            placeholder="">

        <?php if (!empty($errors['password'])): ?>
            <p class="mt-1 text-red-600 text-sm"><?= htmlspecialchars($errors['password']) ?></p>
        <?php endif; ?>
    </div>

    <div class="text-left">
        <a href="#" class="text-gray-600 text-sm hover:text-orange-500 transition-colors">
            Mot de passe oublié?
        </a>
    </div>

    <button type="submit"
        class="w-full bg-orange-500 text-white py-3 rounded-xl font-semibold hover:bg-orange-600 transition-colors">
        Connexion
    </button>

    <div class="text-center text-sm text-gray-600">
        vous n'avez pas de compte ?
        <a href="/inscription" class="text-orange-500 hover:text-orange-600 transition-colors">s'inscrire</a>
    </div>
</form>

<!-- Comptes de test en bas -->
<div class="mt-8 p-6 rounded-xl test-credentials">
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
            <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Comptes de Test
        </h3>
        <p class="text-sm text-gray-600 mb-4">Utilisez ces identifiants pour tester l'application</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="credential-item p-4 rounded-lg">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Login</label>
            <div class="flex items-center justify-between">
                <code class="text-sm font-mono text-gray-800 bg-white px-2 py-1 rounded border flex-1 mr-2" id="test-login">die_6887cc818cf3e</code>
                <button type="button" onclick="copyToField('test-login', 'login')" 
                    class="copy-btn bg-orange-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-orange-600">
                    Copier
                </button>
            </div>
        </div>

        <div class="credential-item p-4 rounded-lg">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Mot de passe</label>
            <div class="flex items-center justify-between">
                <code class="text-sm font-mono text-gray-800 bg-white px-2 py-1 rounded border flex-1 mr-2" id="test-password">passer123</code>
                <button type="button" onclick="copyToField('test-password', 'password')" 
                    class="copy-btn bg-orange-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-orange-600">
                    Copier
                </button>
            </div>
        </div>
    </div>

    <div class="mb-4 p-3 bg-orange-50 rounded-lg border border-orange-100">
        <div class="flex items-start">
            <svg class="w-4 h-4 text-orange-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-orange-800 mb-1">Note pour les tests</h4>
                <p class="text-xs text-orange-700">Ces identifiants sont destinés uniquement aux tests et à la démonstration.</p>
            </div>
        </div>
    </div>

    <button type="button" onclick="fillTestCredentials()" 
        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2.5 rounded-xl font-medium hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Remplir automatiquement
    </button>
</div>

<script>
function copyToField(sourceId, targetFieldName) {
    const sourceElement = document.getElementById(sourceId);
    const text = sourceElement.textContent;
    const targetInput = document.querySelector(`input[name="${targetFieldName}"]`);
    
    if (targetInput) {
        targetInput.value = text;
        targetInput.focus();
        
        // Animation de confirmation
        targetInput.classList.add('ring-2', 'ring-green-500');
        setTimeout(() => {
            targetInput.classList.remove('ring-2', 'ring-green-500');
        }, 1500);
    }
    
    // Feedback visuel sur le bouton
    const button = sourceElement.nextElementSibling;
    const originalText = button.textContent;
    button.textContent = '✓ Copié';
    button.classList.add('bg-green-500');
    button.classList.remove('bg-orange-500');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-500');
        button.classList.add('bg-orange-500');
    }, 2000);
}

function fillTestCredentials() {
    const loginInput = document.querySelector('input[name="login"]');
    const passwordInput = document.querySelector('input[name="password"]');
    
    if (loginInput && passwordInput) {
        loginInput.value = 'die_6887cc818cf3e';
        passwordInput.value = 'passer123';
        
        // Animation de confirmation
        loginInput.classList.add('ring-2', 'ring-green-500');
        passwordInput.classList.add('ring-2', 'ring-green-500');
        
        setTimeout(() => {
            loginInput.classList.remove('ring-2', 'ring-green-500');
            passwordInput.classList.remove('ring-2', 'ring-green-500');
        }, 2000);
        
        loginInput.focus();
    }
}
</script>