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

try {
    $stmt = $pdo->prepare("
        SELECT f.id, f.date_facture, c.nom AS client_nom, 
               (f.montant_ht * (1 + f.taux_tva/100)) AS montant_total
        FROM factures f
        JOIN clients c ON f.client_id = c.id
        WHERE f.user_id = :user_id 
          AND f.statut != 'payee'
        ORDER BY f.date_facture DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['general'] = __("paiement_create.error_loading") . $e->getMessage();
}

$paiement_data = [
    'facture_id' => '',
    'montant' => '',
    'mode_paiement' => 'virement',
    'date_paiement' => date('Y-m-d'),
    'reference' => '',
    'notes' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paiement_data = [
        'facture_id' => (int)$_POST['facture_id'],
        'montant' => (float)$_POST['montant'],
        'mode_paiement' => $_POST['mode_paiement'],
        'date_paiement' => $_POST['date_paiement'],
        'reference' => trim($_POST['reference']),
        'notes' => trim($_POST['notes'])
    ];

    if ($paiement_data['facture_id'] <= 0) {
        $errors['facture_id'] = __("paiement_create.select_invoice");
    }
    
    if ($paiement_data['montant'] <= 0) {
        $errors['montant'] = __("paiement_create.amount_error");
    }
    
    if (empty($paiement_data['date_paiement'])) {
        $errors['date_paiement'] = __("paiement_create.date_required");
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("
                INSERT INTO paiements (
                    user_id, facture_id, montant, mode_paiement, date_paiement, reference, notes
                ) VALUES (
                    :user_id, :facture_id, :montant, :mode_paiement, :date_paiement, :reference, :notes
                )
            ");
            
            $params = [
                ':user_id' => $user_id,
                ':facture_id' => $paiement_data['facture_id'],
                ':montant' => $paiement_data['montant'],
                ':mode_paiement' => $paiement_data['mode_paiement'],
                ':date_paiement' => $paiement_data['date_paiement'],
                ':reference' => $paiement_data['reference'],
                ':notes' => $paiement_data['notes']
            ];
            
            if ($stmt->execute($params)) {
                $paiement_id = $pdo->lastInsertId();
                
                $stmt_facture = $pdo->prepare("
                    SELECT montant_ht, taux_tva 
                    FROM factures 
                    WHERE id = :facture_id
                ");
                $stmt_facture->bindParam(':facture_id', $paiement_data['facture_id'], PDO::PARAM_INT);
                $stmt_facture->execute();
                $facture = $stmt_facture->fetch(PDO::FETCH_ASSOC);
                
                $montant_total = $facture['montant_ht'] * (1 + $facture['taux_tva']/100);
                
                if ($paiement_data['montant'] >= $montant_total) {
                    $stmt_update = $pdo->prepare("
                        UPDATE factures 
                        SET statut = 'payee' 
                        WHERE id = :facture_id
                    ");
                    $stmt_update->bindParam(':facture_id', $paiement_data['facture_id'], PDO::PARAM_INT);
                    $stmt_update->execute();
                }
                
                $pdo->commit();
                $success = true;
                
                header("Location: paiement_view.php?id=$paiement_id");
                exit();
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors['general'] = __("paiement_create.save_error") . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('paiement_create.title') ?> - efacture-maroc.com</title>
    
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
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-money-bill-wave text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('paiement_create.title') ?></h1>
            </div>
            
            <?php if (!empty($errors['general'])): ?>
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
            
            <form method="post" class="bg-white shadow-sm rounded-lg overflow-hidden p-6">
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2"><?= __('paiement_create.general_info') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label for="facture_id" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('paiement_create.invoice') ?> <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="facture_id" 
                                name="facture_id" 
                                required
                                class="form-input block w-full rounded-md border-gray-300 py-3 pl-3 pr-10 focus:outline-none sm:text-sm"
                            >
                                <option value=""><?= __('paiement_create.select_invoice') ?></option>
                                <?php foreach ($factures as $facture): ?>
                                    <option 
                                        value="<?= $facture['id'] ?>" 
                                        <?= ($paiement_data['facture_id'] == $facture['id']) ? 'selected' : '' ?>
                                        data-montant="<?= $facture['montant_total'] ?>"
                                        class="py-2"
                                    >
                                        FAC-<?= str_pad($facture['id'], 5, '0', STR_PAD_LEFT) ?> - 
                                        <?= date('d/m/Y', strtotime($facture['date_facture'])) ?> - 
                                        <?= htmlspecialchars($facture['client_nom']) ?> - 
                                        <?= number_format($facture['montant_total'], 2, ',', ' ') ?> DH
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['facture_id'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['facture_id'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('paiement_create.amount') ?> (DH) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">DH</span>
                                </div>
                                <input
                                    type="number"
                                    id="montant"
                                    name="montant"
                                    step="0.01"
                                    min="0.01"
                                    value="<?= $paiement_data['montant'] ?>"
                                    required
                                    class="form-input block w-full pl-12 pr-12 py-3 rounded-md border-gray-300 focus:outline-none sm:text-sm"
                                    placeholder="0.00"
                                >
                            </div>
                            <?php if (isset($errors['montant'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['montant'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="mode_paiement" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('paiement_create.payment_method') ?> <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="mode_paiement"
                                name="mode_paiement"
                                required
                                class="form-input block w-full rounded-md border-gray-300 py-3 pl-3 pr-10 focus:outline-none sm:text-sm"
                            >
                                <option value="virement" <?= ($paiement_data['mode_paiement'] == 'virement') ? 'selected' : '' ?>><?= __('paiement_create.methods.bank_transfer') ?></option>
                                <option value="cheque" <?= ($paiement_data['mode_paiement'] == 'cheque') ? 'selected' : '' ?>><?= __('paiement_create.methods.check') ?></option>
                                <option value="especes" <?= ($paiement_data['mode_paiement'] == 'especes') ? 'selected' : '' ?>><?= __('paiement_create.methods.cash') ?></option>
                                <option value="carte" <?= ($paiement_data['mode_paiement'] == 'carte') ? 'selected' : '' ?>><?= __('paiement_create.methods.card') ?></option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_paiement" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('paiement_create.payment_date') ?> <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="date_paiement"
                                name="date_paiement"
                                value="<?= $paiement_data['date_paiement'] ?>"
                                required
                                class="form-input block w-full rounded-md border-gray-300 py-3 px-3 focus:outline-none sm:text-sm"
                            >
                            <?php if (isset($errors['date_paiement'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['date_paiement'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('paiement_create.reference') ?>
                            </label>
                            <input
                                type="text"
                                id="reference"
                                name="reference"
                                value="<?= htmlspecialchars($paiement_data['reference']) ?>"
                                class="form-input block w-full rounded-md border-gray-300 py-3 px-3 focus:outline-none sm:text-sm"
                                placeholder="<?= __('paiement_create.reference_placeholder') ?>"
                            >
                        </div>
                        
                        <div class="col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('paiement_create.notes') ?>
                            </label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                class="form-input block w-full rounded-md border-gray-300 py-3 px-3 focus:outline-none sm:text-sm"
                            ><?= htmlspecialchars($paiement_data['notes']) ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="paiements.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('paiement_create.cancel') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition animate-pulse">
                        <i class="fas fa-save mr-2"></i> <?= __('paiement_create.save') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        document.getElementById('facture_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.montant) {
                document.getElementById('montant').value = selectedOption.dataset.montant;
            }
        });
        
        document.getElementById('montant').addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
        
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.addEventListener('mouseenter', () => {
                submitBtn.classList.add('animate-pulse');
            });
            
            submitBtn.addEventListener('mouseleave', () => {
                submitBtn.classList.remove('animate-pulse');
            });
        }
    </script>
</body>
</html>