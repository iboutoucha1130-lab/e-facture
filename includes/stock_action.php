<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connection.php';

$user_id = $_SESSION['user']['id'];

try {
    // Gestion des différentes actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'add':
                // Validation des données
                $required_fields = ['produit_id', 'quantite'];
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("Le champ '$field' est requis");
                    }
                }

                $produit_id = (int)$_POST['produit_id'];
                $quantite = (int)$_POST['quantite'];
                $seuil_alerte = isset($_POST['seuil_alerte']) ? (int)$_POST['seuil_alerte'] : 10;
                $emplacement = $_POST['emplacement'] ?? '';

                // Vérifier si l'article existe déjà dans le stock
                $stmt_check = $pdo->prepare("
                    SELECT id 
                    FROM stock 
                    WHERE user_id = ? AND produit_id = ?
                ");
                $stmt_check->execute([$user_id, $produit_id]);
                
                if ($stmt_check->rowCount() > 0) {
                    // Mise à jour de la quantité existante
                    $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
                    $stock_id = $row['id'];
                    
                    $stmt_update = $pdo->prepare("
                        UPDATE stock 
                        SET quantite = quantite + ?, 
                            seuil_alerte = ?,
                            emplacement = ?
                        WHERE id = ? AND user_id = ?
                    ");
                    $stmt_update->execute([
                        $quantite, 
                        $seuil_alerte, 
                        $emplacement,
                        $stock_id, 
                        $user_id
                    ]);
                    
                    $_SESSION['message'] = "Stock mis à jour avec succès";
                } else {
                    // Ajout d'un nouvel article
                    $stmt_insert = $pdo->prepare("
                        INSERT INTO stock 
                        (user_id, produit_id, quantite, seuil_alerte, emplacement) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt_insert->execute([
                        $user_id, 
                        $produit_id, 
                        $quantite, 
                        $seuil_alerte, 
                        $emplacement
                    ]);
                    
                    $_SESSION['message'] = "Article ajouté au stock avec succès";
                }
                break;

            case 'update':
                // Validation des données
                if (empty($_POST['id'])) {
                    throw new Exception("ID manquant pour la mise à jour");
                }
                
                $id = (int)$_POST['id'];
                $quantite = (int)$_POST['quantite'];
                $seuil_alerte = (int)$_POST['seuil_alerte'];
                $emplacement = $_POST['emplacement'] ?? '';

                // Mise à jour du stock
                $stmt = $pdo->prepare("
                    UPDATE stock 
                    SET quantite = ?, 
                        seuil_alerte = ?, 
                        emplacement = ? 
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([
                    $quantite, 
                    $seuil_alerte, 
                    $emplacement,
                    $id, 
                    $user_id
                ]);
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception("Aucune modification effectuée ou élément non trouvé");
                }
                
                $_SESSION['message'] = "Stock mis à jour avec succès";
                break;
                
            default:
                throw new Exception("Action non reconnue");
        }
        
        // Redirection après succès
        header('Location: ../stock.php');
        exit();
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        // Gestion de la suppression
        if ($_GET['action'] === 'delete' && !empty($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            $stmt = $pdo->prepare("
                DELETE FROM stock 
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$id, $user_id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "Élément supprimé du stock";
            } else {
                $_SESSION['error'] = "Élément introuvable ou déjà supprimé";
            }
            
            header('Location: ../stock.php');
            exit();
        }
    }
    
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur de base de données: " . $e->getMessage();
    header('Location: ../stock.php');
    exit();
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../stock.php');
    exit();
}