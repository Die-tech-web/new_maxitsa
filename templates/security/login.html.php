<?php
use App\Core\Session;

$session = Session::getInstance();
$errors = $session->get('errors') ?? [];
$session->destroy(key: 'errors');
$this->session->unset('errors');
// $this->session->unset('old');
?>

<?php foreach ($errors as $error): ?>
    <div style="color: red"><?= htmlspecialchars($error) ?></div>
<?php endforeach; ?>


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