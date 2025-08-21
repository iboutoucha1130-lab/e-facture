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

if (!isset($_GET['id'])) {
    header('Location: paiements.php');
    exit();
}

$paiement_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("
        SELECT p.*, f.numero_facture, c.nom AS client_nom 
        FROM paiements p
        JOIN factures f ON p.facture_id = f.id
        JOIN clients c ON f.client_id = c.id
        WHERE p.id = :id AND p.user_id = :user_id
    ");
    $stmt->bindParam(':id', $paiement_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$paiement) {
        header('Location: paiements.php');
        exit();
    }
} catch (PDOException $e) {
    $error = __("paiement_edit.error_retrieving") . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $montant = (float)$_POST['montant'];
    $mode_paiement = trim($_POST['mode_paiement']);
    $reference = trim($_POST['reference']);
    $date_paiement = $_POST['date_paiement'];
    $facture_id = (int)$_POST['facture_id'];

    try {
        $stmt = $pdo->prepare("
            UPDATE paiements 
            SET montant = :montant, 
                mode_paiement = :mode_paiement, 
                reference = :reference, 
                date_paiement = :date_paiement, 
                facture_id = :facture_id
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->bindParam(':montant', $montant);
        $stmt->bindParam(':mode_paiement', $mode_paiement);
        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':date_paiement', $date_paiement);
        $stmt->bindParam(':facture_id', $facture_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $paiement_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $success = __("paiement_edit.update_success");
            header("Location: paiement_view.php?id=" . $paiement_id . "&success=" . urlencode($success));
            exit();
        }
    } catch (PDOException $e) {
        $error = __("paiement_edit.update_error") . $e->getMessage();
    }
}

try {
    $stmt = $pdo->prepare("
        SELECT f.id, f.date_facture, c.nom AS client_nom 
        FROM factures f
        JOIN clients c ON f.client_id = c.id
        WHERE f.user_id = :user_id
        ORDER BY f.date_facture DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = __("paiement_edit.error_factures") . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('paiement_edit.title') ?> - efacture-maroc.com</title>
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
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900"><?= __('paiement_edit.title') ?></h1>
                <a href="paiements.php" class="text-sm text-primary hover:text-green-800 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> <?= __('paiement_edit.back_link') ?>
                </a>
            </div>

            <?php if ($error): ?>
                <div class="rounded-md bg-red-50 p-4 mb-6">
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

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <form method="post" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="facture_id" class="block text-sm font-medium text-gray-700 mb-1"><?= __('paiement_edit.invoice_label') ?></label>
                            <select id="facture_id" name="facture_id" required class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <?php foreach ($factures as $facture): ?>
                                    <option value="<?= $facture['id'] ?>" <?= $facture['id'] == $paiement['facture_id'] ? 'selected' : '' ?>>
                                        FAC-<?= str_pad($facture['id'], 5, '0', STR_PAD_LEFT) ?> - <?= date('d/m/Y', strtotime($facture['date_facture'])) ?> - <?= htmlspecialchars($facture['client_nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1"><?= __('paiement_edit.amount_label') ?></label>
                            <input type="number" step="0.01" id="montant" name="montant" value="<?= htmlspecialchars($paiement['montant']) ?>" required class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>

                        <div>
                            <label for="date_paiement" class="block text-sm font-medium text-gray-700 mb-1"><?= __('paiement_edit.date_label') ?></label>
                            <input type="date" id="date_paiement" name="date_paiement" value="<?= htmlspecialchars($paiement['date_paiement']) ?>" required class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>

                        <div>
                            <label for="mode_paiement" class="block text-sm font-medium text-gray-700 mb-1"><?= __('paiement_edit.method_label') ?></label>
                            <select id="mode_paiement" name="mode_paiement" required class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="Espèces" <?= $paiement['mode_paiement'] == 'Espèces' ? 'selected' : '' ?>><?= __('paiement_edit.method_cash') ?></option>
                                <option value="Chèque" <?= $paiement['mode_paiement'] == 'Chèque' ? 'selected' : '' ?>><?= __('paiement_edit.method_check') ?></option>
                                <option value="Virement" <?= $paiement['mode_paiement'] == 'Virement' ? 'selected' : '' ?>><?= __('paiement_edit.method_transfer') ?></option>
                                <option value="Carte bancaire" <?= $paiement['mode_paiement'] == 'Carte bancaire' ? 'selected' : '' ?>><?= __('paiement_edit.method_card') ?></option>
                                <option value="Autre" <?= $paiement['mode_paiement'] == 'Autre' ? 'selected' : '' ?>><?= __('paiement_edit.method_other') ?></option>
                            </select>
                        </div>

                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700 mb-1"><?= __('paiement_edit.reference_label') ?></label>
                            <input type="text" id="reference" name="reference" value="<?= htmlspecialchars($paiement['reference']) ?>" class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                            <i class="fas fa-save mr-2"></i> <?= __('paiement_edit.save_button') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>