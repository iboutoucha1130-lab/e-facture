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

if ($devis_id <= 0) {
    $_SESSION['error'] = __("facturer.invalid_quote");
    header('Location: dashboard.php');
    exit();
}

try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("
        SELECT d.*, c.nom AS client_nom, c.ice, c.adresse, c.ville, c.code_postal 
        FROM devis d
        JOIN clients c ON d.client_id = c.id
        WHERE d.id = :devis_id AND d.user_id = :user_id
    ");
    $stmt->bindParam(':devis_id', $devis_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $devis = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$devis) {
        $_SESSION['error'] = __("facturer.quote_not_found");
        header('Location: dashboard.php');
        exit();
    }

    $date_facture = date('Y-m-d');
    $date_echeance = date('Y-m-d', strtotime('+30 days'));

    $stmt = $pdo->prepare("
        INSERT INTO factures (
            user_id, client_id, date_facture, date_echeance, statut, taux_tva, montant_ht
        ) VALUES (
            :user_id, :client_id, :date_facture, :date_echeance, 'impayee', :taux_tva, :montant_ht
        )
    ");
    $params = [
        ':user_id' => $user_id,
        ':client_id' => $devis['client_id'],
        ':date_facture' => $date_facture,
        ':date_echeance' => $date_echeance,
        ':taux_tva' => $devis['taux_tva'],
        ':montant_ht' => $devis['montant_ht']
    ];
    $stmt->execute($params);
    $facture_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("
        INSERT INTO facture_lignes (
            facture_id, produit_id, description, quantite, prix_unitaire
        )
        SELECT :facture_id, produit_id, description, quantite, prix_unitaire
        FROM devis_lignes
        WHERE devis_id = :devis_id
    ");
    $stmt->execute([
        ':facture_id' => $facture_id,
        ':devis_id' => $devis_id
    ]);

    $stmt = $pdo->prepare("DELETE FROM devis_lignes WHERE devis_id = :devis_id");
    $stmt->bindParam(':devis_id', $devis_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $stmt = $pdo->prepare("DELETE FROM devis WHERE id = :devis_id AND user_id = :user_id");
    $stmt->bindParam(':devis_id', $devis_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $pdo->commit();
    
    $_SESSION['success'] = __("facturer.conversion_success");
    header('Location: dashboard.php');
    exit();

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['error'] = __("facturer.conversion_error", ['error' => $e->getMessage()]);
    header('Location: dashboard.php');
    exit();
}