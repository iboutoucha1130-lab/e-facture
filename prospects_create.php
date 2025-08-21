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
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $entreprise = trim($_POST['entreprise']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $source = trim($_POST['source']);
    $statut = $_POST['statut'];

    if (empty($nom)) {
        $error = __('prospects_create.error_name_required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        $error = __('prospects_create.error_invalid_email');
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO prospects (user_id, nom, entreprise, telephone, email, source, statut)
                VALUES (:user_id, :nom, :entreprise, :telephone, :email, :source, :statut)
            ");
            
            $stmt->execute([
                ':user_id' => $user_id,
                ':nom' => $nom,
                ':entreprise' => $entreprise,
                ':telephone' => $telephone,
                ':email' => $email,
                ':source' => $source,
                ':statut' => $statut
            ]);
            
            $success = __('prospects_create.success_added');
            header("Location: prospects.php?success=" . urlencode($success));
            exit();
        } catch (PDOException $e) {
            $error = __('prospects_create.error_adding') . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('prospects_create.title') ?> - efacture-maroc.com</title>
    
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
        
        .animate-pulse {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
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
                <h1 class="text-2xl font-bold text-gray-900"><?= __('prospects_create.title') ?></h1>
            </div>
            
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800"><?= $error ?></h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="post" class="bg-white shadow-sm rounded-lg overflow-hidden p-6">
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200"><?= __('prospects_create.info_section') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_create.fullname_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" required
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>"
                                   placeholder="<?= __('prospects_create.fullname_placeholder') ?>">
                        </div>
                        
                        <div>
                            <label for="entreprise" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_create.company_label') ?>
                            </label>
                            <input type="text" id="entreprise" name="entreprise"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   value="<?= isset($_POST['entreprise']) ? htmlspecialchars($_POST['entreprise']) : '' ?>"
                                   placeholder="<?= __('prospects_create.company_placeholder') ?>">
                        </div>
                        
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_create.phone_label') ?>
                            </label>
                            <input type="tel" id="telephone" name="telephone"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   value="<?= isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '' ?>"
                                   placeholder="<?= __('prospects_create.phone_placeholder') ?>">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_create.email_label') ?>
                            </label>
                            <input type="email" id="email" name="email"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm"
                                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                                   placeholder="<?= __('prospects_create.email_placeholder') ?>">
                        </div>
                        
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_create.source_label') ?>
                            </label>
                            <select id="source" name="source"
                                class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value=""><?= __('prospects_create.source_default') ?></option>
                                <option value="site_web" <?= (isset($_POST['source']) && $_POST['source'] === 'site_web') ? 'selected' : '' ?>><?= __('prospects_create.source_website') ?></option>
                                <option value="reseaux_sociaux" <?= (isset($_POST['source']) && $_POST['source'] === 'reseaux_sociaux') ? 'selected' : '' ?>><?= __('prospects_create.source_social') ?></option>
                                <option value="recommandation" <?= (isset($_POST['source']) && $_POST['source'] === 'recommandation') ? 'selected' : '' ?>><?= __('prospects_create.source_recommendation') ?></option>
                                <option value="salon" <?= (isset($_POST['source']) && $_POST['source'] === 'salon') ? 'selected' : '' ?>><?= __('prospects_create.source_event') ?></option>
                                <option value="autre" <?= (isset($_POST['source']) && $_POST['source'] === 'autre') ? 'selected' : '' ?>><?= __('prospects_create.source_other') ?></option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('prospects_create.status_label') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="statut" name="statut" required
                                class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value="nouveau" <?= (isset($_POST['statut']) && $_POST['statut'] === 'nouveau') ? 'selected' : 'selected' ?>><?= __('prospects_create.status_new') ?></option>
                                <option value="contacte" <?= (isset($_POST['statut']) && $_POST['statut'] === 'contacte') ? 'selected' : '' ?>><?= __('prospects_create.status_contacted') ?></option>
                                <option value="suivi" <?= (isset($_POST['statut']) && $_POST['statut'] === 'suivi') ? 'selected' : '' ?>><?= __('prospects_create.status_followup') ?></option>
                                <option value="converti" <?= (isset($_POST['statut']) && $_POST['statut'] === 'converti') ? 'selected' : '' ?>><?= __('prospects_create.status_converted') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="prospects.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('prospects_create.cancel_button') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition animate-pulse">
                        <i class="fas fa-save mr-2"></i> <?= __('prospects_create.save_button') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>