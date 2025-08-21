<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/config.php';
require_once 'includes/db_connection.php';
require_once 'includes/helpers.php';

$user_id = $_SESSION['user']['id'];
$facture_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];
$success = false;

try {
    $stmt = $pdo->prepare("SELECT id FROM factures WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $facture_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        header('Location: factures.php');
        exit();
    }
} catch (PDOException $e) {
    $errors['general'] = __('facture_edit.error_access') . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("SELECT id, nom, ice FROM clients WHERE user_id = :user_id ORDER BY nom ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['general'] = __('facture_edit.error_clients') . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("SELECT id, nom, prix FROM produits WHERE user_id = :user_id ORDER BY nom ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['general'] = __('facture_edit.error_products') . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("
        SELECT * FROM factures 
        WHERE id = :id AND user_id = :user_id
    ");
    $stmt->bindParam(':id', $facture_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $facture_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$facture_data) {
        throw new Exception(__('facture_edit.not_found'));
    }
    
    $stmt = $pdo->prepare("
        SELECT * FROM facture_lignes 
        WHERE facture_id = :facture_id
    ");
    $stmt->bindParam(':facture_id', $facture_id, PDO::PARAM_INT);
    $stmt->execute();
    $facture_data['lignes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $errors['general'] = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_data = [
        'client_id' => (int)$_POST['client_id'],
        'date_facture' => $_POST['date_facture'],
        'date_echeance' => $_POST['date_echeance'],
        'statut' => $_POST['statut'],
        'taux_tva' => (float)$_POST['taux_tva'],
        'lignes' => []
    ];

    if ($update_data['client_id'] <= 0) {
        $errors['client_id'] = __('facture_edit.select_client');
    }
    
    if (empty($update_data['date_facture'])) {
        $errors['date_facture'] = __('facture_edit.required_date');
    }
    
    if (empty($update_data['date_echeance'])) {
        $errors['date_echeance'] = __('facture_edit.required_due_date');
    }
    
    if (isset($_POST['lignes'])) {
        foreach ($_POST['lignes'] as $ligne) {
            if (!empty($ligne['description']) || !empty($ligne['produit_id'])) {
                $update_data['lignes'][] = [
                    'id' => isset($ligne['id']) ? (int)$ligne['id'] : 0,
                    'produit_id' => (int)$ligne['produit_id'],
                    'quantite' => (float)$ligne['quantite'],
                    'prix_unitaire' => (float)$ligne['prix_unitaire'],
                    'description' => trim($ligne['description'])
                ];
            }
        }
    }
    
    if (count($update_data['lignes']) === 0) {
        $errors['lignes'] = __('facture_edit.add_line');
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("
                UPDATE factures SET
                    client_id = :client_id,
                    date_facture = :date_facture,
                    date_echeance = :date_echeance,
                    statut = :statut,
                    taux_tva = :taux_tva
                WHERE id = :id AND user_id = :user_id
            ");
            
            $params = [
                ':client_id' => $update_data['client_id'],
                ':date_facture' => $update_data['date_facture'],
                ':date_echeance' => $update_data['date_echeance'],
                ':statut' => $update_data['statut'],
                ':taux_tva' => $update_data['taux_tva'],
                ':id' => $facture_id,
                ':user_id' => $user_id
            ];
            
            $stmt->execute($params);
            
            $montant_ht = 0;
            
            $stmt = $pdo->prepare("DELETE FROM facture_lignes WHERE facture_id = :facture_id");
            $stmt->execute([':facture_id' => $facture_id]);
            
            foreach ($update_data['lignes'] as $ligne) {
                $stmt = $pdo->prepare("
                    INSERT INTO facture_lignes (
                        facture_id, produit_id, description, quantite, prix_unitaire
                    ) VALUES (
                        :facture_id, :produit_id, :description, :quantite, :prix_unitaire
                    )
                ");
                
                $ligne_params = [
                    ':facture_id' => $facture_id,
                    ':produit_id' => $ligne['produit_id'],
                    ':description' => $ligne['description'],
                    ':quantite' => $ligne['quantite'],
                    ':prix_unitaire' => $ligne['prix_unitaire']
                ];
                
                $stmt->execute($ligne_params);
                
                $montant_ht += $ligne['quantite'] * $ligne['prix_unitaire'];
            }
            
            $stmt = $pdo->prepare("UPDATE factures SET montant_ht = :montant_ht WHERE id = :id");
            $stmt->execute([':montant_ht' => $montant_ht, ':id' => $facture_id]);
            
            $pdo->commit();
            $success = true;
            
            header("Location: facture_view.php?id=$facture_id");
            exit();
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors['general'] = __('facture_edit.update_error') . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('facture_edit.title', ['id' => str_pad($facture_id, 5, '0', STR_PAD_LEFT)]) ?> - efacture-maroc.com</title>
    
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
        .status-brouillon { background-color: #f0f0f0; color: #666; }
        .status-envoyee { background-color: #e6f7ff; color: #1890ff; }
        .status-payee { background-color: #f6ffed; color: #52c41a; }
        .status-impayee { background-color: #fff2f0; color: #ff4d4f; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include 'includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-file-invoice text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('facture_edit.edit_title', ['id' => str_pad($facture_id, 5, '0', STR_PAD_LEFT)]) ?></h1>
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
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><?= __('facture_edit.general_info') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('facture_edit.client') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="client_id" name="client_id" required
                                class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value=""><?= __('facture_edit.select_client') ?></option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>" 
                                        <?= ($facture_data['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($client['nom']) ?> (ICE: <?= $client['ice'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['client_id'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['client_id'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="date_facture" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('facture_edit.invoice_date') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_facture" name="date_facture" 
                                   value="<?= $facture_data['date_facture'] ?>" required
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['date_facture'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['date_facture'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="date_echeance" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('facture_edit.due_date') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_echeance" name="date_echeance" 
                                   value="<?= $facture_data['date_echeance'] ?>" required
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['date_echeance'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['date_echeance'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('facture_edit.status') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="statut" name="statut" required
                                class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value="brouillon" <?= ($facture_data['statut'] == 'brouillon') ? 'selected' : '' ?>><?= __('facture_edit.status_draft') ?></option>
                                <option value="envoyee" <?= ($facture_data['statut'] == 'envoyee') ? 'selected' : '' ?>><?= __('facture_edit.status_sent') ?></option>
                                <option value="payee" <?= ($facture_data['statut'] == 'payee') ? 'selected' : '' ?>><?= __('facture_edit.status_paid') ?></option>
                                <option value="impayee" <?= ($facture_data['statut'] == 'impayee') ? 'selected' : '' ?>><?= __('facture_edit.status_unpaid') ?></option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="taux_tva" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('facture_edit.vat_rate') ?> (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="taux_tva" name="taux_tva" min="0" max="100" step="0.1" 
                                   value="<?= $facture_data['taux_tva'] ?>" required
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                        </div>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800"><?= __('facture_edit.invoice_lines') ?></h2>
                        <button type="button" id="add-line" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none">
                            <i class="fas fa-plus mr-1"></i> <?= __('facture_edit.add_line_button') ?>
                        </button>
                    </div>
                    
                    <?php if (isset($errors['lignes'])): ?>
                        <div class="mb-4 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                            <p class="text-sm text-red-800"><?= $errors['lignes'] ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="overflow-x-auto">
                        <table id="facture-lines" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_edit.table.product') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_edit.table.description') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_edit.table.quantity') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_edit.table.unit_price') ?> (DH)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_edit.table.total') ?> (DH)</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($facture_data['lignes'] as $index => $ligne): ?>
                                    <tr class="facture-line hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="lignes[<?= $index ?>][produit_id]" class="product-select form-select block w-full rounded-md border-gray-300 py-1 pl-2 pr-7 focus:outline-none sm:text-sm">
                                                <option value=""><?= __('facture_edit.select_product') ?></option>
                                                <?php foreach ($produits as $produit): ?>
                                                    <option value="<?= $produit['id'] ?>" 
                                                        data-prix="<?= $produit['prix'] ?>"
                                                        <?= ($ligne['produit_id'] == $produit['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($produit['nom']) ?> (<?= number_format($produit['prix'], 2, ',', ' ') ?> DH)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="lignes[<?= $index ?>][description]" 
                                                   value="<?= htmlspecialchars($ligne['description']) ?>" 
                                                   placeholder="<?= __('facture_edit.description_placeholder') ?>" 
                                                   class="line-desc form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" name="lignes[<?= $index ?>][quantite]" 
                                                   min="1" step="0.01" value="<?= $ligne['quantite'] ?>" 
                                                   class="line-qty form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" name="lignes[<?= $index ?>][prix_unitaire]" 
                                                   min="0" step="0.01" value="<?= $ligne['prix_unitaire'] ?>" 
                                                   class="line-price form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 line-total">
                                            <?= number_format($ligne['quantite'] * $ligne['prix_unitaire'], 2, ',', ' ') ?> DH
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button" class="btn-remove-line text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="text-sm text-gray-500"><?= __('facture_edit.total_ht') ?>:</p>
                            <p class="text-lg font-bold text-gray-900" id="montant_ht"><?= number_format($facture_data['montant_ht'], 2, ',', ' ') ?> DH</p>
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p class="text-sm text-gray-500"><?= __('facture_edit.vat_amount', ['rate' => $facture_data['taux_tva']]) ?>:</p>
                            <p class="text-lg font-bold text-gray-900" id="montant_tva"><?= number_format($facture_data['montant_ht'] * ($facture_data['taux_tva'] / 100), 2, ',', ' ') ?> DH</p>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded shadow-sm border-l-4 border-primary">
                            <p class="text-sm text-gray-700"><?= __('facture_edit.total_ttc') ?>:</p>
                            <p class="text-lg font-bold text-primary" id="montant_ttc"><?= number_format($facture_data['montant_ht'] * (1 + $facture_data['taux_tva'] / 100), 2, ',', ' ') ?> DH</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="factures.php?id=<?= $facture_id ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('facture_edit.cancel_button') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition animate-pulse">
                        <i class="fas fa-save mr-2"></i> <?= __('facture_edit.update_button') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-line').click(function() {
                const newLine = `
                <tr class="facture-line hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select name="lignes[][produit_id]" class="product-select form-select block w-full rounded-md border-gray-300 py-1 pl-2 pr-7 focus:outline-none sm:text-sm">
                            <option value=""><?= __('facture_edit.select_product') ?></option>
                            <?php foreach ($produits as $produit): ?>
                                <option value="<?= $produit['id'] ?>" data-prix="<?= $produit['prix'] ?>">
                                    <?= htmlspecialchars($produit['nom']) ?> (<?= number_format($produit['prix'], 2, ',', ' ') ?> DH)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="text" name="lignes[][description]" placeholder="<?= __('facture_edit.description_placeholder') ?>" 
                               class="line-desc form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="lignes[][quantite]" min="1" step="0.01" value="1" 
                               class="line-qty form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="lignes[][prix_unitaire]" min="0" step="0.01" value="0" 
                               class="line-price form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 line-total">
                        0.00 DH
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" class="btn-remove-line text-red-600 hover:text-red-900">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                `;
                $('#facture-lines tbody').append(newLine);
                attachLineEvents();
            });

            function attachLineEvents() {
                $('.product-select').change(function() {
                    const prix = $(this).find('option:selected').data('prix');
                    if (prix) {
                        $(this).closest('tr').find('.line-price').val(prix);
                        calculateLineTotal($(this).closest('tr'));
                    }
                });

                $('.line-qty, .line-price').on('input', function() {
                    calculateLineTotal($(this).closest('tr'));
                });

                $('.btn-remove-line').click(function() {
                    $(this).closest('tr').remove();
                    calculateTotals();
                });
            }

            function calculateLineTotal(line) {
                const qty = parseFloat(line.find('.line-qty').val()) || 0;
                const price = parseFloat(line.find('.line-price').val()) || 0;
                const total = qty * price;
                line.find('.line-total').text(total.toFixed(2).replace('.', ',') + ' DH');
                calculateTotals();
            }

            function calculateTotals() {
                let totalHT = 0;
                
                $('.facture-line').each(function() {
                    const qty = parseFloat($(this).find('.line-qty').val()) || 0;
                    const price = parseFloat($(this).find('.line-price').val()) || 0;
                    totalHT += qty * price;
                });
                
                const tva = parseFloat($('#taux_tva').val()) || 0;
                const montantTVA = totalHT * (tva / 100);
                const totalTTC = totalHT + montantTVA;
                
                $('#montant_ht').text(totalHT.toFixed(2).replace('.', ',') + ' DH');
                $('#montant_tva').text(montantTVA.toFixed(2).replace('.', ',') + ' DH');
                $('#montant_ttc').text(totalTTC.toFixed(2).replace('.', ',') + ' DH');
            }

            attachLineEvents();
            $('#taux_tva').on('input', calculateTotals);
            calculateTotals();
        });
    </script>
</body>
</html>