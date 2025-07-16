<?php
namespace App\Core;

class Validator
{

}





























































// namespace App\Core;

// use App\Config\ErrorsEnum\ErrorsEnumFr;

// class Validator
// {
//     public static function validateConnexion(array $data): array
//     {
//         $errors = [];

//         if (empty($data['telephone'])) {
//             $errors['telephone'] = ErrorsEnumFr::format(ErrorsEnumFr::REQUIRED, ['field' => 'téléphone']);
//         } elseif (!preg_match('/^(?:\+221)?(77|78|76|70|75)[0-9]{7}$/', $data['telephone'])) {
//             $errors['telephone'] = ErrorsEnumFr::INVALID_PHONE;
//         }

//         if (empty($data['password'])) {
//             $errors['password'] = ErrorsEnumFr::PASSWORD_REQUIRED;
//         }

//         return $errors;
//     }

//     public static function validateInscription(array $data, \PDO $pdo): array
//     {
//         $errors = [];

//         // Champs et leurs labels pour les messages
//         $fieldNames = [
//             'nom' => 'Nom',
//             'prenom' => 'Prénom',
//             'telephone' => 'Téléphone',
//             'cni' => 'CNI',
//             'adresse' => 'Adresse',
//         ];

//         // Champs requis
//         foreach ($fieldNames as $field => $label) {
//             if (empty($data[$field])) {
//                 $errors[$field] = ErrorsEnumFr::format(ErrorsEnumFr::REQUIRED, ['field' => $label]);
//             }
//         }

//         // Vérification mot de passe
//         if (empty($data['password'])) {
//             $errors['password'] = ErrorsEnumFr::PASSWORD_REQUIRED;
//         } elseif (strlen($data['password']) < 6) {
//             $errors['password'] = ErrorsEnumFr::PASSWORD_MIN_LENGTH;
//         }

//         // Nettoyage numéro téléphone
//         $numero = isset($data['telephone']) ? preg_replace('/^\+221/', '', $data['telephone']) : '';

//         // Format téléphone
//         if (!empty($numero) && !preg_match('/^(77|78|76|70|75)[0-9]{7}$/', $numero)) {
//             $errors['telephone'] = ErrorsEnumFr::INVALID_PHONE;
//         }

//         // Téléphone déjà existant ?
//         if (!isset($errors['telephone']) && !empty($numero)) {
//             $stmt = $pdo->prepare("SELECT COUNT(*) FROM client WHERE telephone = :telephone");
//             $stmt->execute([':telephone' => $numero]);
//             if ($stmt->fetchColumn() > 0) {
//                 $errors['telephone'] = ErrorsEnumFr::DUPLICATE_PHONE;
//             }
//         }

//         // Format CNI
//         if (!empty($data['cni']) && (!ctype_digit($data['cni']) || strlen($data['cni']) !== 13)) {
//             $errors['cni'] = ErrorsEnumFr::INVALID_CNI;
//         }

//         // CNI déjà existante ?
//         if (!isset($errors['cni']) && !empty($data['cni'])) {
//             $stmt = $pdo->prepare("SELECT COUNT(*) FROM client WHERE cni = :cni");
//             $stmt->execute([':cni' => $data['cni']]);
//             if ($stmt->fetchColumn() > 0) {
//                 $errors['cni'] = ErrorsEnumFr::DUPLICATE_CNI;
//             }
//         }

//         // Vérification photo recto
//         if (
//             !isset($_FILES['photo_recto']) ||
//             $_FILES['photo_recto']['error'] !== UPLOAD_ERR_OK ||
//             !in_array(mime_content_type($_FILES['photo_recto']['tmp_name']), ['image/jpeg', 'image/png'])
//         ) {
//             $errors['photo_recto'] = ErrorsEnumFr::format(ErrorsEnumFr::PHOTO_REQUIS, ['type' => 'recto']);
//         }

//         // Vérification photo verso
//         if (
//             !isset($_FILES['photo_verso']) ||
//             $_FILES['photo_verso']['error'] !== UPLOAD_ERR_OK ||
//             !in_array(mime_content_type($_FILES['photo_verso']['tmp_name']), ['image/jpeg', 'image/png'])
//         ) {
//             $errors['photo_verso'] = ErrorsEnumFr::format(ErrorsEnumFr::PHOTO_REQUIS, ['type' => 'verso']);
//         }

//         return $errors;
//     }
// }