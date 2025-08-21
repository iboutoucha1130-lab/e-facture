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
$prospect_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];
$success = false;

try {
    $stmt = $pdo->prepare("SELECT * FROM prospects WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $prospect_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $prospect_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$prospect_data) {
        header('Location: prospects.php');
        exit();
    }
} catch (PDOException $e) {
    $errors['general'] = __("prospects_edit.db_error") . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_data = [
        'nom' => trim($_POST['nom']),
        'entreprise' => trim($_POST['entreprise']),
        'telephone' => trim($_POST['telephone']),
        'email' => trim($_POST['email']),
        'source' => trim($_POST['source']),
        'statut' => trim($_POST['statut'])
    ];

    if (empty($update_data['nom'])) {
        $errors['nom'] = __("prospects_edit.errors.name_required");
    }
    
    if (empty($update_data['entreprise'])) {
        $errors['entreprise'] = __("prospects_edit.errors.company_required");
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE prospects SET
                    nom = :nom,
                    entreprise = :entreprise,
                    telephone = :telephone,
                    email = :email,
                    source = :source,
                    statut = :statut
                WHERE id = :id AND user_id = :user_id
            ");
            
            $params = [
                ':nom' => $update_data['nom'],
                ':entreprise' => $update_data['entreprise'],
                ':telephone' => $update_data['telephone'],
                ':email' => $update_data['email'],
                ':source' => $update_data['source'],
                ':statut' => $update_data['statut'],
                ':id' => $prospect_id,
                ':user_id' => $user_id
            ];
            
            if ($stmt->execute($params)) {
                $success = true;
                header("Location: prospects.php?success=" . urlencode(__("prospects_edit.success")));
                exit();
            }
        } catch (PDOException $e) {
            $errors['general'] = __("prospects_edit.update_error") . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('prospects_edit.title') ?> - efacture-maroc.com</title>
    
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
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shadcn/ui/dist/shadcn-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .form-input:focus {
            border-color: #006233;
            box-shadow: 0 0 0 3px rgba(0, 98, 51, 0.1);
        }
        .status-nouveau { background-color: #f0f0f0; color: #666; }
        .status-contacte { background-color: #e6f7ff; color: #1890ff; }
        .status-suivi { background-color: #fff7e6; color: #fa8c16; }
        .status-converti { background-color: #f6ffed; color: #52c41a; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-handshake text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('prospects_edit.title') ?></h1>
            </div>
            
            <?php if (!empty($errors['general'])): ?>
                <div class="mb-6 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
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
            
            <form method="post" class="bg-white shadow-sm rounded-lg overflow-hidden p-6">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><?= __('prospects_edit.info_title') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_edit.name_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" required
                                   value="<?= htmlspecialchars($prospect_data['nom']) ?>" 
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   placeholder="<?= __('prospects_edit.name_placeholder') ?>">
                            <?php if (isset($errors['nom'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['nom'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="entreprise" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_edit.company_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="entreprise" name="entreprise" required
                                   value="<?= htmlspecialchars($prospect_data['entreprise']) ?>" 
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   placeholder="<?= __('prospects_edit.company_placeholder') ?>">
                            <?php if (isset($errors['entreprise'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['entreprise'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_edit.phone_label') ?>
                            </label>
                            <input type="text" id="telephone" name="telephone"
                                   value="<?= htmlspecialchars($prospect_data['telephone']) ?>" 
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   placeholder="<?= __('prospects_edit.phone_placeholder') ?>">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_edit.email_label') ?>
                            </label>
                            <input type="email" id="email" name="email"
                                   value="<?= htmlspecialchars($prospect_data['email']) ?>" 
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   placeholder="<?= __('prospects_edit.email_placeholder') ?>">
                        </div>
                        
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_edit.source_label') ?>
                            </label>
                            <input type="text" id="source" name="source"
                                   value="<?= htmlspecialchars($prospect_data['source']) ?>" 
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   placeholder="<?= __('prospects_edit.source_placeholder') ?>">
                        </div>
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_edit.status_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="statut" name="statut" required
                                class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value="nouveau" <?= ($prospect_data['statut'] == 'nouveau') ? 'selected' : '' ?>><?= __('prospects_edit.status_new') ?></option>
                                <option value="contacte" <?= ($prospect_data['statut'] == 'contacte') ? 'selected' : '' ?>><?= __('prospects_edit.status_contacted') ?></option>
                                <option value="suivi" <?= ($prospect_data['statut'] == 'suivi') ? 'selected' : '' ?>><?= __('prospects_edit.status_followup') ?></option>
                                <option value="converti" <?= ($prospect_data['statut'] == 'converti') ? 'selected' : '' ?>><?= __('prospects_edit.status_converted') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="prospects.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('prospects_edit.cancel_button') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-save mr-2"></i> <?= __('prospects_edit.save_button') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>