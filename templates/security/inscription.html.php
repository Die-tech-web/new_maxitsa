<?php if (!empty($_SESSION['message'])): ?>
  <div class="alert <?= $_SESSION['type'] ?>">
    <?= $_SESSION['message'] ?>
  </div>
  <?php unset($_SESSION['message'], $_SESSION['type']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['errors'])): ?>
  <ul class="text-danger">
    <?php foreach ($_SESSION['errors'] as $champ => $msg): ?>
      <li><?= htmlspecialchars($msg) ?></li>
    <?php endforeach; ?>
    <?php unset($_SESSION['errors']); ?>
  </ul>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Créer Compte MAXITSA</title>
  <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #C4782A 0%, #E59A47 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            max-width: 70rem;
            width: 100%;
            backdrop-filter: blur(10px);
        }

        .title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #C4782A;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .form-column {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-field {
            width: 100%;
            border: 2px solid #C4782A;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            color: #374151;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .input-field:focus {
            outline: none;
            border-color: #8B5A2B;
            box-shadow: 0 0 0 4px rgba(196, 120, 42, 0.1);
            background: white;
        }

        .input-field::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .upload-area {
            border: 2px dashed #C4782A;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f9fafb, #f3f4f6);
            position: relative;
            overflow: hidden;
        }

        .upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(196, 120, 42, 0.05), rgba(229, 154, 71, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .upload-area:hover::before {
            opacity: 1;
        }

        .upload-area:hover {
            border-color: #8B5A2B;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(196, 120, 42, 0.15);
        }

        .upload-icon {
            font-size: 2.5rem;
            color: #C4782A;
            margin-bottom: 1rem;
        }

        .upload-text {
            color: #374151;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            z-index: 1;
        }

        .upload-btn {
            background: linear-gradient(135deg, #C4782A, #E59A47);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(196, 120, 42, 0.3);
        }

        .upload-btn.uploaded {
            background: linear-gradient(135deg, #8B5A2B, #6B4423);
        }

        .hidden {
            display: none;
        }

        .submit-area {
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .submit-btn {
            background: linear-gradient(135deg, #6B4423, #8B5A2B);
            color: white;
            font-weight: 700;
            padding: 1rem 3rem;
            border-radius: 1rem;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(107, 68, 35, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(107, 68, 35, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        /* Messages d'alerte */
        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 1rem;
            font-weight: 500;
            border: none;
        }

        .alert.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .alert.error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .text-danger {
            color: #dc2626;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .title h1 {
                font-size: 2rem;
            }

            .upload-area {
                min-height: 150px;
                padding: 1.5rem;
            }
        }

        /* Animation pour les champs */
        .input-field {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Style pour les champs requis */
        .input-field:required:invalid {
            border-color: #C4782A;
        }

        .input-field:required:valid {
            border-color: #C4782A;
        }
    </style>
</head>
<body>
  <div class="container">
    <div class="title">
      <h1>CRÉER VOTRE COMPTE</h1>
      <p class="subtitle">Rejoignez MAXITSA dès maintenant</p>
    </div>
    
    <form method="POST" action="/register" enctype="multipart/form-data">
      <div class="form-grid">
        <div class="form-column">

          <div class="input-group">
            <input 
              type="text" 
              name="nom" 
              placeholder="👤 Nom de famille" 
              class="input-field"
              required 
              value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
            />
          </div>

          <div class="input-group">
            <input 
              type="text" 
              name="prenom" 
              placeholder="👤 Prénom" 
              class="input-field"
              required 
              value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
            />
          </div>

           <div class="input-group">
            <input 
              type="log" 
              name="login" 
              placeholder="📱login" 
              class="input-field"
              required 
              value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
            />
          </div>

          <div class="input-group">
            <input 
              type="password" 
              name="password" 
              placeholder="🔒 Mot de passe (min. 6 caractères)" 
              class="input-field"
              required 
              minlength="6"
            />
          </div>

          <div class="input-group">
            <input 
              type="password" 
              name="confirm_password" 
              placeholder="🔒 Confirmer le mot de passe" 
              class="input-field"
              required 
            />
          </div>

          <div class="input-group">
            <input 
              type="text" 
              name="cni" 
              placeholder="🆔 Numéro CNI" 
              class="input-field"
              required 
              value="<?= htmlspecialchars($_POST['cni'] ?? '') ?>"
            />
          </div>

          <div class="input-group">
            <input 
              type="text" 
              name="adresse" 
              placeholder="🏠 Adresse complète" 
              class="input-field"
              required 
              value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>"
            />
          </div>
        </div>

        <div class="form-column">
          <div class="upload-area">
            <div class="upload-icon">📷</div>
            <p class="upload-text">Photo CNI - Recto</p>
            <button 
              type="button" 
              class="upload-btn"
              onclick="document.getElementById('photo-recto').click()"
            >
              Choisir fichier
            </button>
            <input type="file" id="photo-recto" name="photo_recto" accept="image/*" class="hidden" required />
          </div>

          <div class="upload-area">
            <div class="upload-icon">📷</div>
            <p class="upload-text">Photo CNI - Verso</p>
            <button 
              type="button" 
              class="upload-btn"
              onclick="document.getElementById('photo-verso').click()"
            >
              Choisir fichier
            </button>
            <input type="file" id="photo-verso" name="photo_verso" accept="image/*" class="hidden" required />
          </div>
        </div>

        <div class="submit-area">
          <button type="submit" class="submit-btn">Créer mon compte</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    document.querySelectorAll('.input-field').forEach((field, index) => {
      field.style.animationDelay = `${index * 0.1}s`;
    });

    document.getElementById('photo-recto').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const button = e.target.previousElementSibling;
        const fileName = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
        button.textContent = `✅ ${fileName}`;
        button.classList.add('uploaded');
        
        button.parentElement.style.borderColor = '#8B5A2B';
        button.parentElement.querySelector('.upload-icon').textContent = '✅';
        button.parentElement.querySelector('.upload-text').textContent = 'Photo recto sélectionnée';
      }
    });

    document.getElementById('photo-verso').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const button = e.target.previousElementSibling;
        const fileName = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
        button.textContent = `✅ ${fileName}`;
        button.classList.add('uploaded');
        
        button.parentElement.style.borderColor = '#8B5A2B';
        button.parentElement.querySelector('.upload-icon').textContent = '✅';
        button.parentElement.querySelector('.upload-text').textContent = 'Photo verso sélectionnée';
      }
    });

    document.querySelector('input[name="confirm_password"]').addEventListener('input', function() {
      const password = document.querySelector('input[name="password"]').value;
      const confirmPassword = this.value;
      
      if (confirmPassword && password !== confirmPassword) {
        this.style.borderColor = '#C4782A';
      } else if (confirmPassword && password === confirmPassword) {
        this.style.borderColor = '#8B5A2B';
      }
    });

    document.querySelector('.submit-btn').addEventListener('click', function(e) {
      this.style.transform = 'scale(0.95)';
      setTimeout(() => {
        this.style.transform = '';
      }, 150);
    });
  </script>
</body>
</html>