<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$user_id = $_SESSION['user']['id'];
$errors = [];
$success = false;

$categories = [
    __('produit_create.categories.service'),
    __('produit_create.categories.software'),
    __('produit_create.categories.hardware'),
    __('produit_create.categories.consultation'),
    __('produit_create.categories.training'),
    __('produit_create.categories.maintenance'),
    __('produit_create.categories.other')
];

$produit_data = [
    'nom' => '',
    'description' => '',
    'prix' => '',
    'categorie' => '',
    'image_path' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit_data = [
        'nom' => trim($_POST['nom']),
        'description' => trim($_POST['description']),
        'prix' => trim($_POST['prix']),
        'categorie' => $_POST['categorie'],
        'image_path' => ''
    ];

    if (empty($produit_data['nom'])) {
        $errors['nom'] = __('produit_create.errors.name_required');
    }
    
    if (empty($produit_data['prix']) || !is_numeric($produit_data['prix']) || $produit_data['prix'] <= 0) {
        $errors['prix'] = __('produit_create.errors.price_invalid');
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $upload_dir = 'uploads/products/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = 'prod_' . uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                $produit_data['image_path'] = 'products/' . $file_name;
            } else {
                $errors['image'] = __('produit_create.errors.image_upload');
            }
        } else {
            $errors['image'] = __('produit_create.errors.image_type');
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO produits (
                    user_id, nom, description, prix, categorie, image_path
                ) VALUES (
                    :user_id, :nom, :description, :prix, :categorie, :image_path
                )
            ");
            
            $params = [
                ':user_id' => $user_id,
                ':nom' => $produit_data['nom'],
                ':description' => $produit_data['description'],
                ':prix' => $produit_data['prix'],
                ':categorie' => $produit_data['categorie'],
                ':image_path' => $produit_data['image_path']
            ];
            
            if ($stmt->execute($params)) {
                $success = true;
                $produit_data = array_fill_keys(array_keys($produit_data), '');
                $_POST = [];
            }
        } catch (PDOException $e) {
            $errors['general'] = __('produit_create.errors.db_error') . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('produit_create.title') ?> - efacture-maroc.com</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#006233',
                        secondary: '#C1272D',
                        accent: '#0a9c5e',
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .image-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 0.375rem;
        }
        .form-input {
            transition: border-color 0.2s ease-in-out;
        }
        .form-input:focus {
            border-color: #006233;
            box-shadow: 0 0 0 3px rgba(0, 98, 51, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900"><?= __('produit_create.heading') ?></h1>
                <a href="produits.php" class="text-primary hover:text-green-800 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> <?= __('produit_create.back_link') ?>
                </a>
            </div>
            
            <?php if ($success): ?>
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                <?= __('produit_create.success_message') ?>
                                <a href="produits.php" class="text-green-700 hover:text-green-600 underline"><?= __('produit_create.view_list') ?></a>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors['general'])): ?>
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800"><?= $errors['general'] ?></h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="bg-white shadow-sm rounded-lg overflow-hidden p-6">
                <form method="post" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('produit_create.form.name_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" 
                                   value="<?= htmlspecialchars($produit_data['nom']) ?>" 
                                   class="form-input w-full rounded-md border-gray-300 shadow-sm <?= isset($errors['nom']) ? 'border-red-300' : '' ?>">
                            <?php if (isset($errors['nom'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['nom'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('produit_create.form.price_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="prix" name="prix" step="0.01" min="0"
                                   value="<?= htmlspecialchars($produit_data['prix']) ?>" 
                                   class="form-input w-full rounded-md border-gray-300 shadow-sm <?= isset($errors['prix']) ? 'border-red-300' : '' ?>">
                            <?php if (isset($errors['prix'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['prix'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('produit_create.form.category_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="categorie" name="categorie" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""><?= __('produit_create.form.category_select') ?></option>
                                <?php foreach ($categories as $categorie): ?>
                                    <option value="<?= $categorie ?>" 
                                        <?= ($produit_data['categorie'] == $categorie) ? 'selected' : '' ?>>
                                        <?= $categorie ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('produit_create.form.image_label') ?>
                            </label>
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-green-800">
                            <?php if (isset($errors['image'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['image'] ?></p>
                            <?php endif; ?>
                            <p class="mt-1 text-xs text-gray-500"><?= __('produit_create.form.image_hint') ?></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('produit_create.form.description_label') ?>
                            </label>
                            <textarea id="description" name="description" rows="3"
                                      class="form-input w-full rounded-md border-gray-300 shadow-sm"><?= htmlspecialchars($produit_data['description']) ?></textarea>
                        </div>
                        
                        <?php if ($produit_data['image_path']): ?>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1"><?= __('produit_create.form.current_image') ?></label>
                                <img src="uploads/<?= $produit_data['image_path'] ?>" alt="<?= __('produit_create.form.image_alt') ?>" class="image-preview">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="produits.php" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <?= __('produit_create.cancel_button') ?>
                        </a>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <?= __('produit_create.save_button') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>