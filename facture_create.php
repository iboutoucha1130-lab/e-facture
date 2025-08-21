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
$errors = [];
$success = false;

try {
    $stmt = $pdo->prepare("SELECT id, nom, ice FROM clients WHERE user_id = :user_id ORDER BY nom ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['general'] = __('facture_create.error_loading_clients') . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("SELECT id, nom, prix FROM produits WHERE user_id = :user_id ORDER BY nom ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['general'] = __('facture_create.error_loading_products') . $e->getMessage();
}

$facture_data = [
    'client_id' => '',
    'date_facture' => date('Y-m-d'),
    'date_echeance' => date('Y-m-d', strtotime('+30 days')),
    'statut' => 'brouillon',
    'taux_tva' => 20,
    'lignes' => [
        ['produit_id' => '', 'quantite' => 1, 'prix_unitaire' => 0, 'description' => '']
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facture_data = [
        'client_id' => (int)$_POST['client_id'],
        'date_facture' => $_POST['date_facture'],
        'date_echeance' => $_POST['date_echeance'],
        'statut' => $_POST['statut'],
        'taux_tva' => (float)$_POST['taux_tva'],
        'lignes' => []
    ];

    if ($facture_data['client_id'] <= 0) {
        $errors['client_id'] = __('facture_create.select_client');
    }
    
    if (empty($facture_data['date_facture'])) {
        $errors['date_facture'] = __('facture_create.invoice_date_required');
    }
    
    if (empty($facture_data['date_echeance'])) {
        $errors['date_echeance'] = __('facture_create.due_date_required');
    }
    
    if (isset($_POST['lignes'])) {
        foreach ($_POST['lignes'] as $ligne) {
            if (!empty($ligne['description']) || !empty($ligne['produit_id'])) {
                $facture_data['lignes'][] = [
                    'produit_id' => (int)$ligne['produit_id'],
                    'quantite' => (float)$ligne['quantite'],
                    'prix_unitaire' => (float)$ligne['prix_unitaire'],
                    'description' => trim($ligne['description'])
                ];
            }
        }
    }
    
    if (count($facture_data['lignes']) === 0) {
        $errors['lignes'] = __('facture_create.add_at_least_one_line');
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("
                INSERT INTO factures (
                    user_id, client_id, date_facture, date_echeance, statut, taux_tva, montant_ht
                ) VALUES (
                    :user_id, :client_id, :date_facture, :date_echeance, :statut, :taux_tva, 0
                )
            ");
            
            $params = [
                ':user_id' => $user_id,
                ':client_id' => $facture_data['client_id'],
                ':date_facture' => $facture_data['date_facture'],
                ':date_echeance' => $facture_data['date_echeance'],
                ':statut' => $facture_data['statut'],
                ':taux_tva' => $facture_data['taux_tva']
            ];
            
            $stmt->execute($params);
            $facture_id = $pdo->lastInsertId();
            
            $montant_ht = 0;
            
            foreach ($facture_data['lignes'] as $ligne) {
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
            $errors['general'] = __('facture_create.invoice_creation_error') . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('facture_create.title') ?> - efacture-maroc.com</title>
    
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
        
        .status-brouillon { background-color: #f0f0f0; color: #666; }
        .status-envoyee { background-color: #e6f7ff; color: #1890ff; }
        .status-payee { background-color: #f6ffed; color: #52c41a; }
        .status-impayee { background-color: #fff2f0; color: #ff4d4f; }
        
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
    <?php include 'includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-file-invoice text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('facture_create.title') ?></h1>
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
                    <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200"><?= __('facture_create.general_info') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('facture_create.client') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="client_id" name="client_id" required
                                class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value=""><?= __('facture_create.select_client') ?></option>
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
                                <?= __('facture_create.invoice_date') ?> <span class="text-red-500">*</span>
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
                                <?= __('facture_create.due_date') ?> <span class="text-red-500">*</span>
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
                                <?= __('facture_create.status') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="statut" name="statut" required
                                class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value="brouillon" <?= ($facture_data['statut'] == 'brouillon') ? 'selected' : '' ?>><?= __('facture_create.status_draft') ?></option>
                                <option value="envoyee" <?= ($facture_data['statut'] == 'envoyee') ? 'selected' : '' ?>><?= __('facture_create.status_sent') ?></option>
                                <option value="payee" <?= ($facture_data['statut'] == 'payee') ? 'selected' : '' ?>><?= __('facture_create.status_paid') ?></option>
                                <option value="impayee" <?= ($facture_data['statut'] == 'impayee') ? 'selected' : '' ?>><?= __('facture_create.status_unpaid') ?></option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="taux_tva" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('facture_create.vat_rate') ?> (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="taux_tva" name="taux_tva" min="0" max="100" step="0.1" 
                                   value="<?= $facture_data['taux_tva'] ?>" required
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                        </div>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200"><?= __('facture_create.billing_lines') ?></h2>
                    
                    <?php if (isset($errors['lignes'])): ?>
                        <div class="mb-4 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800"><?= $errors['lignes'] ?></h3>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="overflow-x-auto">
                        <table id="facture-lines" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_create.product') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_create.description') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_create.quantity') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_create.unit_price') ?> (DH)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('facture_create.total') ?> (DH)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($facture_data['lignes'] as $index => $ligne): ?>
                                    <tr class="facture-line hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="lignes[<?= $index ?>][produit_id]" 
                                                class="product-select form-select block w-full rounded-md border-gray-300 py-1 pl-2 pr-8 focus:outline-none sm:text-sm">
                                                <option value=""><?= __('facture_create.select_product') ?></option>
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
                                                   placeholder="<?= __('facture_create.description_placeholder') ?>" 
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
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" id="add-line" class="mt-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fas fa-plus mr-2"></i> <?= __('facture_create.add_line') ?>
                    </button>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="total-item p-3">
                            <div class="text-sm text-gray-500"><?= __('facture_create.total_ht') ?>:</div>
                            <div class="text-lg font-semibold text-gray-900" id="montant_ht">0.00 DH</div>
                        </div>
                        <div class="total-item p-3">
                            <div class="text-sm text-gray-500"><?= __('facture_create.vat') ?> (<?= $facture_data['taux_tva'] ?>%):</div>
                            <div class="text-lg font-semibold text-gray-900" id="montant_tva">0.00 DH</div>
                        </div>
                        <div class="total-item p-3 bg-primary bg-opacity-10 rounded-lg">
                            <div class="text-sm text-gray-900 font-medium"><?= __('facture_create.total_ttc') ?>:</div>
                            <div class="text-lg font-bold text-primary" id="montant_ttc">0.00 DH</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="factures.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('facture_create.cancel') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition animate-pulse">
                        <i class="fas fa-save mr-2"></i> <?= __('facture_create.save_invoice') ?>
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
                        <select name="lignes[][produit_id]" class="product-select form-select block w-full rounded-md border-gray-300 py-1 pl-2 pr-8 focus:outline-none sm:text-sm">
                            <option value=""><?= __('facture_create.select_product') ?></option>
                            <?php foreach ($produits as $produit): ?>
                                <option value="<?= $produit['id'] ?>" data-prix="<?= $produit['prix'] ?>">
                                    <?= htmlspecialchars($produit['nom']) ?> (<?= number_format($produit['prix'], 2, ',', ' ') ?> DH)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="text" name="lignes[][description]" placeholder="<?= __('facture_create.description_placeholder') ?>" class="line-desc form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="lignes[][quantite]" min="1" step="0.01" value="1" class="line-qty form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="lignes[][prix_unitaire]" min="0" step="0.01" value="0" class="line-price form-input block w-full rounded-md border-gray-300 py-1 pl-2 pr-3 focus:outline-none sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 line-total">0.00 DH</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" class="btn-remove-line text-red-600 hover:text-red-900">
                            <i class="fas fa-times"></i>
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
                line.find('.line-total').text(total.toFixed(2) + ' DH');
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
                
                $('#montant_ht').text(totalHT.toFixed(2) + ' DH');
                $('#montant_tva').text(montantTVA.toFixed(2) + ' DH');
                $('#montant_ttc').text(totalTTC.toFixed(2) + ' DH');
            }

            attachLineEvents();
            $('#taux_tva').on('input', calculateTotals);
            calculateTotals();
        });
    </script>
</body>
</html>