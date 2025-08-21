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
$devis_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];
$success = false;

try {
    $stmt = $pdo->prepare("SELECT id FROM devis WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $devis_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        header('Location: devis.php');
        exit();
    }
} catch (PDOException $e) {
    $errors['general'] = __("devis_edit.error_access") . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("SELECT id, nom, ice FROM clients WHERE user_id = :user_id ORDER BY nom ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['general'] = __("devis_edit.error_clients") . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("SELECT id, nom, prix FROM produits WHERE user_id = :user_id ORDER BY nom ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['general'] = __("devis_edit.error_products") . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM devis WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $devis_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $devis_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$devis_data) {
        throw new Exception(__("devis_edit.not_found"));
    }
    
    $stmt = $pdo->prepare("SELECT * FROM devis_lignes WHERE devis_id = :devis_id");
    $stmt->bindParam(':devis_id', $devis_id, PDO::PARAM_INT);
    $stmt->execute();
    $devis_data['lignes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $errors['general'] = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_data = [
        'client_id' => (int)$_POST['client_id'],
        'date_creation' => $_POST['date_creation'],
        'date_validite' => $_POST['date_validite'],
        'statut' => $_POST['statut'],
        'taux_tva' => (float)$_POST['taux_tva'],
        'lignes' => []
    ];

    if ($update_data['client_id'] <= 0) {
        $errors['client_id'] = __("devis_edit.select_client");
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
        $errors['lignes'] = __("devis_edit.add_line");
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("
                UPDATE devis SET
                    client_id = :client_id,
                    date_creation = :date_creation,
                    date_validite = :date_validite,
                    statut = :statut,
                    taux_tva = :taux_tva
                WHERE id = :id AND user_id = :user_id
            ");
            
            $params = [
                ':client_id' => $update_data['client_id'],
                ':date_creation' => $update_data['date_creation'],
                ':date_validite' => $update_data['date_validite'],
                ':statut' => $update_data['statut'],
                ':taux_tva' => $update_data['taux_tva'],
                ':id' => $devis_id,
                ':user_id' => $user_id
            ];
            
            $stmt->execute($params);
            
            $montant_ht = 0;
            
            $stmt = $pdo->prepare("DELETE FROM devis_lignes WHERE devis_id = :devis_id");
            $stmt->execute([':devis_id' => $devis_id]);
            
            foreach ($update_data['lignes'] as $ligne) {
                $stmt = $pdo->prepare("
                    INSERT INTO devis_lignes (
                        devis_id, produit_id, description, quantite, prix_unitaire
                    ) VALUES (
                        :devis_id, :produit_id, :description, :quantite, :prix_unitaire
                    )
                ");
                
                $ligne_params = [
                    ':devis_id' => $devis_id,
                    ':produit_id' => $ligne['produit_id'],
                    ':description' => $ligne['description'],
                    ':quantite' => $ligne['quantite'],
                    ':prix_unitaire' => $ligne['prix_unitaire']
                ];
                
                $stmt->execute($ligne_params);
                
                $montant_ht += $ligne['quantite'] * $ligne['prix_unitaire'];
            }
            
            $stmt = $pdo->prepare("UPDATE devis SET montant_ht = :montant_ht WHERE id = :id");
            $stmt->execute([':montant_ht' => $montant_ht, ':id' => $devis_id]);
            
            $pdo->commit();
            $success = true;
            
            header("Location: devis_view.php?id=$devis_id");
            exit();
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors['general'] = __("devis_edit.update_error") . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('devis_edit.title') ?> - efacture-maroc.com</title>
    
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .form-input:focus, .form-select:focus {
            border-color: #006233;
            box-shadow: 0 0 0 3px rgba(0, 98, 51, 0.1);
        }
        
        .devis-line:hover {
            background-color: #f8fafc;
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
        .status-accepte { background-color: #f6ffed; color: #52c41a; }
        .status-refuse { background-color: #fff2f0; color: #ff4d4f; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-file-signature text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('devis_edit.edit_quote', ['id' => str_pad($devis_id, 5, '0', STR_PAD_LEFT)]) ?></h1>
            </div>
            
            <?php if (isset($errors['general'])): ?>
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
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200"><?= __('devis_edit.general_info') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('devis_edit.client') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="client_id" name="client_id" required
                                    class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value=""><?= __('devis_edit.select_client') ?></option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>" 
                                        <?= ($devis_data['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($client['nom']) ?> (ICE: <?= $client['ice'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['client_id'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['client_id'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="date_creation" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('devis_edit.creation_date') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_creation" name="date_creation" 
                                   value="<?= $devis_data['date_creation'] ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="date_validite" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('devis_edit.validity_date') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_validite" name="date_validite" 
                                   value="<?= $devis_data['date_validite'] ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('devis_edit.status') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="statut" name="statut" required
                                    class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="en-cours" <?= ($devis_data['statut'] == 'en-cours') ? 'selected' : '' ?>><?= __('devis_edit.status_in_progress') ?></option>
                                <option value="accepte" <?= ($devis_data['statut'] == 'accepte') ? 'selected' : '' ?>><?= __('devis_edit.status_accepted') ?></option>
                                <option value="refuse" <?= ($devis_data['statut'] == 'refuse') ? 'selected' : '' ?>><?= __('devis_edit.status_refused') ?></option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="taux_tva" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('devis_edit.vat_rate') ?> (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="taux_tva" name="taux_tva" min="0" max="100" step="0.1" 
                                   value="<?= $devis_data['taux_tva'] ?>" required
                                   class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                    </div>
                </div>
                
                <div class="mb-10">
                    <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800"><?= __('devis_edit.quote_lines') ?></h2>
                        <?php if (isset($errors['lignes'])): ?>
                            <span class="text-sm text-red-600"><?= $errors['lignes'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="devis-lines" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('devis_edit.product') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('devis_edit.description') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('devis_edit.quantity') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('devis_edit.unit_price') ?> (DH)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('devis_edit.total') ?> (DH)</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($devis_data['lignes'] as $index => $ligne): ?>
                                    <tr class="devis-line">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="lignes[<?= $index ?>][produit_id]" 
                                                    class="product-select form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-primary focus:border-primary">
                                                <option value=""><?= __('devis_edit.select_product') ?></option>
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
                                                   placeholder="<?= __('devis_edit.description_placeholder') ?>"
                                                   class="line-desc form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" name="lignes[<?= $index ?>][quantite]" 
                                                   min="1" step="0.01" value="<?= $ligne['quantite'] ?>"
                                                   class="line-qty form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" name="lignes[<?= $index ?>][prix_unitaire]" 
                                                   min="0" step="0.01" value="<?= $ligne['prix_unitaire'] ?>"
                                                   class="line-price form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
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
                    
                    <div class="mt-4">
                        <button type="button" id="add-line" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <i class="fas fa-plus mr-2"></i> <?= __('devis_edit.add_line_button') ?>
                        </button>
                    </div>
                </div>
                
                <div class="mb-10">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="total-item flex justify-between items-center p-3">
                                <span class="text-gray-700"><?= __('devis_edit.total_ht') ?>:</span>
                                <span id="montant_ht" class="text-lg font-medium text-gray-900"><?= number_format($devis_data['montant_ht'], 2, ',', ' ') ?> DH</span>
                            </div>
                            <div class="total-item flex justify-between items-center p-3">
                                <span class="text-gray-700"><?= __('devis_edit.vat_amount', ['rate' => $devis_data['taux_tva']]) ?>:</span>
                                <span id="montant_tva" class="text-lg font-medium text-gray-900"><?= number_format($devis_data['montant_ht'] * ($devis_data['taux_tva'] / 100), 2, ',', ' ') ?> DH</span>
                            </div>
                            <div class="total-item flex justify-between items-center p-3 bg-gray-100 rounded-md">
                                <span class="text-lg font-bold text-gray-900"><?= __('devis_edit.total_ttc') ?>:</span>
                                <span id="montant_ttc" class="text-xl font-bold text-primary"><?= number_format($devis_data['montant_ht'] * (1 + $devis_data['taux_tva'] / 100), 2, ',', ' ') ?> DH</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="devis.php?id=<?= $devis_id ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fas fa-times mr-2"></i> <?= __('devis_edit.cancel_button') ?>
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary animate-pulse">
                        <i class="fas fa-save mr-2"></i> <?= __('devis_edit.update_button') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        $(document).ready(function() {
            $('#add-line').click(function() {
                const newLine = `
                <tr class="devis-line">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select name="lignes[][produit_id]" 
                                class="product-select form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-primary focus:border-primary">
                            <option value=""><?= __('devis_edit.select_product') ?></option>
                            <?php foreach ($produits as $produit): ?>
                                <option value="<?= $produit['id'] ?>" data-prix="<?= $produit['prix'] ?>">
                                    <?= htmlspecialchars($produit['nom']) ?> (<?= number_format($produit['prix'], 2, ',', ' ') ?> DH)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="text" name="lignes[][description]" placeholder="<?= __('devis_edit.description_placeholder') ?>"
                               class="line-desc form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="lignes[][quantite]" min="1" step="0.01" value="1"
                               class="line-qty form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="number" name="lignes[][prix_unitaire]" min="0" step="0.01" value="0"
                               class="line-price form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 line-total">
                        0.00 DH
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" class="btn-remove-line text-red-600 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                `;
                $('#devis-lines tbody').append(newLine);
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
                
                $('.devis-line').each(function() {
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
            
            const submitBtn = $('button[type="submit"]');
            if (submitBtn) {
                submitBtn.hover(
                    function() { $(this).addClass('animate-pulse'); },
                    function() { $(this).removeClass('animate-pulse'); }
                );
            }
        });
    </script>
</body>
</html>