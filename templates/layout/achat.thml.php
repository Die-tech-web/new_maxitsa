<form method="POST" action="/layout/security/woyofal/acheter" class="space-y-6" id="woyofalForm">
                
                <!-- Sélection du compte de paiement -->
                <div>
                    <label for="compte_paiement" class="block text-sm font-semibold text-gray-700 mb-2">
                        Compte de paiement
                    </label>
                    <select name="compte_paiement" id="compte_paiement" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:ring-0 text-sm">
                        <option value="">-- Choisir un compte --</option>
                        <?php if (!empty($comptes)): ?>
                            <?php foreach ($comptes as $compte): ?>
                                <option value="<?= htmlspecialchars($compte['numerotel']) ?>" 
                                        data-solde="<?= htmlspecialchars($compte['solde']) ?>" 
                                        data-type="<?= htmlspecialchars($compte['typecompte']) ?>">
                                    <?= htmlspecialchars($compte['numero']) ?> 
                                    (<?= ucfirst(htmlspecialchars($compte['typecompte'])) ?>) - 
                                    <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Numéro de compteur -->
                <div>
                    <label for="compteur" class="block text-sm font-semibold text-gray-700 mb-2">
                        Numéro de compteur
                    </label>
                    <input type="text" name="compteur" id="compteur" required 
                           placeholder="Ex: CPT123456"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:ring-0 transition-colors text-lg outline-none">
                </div>

                <!-- Nom du client -->
                <div>
                    <label for="client" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nom du client
                    </label>
                    <input type="text" name="client" id="client" required 
                           placeholder="Nom complet du client"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:ring-0 transition-colors text-lg outline-none">
                </div>

                <!-- Montant -->
                <div>
                    <label for="montant" class="block text-sm font-semibold text-gray-700 mb-2">
                        Montant à acheter
                    </label>
                    <div class="relative">
                        <input type="number" name="montant" id="montant" step="1" min="100" required placeholder="0"
                            class="w-full px-4 py-3 pr-16 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:ring-0 transition-colors text-lg outline-none">
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium text-sm">FCFA</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Montant minimum: 100 FCFA</p>
                </div>

                <!-- Tranche (optionnel) -->
                <div>
                    <label for="tranche" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tranche tarifaire
                    </label>
                    <select name="tranche" id="tranche"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:ring-0 text-sm">
                        <option value="Tranche 1">Tranche 1 (Par défaut)</option>
                        <option value="Tranche 2">Tranche 2</option>
                        <option value="Tranche 3">Tranche 3</option>
                    </select>
                </div>

                <!-- Informations sur le solde -->
                <div id="solde-info" class="bg-blue-50 p-4 rounded-lg border border-blue-200" style="display: none;">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,7H13V9H11V7M11,11H13V17H11V11Z" />
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium mb-1">Information sur le solde</p>
                            <p id="solde-details"></p>
                        </div>
                    </div>
                </div>

                <!-- Message d'information général -->
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2A7,7 0 0,1 19,9C19,11.38 17.81,13.47 16,14.74V17A1,1 0 0,1 15,18H9A1,1 0 0,1 8,17V14.74C6.19,13.47 5,11.38 5,9A7,7 0 0,1 12,2M9,21V20H15V21A1,1 0 0,1 14,22H10A1,1 0 0,1 9,21M12,4A5,5 0 0,0 7,9C7,10.68 7.84,12.16 9.15,13L9.85,13.39V16H14.15V13.39L14.85,13C16.16,12.16 17,10.68 17,9A5,5 0 0,0 12,4Z" />
                        </svg>
                        <div class="text-sm text-yellow-700">
                            <p class="font-medium mb-1">Information importante</p>
                            <p>Vous recevrez un code de rechargement après validation du paiement.</p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-3 pt-4">
                    <a href="/dashboard"
                        class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors text-center">
                        Annuler
                    </a>
                    <button type="submit" id="submitBtn"
                        class="flex-1 bg-yellow-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-yellow-700 transition-colors shadow-lg flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2A7,7 0 0,1 19,9C19,11.38 17.81,13.47 16,14.74V17A1,1 0 0,1 15,18H9A1,1 0 0,1 8,17V14.74C6.19,13.47 5,11.38 5,9A7,7 0 0,1 12,2M9,21V20H15V21A1,1 0 0,1 14,22H10A1,1 0 0,1 9,21M12,4A5,5 0 0,0 7,9C7,10.68 7.84,12.16 9.15,13L9.85,13.39V16H14.15V13.39L14.85,13C16.16,12.16 17,10.68 17,9A5,5 0 0,0 12,4Z" />
                        </svg>
                        <span id="submitText">Acheter</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>