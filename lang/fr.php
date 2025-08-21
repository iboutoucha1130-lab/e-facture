<?php
return [
    'footer' => [
        'copyright' => 'Solution de facturation pour le Maroc',
        'legal_mentions' => 'Mentions légales',
        'tos' => 'CGU',
        'contact' => 'Contact'
    ],

    'header' => [
        'home' => 'Accueil',
        'pricing' => 'Tarifs',
        'contact' => 'Contact',
        'terms' => 'CGU',
        'dashboard' => 'Tableau de bord',
        'settings' => 'Paramètres',
        'login' => 'Connexion',
        'logout' => 'Déconnexion'
    ],
    'clients' => [
        'title' => 'Gestion des Clients',
        'add_button' => 'Ajouter un client',
        'error_retrieving' => 'Erreur lors de la récupération des clients: ',
        'delete_success' => 'Client supprimé avec succès',
        'delete_error' => 'Erreur lors de la suppression du client: ',
        'empty_title' => 'Aucun client enregistré',
        'empty_subtitle' => 'Commencez par ajouter votre premier client',
        'table' => [
            'name' => 'Nom',
            'phone' => 'Téléphone',
            'email' => 'Email',
            'ice' => 'ICE',
            'actions' => 'Actions'
        ],
        'edit_button' => 'Modifier',
        'delete_button' => 'Supprimer',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce client?'
    ],
    'cgu' => [
        'title' => 'Conditions Générales d\'Utilisation',
        'page_title' => 'Conditions Générales d\'Utilisation',
        'last_update' => 'Dernière mise à jour',
    ],
    'contact' => [
        'title' => 'Contactez-nous',
        'contact_us' => 'Contactez-nous',
        'address' => 'Adresse',
        'address_value' => '123 Avenue Mohammed V',
        'phone' => 'Téléphone',
        'phone_value' => '+212 6 12 34 56 78',
        'email' => 'Email',
        'email_value' => 'contact@efacture-maroc.com',
        'follow_us' => 'Suivez-nous',
        'send_message' => 'Envoyez-nous un message',
        'form' => [
            'fullname' => 'Nom complet',
            'fullname_placeholder' => 'Votre nom complet',
            'email' => 'Adresse email',
            'email_placeholder' => 'Votre adresse email',
            'phone' => 'Téléphone',
            'phone_placeholder' => 'Votre numéro de téléphone',
            'subject' => 'Sujet',
            'subject_options' => [
                'general' => 'Question générale',
                'support' => 'Support technique',
                'partnership' => 'Partenariat',
                'other' => 'Autre'
            ],
            'message' => 'Message',
            'message_placeholder' => 'Votre message...',
            'submit' => 'Envoyer le message'
        ]
    ],
    'dashboard' => [
        'title' => 'Tableau de bord',
        'welcome' => 'Bonjour, :name',
        'documents' => 'Documents',
        'invoices' => 'Factures',
        'quotes' => 'Devis',
        'payments' => 'Paiements',
        'contacts' => 'Contacts',
        'clients' => 'Clients',
        'prospects' => 'Prospects',
        'responsibles' => 'Responsables',
        'management' => 'Gestion',
        'products' => 'Produits',
        'books' => 'Livres',
        'stock' => 'Stock',
        'revenue_evolution' => 'Évolution du chiffre d\'affaires',
        'revenue_dh' => 'CA (DH)',
        'operations' => 'Opérations',
        'total_revenue' => 'CA total',
        'avg_operation' => 'Moyenne/opération',
        'period' => 'Période',
        'months' => 'mois',
        'recent_activity' => 'Activité récente',
        'invoice' => 'Facture',
        'quote' => 'Devis',
        'payment' => 'Paiement',
        'invoice_date' => 'Date facture',
        'quote_date' => 'Date devis',
        'payment_date' => 'Date paiement',
        'valid_until' => 'Valable jusqu\'au',
        'view' => 'Voir',
        'no_activity' => 'Aucune activité récente'
    ],
    'status' => [
        'brouillon' => 'Brouillon',
        'envoyee' => 'Envoyée',
        'payee' => 'Payée',
        'impayee' => 'Impayée',
        'accepte' => 'Accepté',
        'refuse' => 'Refusé',
        'en-cours' => 'En cours'
    ],
    'devis' => [
        'title' => 'Gestion des devis',
        'add_button' => 'Nouveau devis',
        'error_retrieving' => 'Erreur lors de la récupération des devis: ',
        'delete_success' => 'Devis supprimé avec succès',
        'delete_error' => 'Erreur lors de la suppression: ',
        'empty_title' => 'Vous n\'avez aucun devis',
        'empty_subtitle' => 'Commencez par créer votre premier devis',
        'table' => [
            'number' => 'N° Devis',
            'date' => 'Date',
            'client' => 'Client',
            'amount' => 'Montant TTC',
            'status' => 'Statut',
            'actions' => 'Actions'
        ],
        'status' => [
            'brouillon' => 'Brouillon',
            'envoye' => 'Envoyé',
            'accepte' => 'Accepté',
            'refuse' => 'Refusé'
        ],
        'view_button' => 'Voir',
        'edit_button' => 'Modifier',
        'convert_button' => 'Facturer',
        'convert_confirm' => 'Transformer ce devis en facture?',
        'delete_button' => 'Supprimer',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce devis?'
    ],
    'devis_create' => [
        'title' => 'Créer un nouveau devis',
        'general_info' => 'Informations générales',
        'client_label' => 'Client',
        'client_required' => 'Veuillez sélectionner un client',
        'creation_date' => 'Date de création',
        'validity_date' => 'Date de validité',
        'status' => 'Statut',
        'status_options' => [
            'en-cours' => 'En cours',
            'accepte' => 'Accepté',
            'refuse' => 'Refusé'
        ],
        'vat_rate' => 'Taux TVA (%)',
        'lines_title' => 'Lignes du devis',
        'lines_error' => 'Ajoutez au moins une ligne au devis',
        'product_select' => 'Sélectionner un produit',
        'description' => 'Description',
        'quantity' => 'Quantité',
        'unit_price' => 'Prix unitaire (DH)',
        'total' => 'Total (DH)',
        'add_line' => 'Ajouter une ligne',
        'summary' => 'Récapitulatif',
        'subtotal' => 'Total HT:',
        'vat_amount' => 'TVA ({{rate}}%):',
        'total_ttc' => 'Total TTC:',
        'cancel' => 'Annuler',
        'save' => 'Enregistrer le devis',
        'error_general' => 'Erreur lors du chargement :',
        'error_clients' => 'Erreur lors du chargement des clients:',
        'error_products' => 'Erreur lors du chargement des produits:',
        'error_creation' => 'Erreur lors de la création du devis:'
    ],
    'devis_edit' => [
        'title' => 'Modifier devis',
        'edit_quote' => 'Modifier le devis DEV-:id',
        'general_info' => 'Informations générales',
        'client' => 'Client',
        'select_client' => 'Sélectionner un client',
        'creation_date' => 'Date de création',
        'validity_date' => 'Date de validité',
        'status' => 'Statut',
        'status_in_progress' => 'En cours',
        'status_accepted' => 'Accepté',
        'status_refused' => 'Refusé',
        'vat_rate' => 'Taux TVA',
        'quote_lines' => 'Lignes du devis',
        'product' => 'Produit',
        'select_product' => 'Sélectionner un produit',
        'description' => 'Description',
        'description_placeholder' => 'Description',
        'quantity' => 'Quantité',
        'unit_price' => 'Prix unitaire',
        'total' => 'Total',
        'add_line_button' => 'Ajouter une ligne',
        'total_ht' => 'Total HT',
        'vat_amount' => 'TVA (:rate%)',
        'total_ttc' => 'Total TTC',
        'cancel_button' => 'Annuler',
        'update_button' => 'Mettre à jour',
        'error_access' => "Erreur d'accès au devis: ",
        'error_clients' => "Erreur lors du chargement des clients: ",
        'error_products' => "Erreur lors du chargement des produits: ",
        'not_found' => "Devis non trouvé",
        'update_error' => "Erreur lors de la mise à jour du devis: ",
        'add_line' => "Ajoutez au moins une ligne au devis",
    ],
    'devis_view' => [
        'title' => 'Devis DEV-:id - efacture-maroc.com',
        'not_found' => 'Devis non trouvé ou accès refusé',
        'back_button' => 'Retour aux devis',
        'download_pdf' => 'Télécharger PDF',
        'edit_button' => 'Modifier',
        'delete_button' => 'Supprimer',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce devis?',
        'company_ice' => 'ICE',
        'company_address' => 'Adresse',
        'company_phone' => 'Tél',
        'company_email' => 'Email',
        'document_title' => 'DEVIS',
        'document_number' => 'N°',
        'date' => 'Date',
        'status' => 'Statut',
        'recipient' => 'Destinataire',
        'ice' => 'ICE',
        'address' => 'Adresse',
        'phone' => 'Tél',
        'email' => 'Email',
        'table' => [
            'description' => 'Description',
            'quantity' => 'Quantité',
            'unit_price' => 'Prix unitaire',
            'total' => 'Total',
        ],
        'total_ht' => 'Total HT',
        'vat' => 'TVA',
        'total_ttc' => 'Total TTC',
        'validity_conditions' => 'Conditions de validité',
        'validity_date' => 'Date de validité',
        'validity_text' => 'Ce devis est valable jusqu\'à la date indiquée ci-dessus',
        'legal_notice' => 'Mentions légales',
        'legal_conformity' => 'Conforme à la législation marocaine en vigueur.',
        'legal_note' => 'Ce devis n\'est pas une facture et ne constitue pas une obligation de paiement.',
        'date_prefix' => 'Le',
        'signature' => 'Signature',
        'signature_note' => 'Cachet et signature du responsable',
        'generating_pdf' => 'Génération en cours...',
    ],
    'facture_create' => [
        'title' => 'Créer une nouvelle facture',
        'error_loading_clients' => 'Erreur lors du chargement des clients: ',
        'error_loading_products' => 'Erreur lors du chargement des produits: ',
        'select_client' => 'Veuillez sélectionner un client',
        'invoice_date_required' => 'La date de facture est obligatoire',
        'due_date_required' => 'La date d\'échéance est obligatoire',
        'add_at_least_one_line' => 'Ajoutez au moins une ligne à la facture',
        'invoice_creation_error' => 'Erreur lors de la création de la facture: ',
        'general_info' => 'Informations générales',
        'client' => 'Client',
        'select_client' => 'Sélectionner un client',
        'invoice_date' => 'Date de facture',
        'due_date' => 'Date d\'échéance',
        'status' => 'Statut',
        'status_draft' => 'Brouillon',
        'status_sent' => 'Envoyée',
        'status_paid' => 'Payée',
        'status_unpaid' => 'Impayée',
        'vat_rate' => 'Taux TVA',
        'billing_lines' => 'Lignes de facturation',
        'product' => 'Produit',
        'select_product' => 'Sélectionner un produit',
        'description' => 'Description',
        'description_placeholder' => 'Description du produit/service',
        'quantity' => 'Quantité',
        'unit_price' => 'Prix unitaire',
        'total' => 'Total',
        'add_line' => 'Ajouter une ligne',
        'total_ht' => 'Total HT',
        'vat' => 'TVA',
        'total_ttc' => 'Total TTC',
        'cancel' => 'Annuler',
        'save_invoice' => 'Enregistrer la facture'
    ],
    'facture_edit' => [
        'title' => 'Modifier facture FAC-:id',
        'edit_title' => 'Modifier la facture FAC-:id',
        'error_access' => 'Erreur d\'accès à la facture: ',
        'error_clients' => 'Erreur lors du chargement des clients: ',
        'error_products' => 'Erreur lors du chargement des produits: ',
        'not_found' => 'Facture non trouvée',
        'update_error' => 'Erreur lors de la mise à jour de la facture: ',
        'select_client' => 'Veuillez sélectionner un client',
        'required_date' => 'La date de facture est obligatoire',
        'required_due_date' => 'La date d\'échéance est obligatoire',
        'add_line' => 'Ajoutez au moins une ligne à la facture',
        'general_info' => 'Informations générales',
        'client' => 'Client',
        'select_client' => 'Sélectionner un client',
        'invoice_date' => 'Date de facture',
        'due_date' => 'Date d\'échéance',
        'status' => 'Statut',
        'status_draft' => 'Brouillon',
        'status_sent' => 'Envoyée',
        'status_paid' => 'Payée',
        'status_unpaid' => 'Impayée',
        'vat_rate' => 'Taux TVA',
        'invoice_lines' => 'Lignes de facturation',
        'add_line_button' => 'Ajouter une ligne',
        'table' => [
            'product' => 'Produit',
            'description' => 'Description',
            'quantity' => 'Quantité',
            'unit_price' => 'Prix unitaire',
            'total' => 'Total',
        ],
        'select_product' => 'Sélectionner un produit',
        'description_placeholder' => 'Description',
        'total_ht' => 'Total HT',
        'vat_amount' => 'TVA (:rate%)',
        'total_ttc' => 'Total TTC',
        'cancel_button' => 'Annuler',
        'update_button' => 'Mettre à jour',
    ],
    'facture_view' => [
        'title' => 'Facture FAC-:id - efacture-maroc.com',
        'error_not_found' => 'Facture non trouvée ou accès refusé',
        'back_button' => 'Retour aux factures',
        'download_pdf' => 'Télécharger PDF',
        'edit_button' => 'Modifier',
        'delete_button' => 'Supprimer',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer cette facture?',
        'invoice' => 'FACTURE',
        'number' => 'N°',
        'date' => 'Date',
        'billed_to' => 'Facturé à:',
        'ice' => 'ICE',
        'address' => 'Adresse',
        'phone' => 'Tél',
        'email' => 'Email',
        'table' => [
            'description' => 'Description',
            'quantity' => 'Quantité',
            'unit_price' => 'Prix unitaire (DH)',
            'total' => 'Total (DH)',
        ],
        'total_ht' => 'Total HT',
        'vat' => 'TVA',
        'total_ttc' => 'Total TTC',
        'payment_terms' => 'Conditions de paiement',
        'due_date' => 'Date d\'échéance',
        'payment_method' => 'Mode de paiement',
        'bank_transfer' => 'Virement bancaire',
        'rib' => 'RIB',
        'legal_notice' => 'Mentions légales',
        'legal_text' => 'Conforme à la législation marocaine en vigueur.',
        'payment_conditions' => 'La facture est payable à réception. Tout retard de paiement entraînera des pénalités de retard au taux légal.',
        'signature' => 'Signature',
        'stamp_and_signature' => 'Cachet et signature du responsable',
        'company' => [
            'ice' => 'ICE',
            'address' => 'Adresse',
            'phone' => 'Tél',
            'email' => 'Email',
        ],
        'pdf_filename' => 'facture-:id.pdf',
        'generating_pdf' => 'Génération en cours...',
    ],
    'factures' => [
        'title' => 'Gestion des factures',
        'new_button' => 'Nouvelle facture',
        'error_retrieving' => 'Erreur lors de la récupération des factures: ',
        'delete_success' => 'Facture supprimée avec succès',
        'delete_error' => 'Erreur lors de la suppression: ',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer cette facture?',
        
        'filter' => [
            'status' => 'Statut',
            'all' => 'Tous',
            'client' => 'Client',
            'all_clients' => 'Tous les clients',
            'from' => 'De',
            'to' => 'À',
            'button' => 'Filtrer'
        ],
        
        'status' => [
            'draft' => 'Brouillon',
            'sent' => 'Envoyée',
            'paid' => 'Payée',
            'unpaid' => 'Impayée',
            'brouillon' => 'Brouillon',
            'envoyee' => 'Envoyée',
            'payee' => 'Payée',
            'impayee' => 'Impayée'
        ],
        
        'empty_title' => 'Vous n\'avez aucune facture',
        'empty_subtitle' => 'Commencez par créer votre première facture',
        
        'table' => [
            'invoice_number' => 'N° Facture',
            'date' => 'Date',
            'client' => 'Client',
            'amount' => 'Montant TTC',
            'status' => 'Statut',
            'due_date' => 'Échéance',
            'actions' => 'Actions'
        ],
        
        'action' => [
            'view' => 'Voir',
            'edit' => 'Modifier',
            'delete' => 'Supprimer'
        ]
    ],
    'facturer' => [
        'invalid_quote' => 'Devis invalide',
        'quote_not_found' => 'Devis introuvable',
        'conversion_success' => 'Devis transformé en facture avec succès',
        'conversion_error' => 'Erreur lors de la transformation du devis: :error',
    ],
    'forgot_password' => [
        'title' => 'Réinitialisation du mot de passe - efacture-maroc.com',
        'step1' => [
            'title' => 'Réinitialisation du mot de passe',
            'subtitle' => 'Entrez vos informations pour vérifier votre identité'
        ],
        'step2' => [
            'title' => 'Nouveau mot de passe',
            'subtitle' => 'Définissez votre nouveau mot de passe'
        ],
        'step3' => [
            'title' => 'Mot de passe réinitialisé',
            'subtitle' => 'Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.'
        ],
        'email' => 'Adresse email',
        'fullname' => 'Nom complet',
        'company' => 'Nom de l\'entreprise',
        'new_password' => 'Nouveau mot de passe',
        'confirm_password' => 'Confirmer le mot de passe',
        'verify_button' => 'Vérifier l\'identité',
        'reset_button' => 'Réinitialiser le mot de passe',
        'login_button' => 'Se connecter',
        'placeholder' => [
            'email' => 'exemple@email.com',
            'fullname' => 'Votre nom complet',
            'company' => 'Nom de votre entreprise',
            'new_password' => 'Votre nouveau mot de passe',
            'confirm_password' => 'Confirmez votre mot de passe'
        ],
        'error' => [
            'required_fields' => 'Tous les champs sont obligatoires',
            'no_account' => 'Les informations fournies ne correspondent à aucun compte',
            'password_mismatch' => 'Les mots de passe ne correspondent pas',
            'technical' => 'Erreur technique. Veuillez réessayer.'
        ],
        'success' => [
            'reset_success' => 'Votre mot de passe a été réinitialisé avec succès'
        ]
    ],
    'index' => [
        'title' => 'efacture-maroc.com - Facturation en ligne pour le Maroc',
        'hero' => [
            'title' => 'Gérez vos factures simplement',
            'subtitle' => 'Solution de facturation en ligne 100% marocaine pour les TPE et PME',
            'login_button' => 'Se connecter',
            'register_button' => "S'inscrire gratuitement",
            'dashboard_button' => 'Accéder à mon espace'
        ],
        'features' => [
            'title' => 'Pourquoi choisir efacture-maroc ?',
            'item1' => [
                'title' => 'Conforme à la loi marocaine',
                'description' => 'Factures aux normes DGI avec ICE obligatoire'
            ],
            'item2' => [
                'title' => 'Multilingue',
                'description' => 'Interface en français et arabe'
            ],
            'item3' => [
                'title' => 'Paiements en ligne',
                'description' => 'Intégration avec CMI et les banques locales'
            ]
        ],
        'testimonials' => [
            'title' => 'Ils nous font confiance',
            'quote' => "efacture-maroc m'a fait gagner un temps précieux dans la gestion de ma petite entreprise.",
            'author' => 'Ahmed, gérant de magasin'
        ]
    ],
    'livre_detail' => [
        'title' => 'Détails Livre Comptable',
        'back_button' => 'Retour',
        'no_transactions' => 'Aucune transaction trouvée pour cette période',
        'na' => 'N/A',
        'currency' => 'DH',
        'table' => [
            'date' => 'Date',
            'type' => 'Type',
            'reference' => 'Référence',
            'client' => 'Client',
            'amount' => 'Montant',
            'status' => 'Statut'
        ],
        'types' => [
            'facture' => 'Facture',
            'devis' => 'Devis',
            'paiement' => 'Paiement'
        ],
        'status' => [
            'brouillon' => 'Brouillon',
            'envoye' => 'Envoyé',
            'accepte' => 'Accepté',
            'refuse' => 'Refusé',
            'envoyee' => 'Envoyée',
            'payee' => 'Payée',
            'impayee' => 'Impayée'
        ],
        'months' => [
            'january' => 'Janvier',
            'february' => 'Février',
            'march' => 'Mars',
            'april' => 'Avril',
            'may' => 'Mai',
            'june' => 'Juin',
            'july' => 'Juillet',
            'august' => 'Août',
            'september' => 'Septembre',
            'october' => 'Octobre',
            'november' => 'Novembre',
            'december' => 'Décembre'
        ]
    ],
    'error' => [
        'db_error' => 'Erreur: '
    ],
    'livres_comptables' => [
        'title' => 'Livres Comptables',
        'subtitle' => 'Registre automatique de toutes vos transactions',
        'table' => [
            'period' => 'Période',
            'invoices' => 'Factures',
            'quotes' => 'Devis',
            'payments' => 'Paiements',
            'total_amount' => 'Montant Total',
            'actions' => 'Actions',
            'view_details' => 'Voir détails'
        ],
        'empty_message' => 'Aucune transaction enregistrée',
        'empty_submessage' => 'Vos transactions apparaîtront automatiquement ici'
    ],
    'error' => [
        'db_error' => 'Erreur: '
    ],
    'login' => [
        'title' => 'Connexion - efacture-maroc.com',
        'heading' => 'Connexion à votre compte',
        'subheading' => 'Entrez vos identifiants pour accéder à votre espace',
        'email_label' => 'Adresse email',
        'email_placeholder' => 'exemple@email.com',
        'password_label' => 'Mot de passe',
        'password_placeholder' => 'Votre mot de passe',
        'remember_me' => 'Se souvenir de moi',
        'forgot_password' => 'Mot de passe oublié ?',
        'submit_button' => 'Se connecter',
        'no_account' => "Vous n'avez pas de compte ?",
        'register_link' => 'Créer un compte',
        'required_fields' => 'Tous les champs sont obligatoires',
        'invalid_credentials' => 'Email ou mot de passe incorrect',
        'technical_error' => 'Erreur technique. Veuillez réessayer.',
        'logout_success' => 'Vous avez été déconnecté avec succès'
    ],
    
    'logout' => [
        'success' => 'Vous avez été déconnecté avec succès',
        'error' => 'Erreur lors de la déconnexion'
    ],
    'error' => [
        'token_deletion' => 'Erreur lors de la suppression du token Remember Me: ',
        'db_error' => 'Erreur de base de données: '
    ],

    'mentions-legales' => [
        'page_title' => "Mentions légales - efacture-maroc.com",
        'title' => "Mentions légales",
        'intro' => "Conformément aux dispositions légales en vigueur au Maroc, nous vous informons des mentions légales concernant notre service efacture-maroc.com.",
        
        'section1' => [
            'title' => "1. Éditeur du site",
            'denomination' => "Dénomination sociale :",
            'denomination_value' => "SARL efacture-maroc",
            'forme_juridique' => "Forme juridique :",
            'forme_juridique_value' => "Société à responsabilité limitée",
            'siege_social' => "Siège social :",
            'siege_social_value' => "",
            'telephone' => "Téléphone :",
            'telephone_value' => "+212 5 37 77 77 77",
            'email' => "Email :",
            'ice' => "ICE :",
            'registre_commerce' => "Registre du commerce :",
            'patente' => "Patente :",
            'cnss' => "CNSS :"
        ],
        
        'section2' => [
            'title' => "2. Hébergement",
            'content' => "Le site efacture-maroc.com est hébergé par :",
            'hebergeur' => "Maroc Telecom Hosting",
            'adresse' => "Adresse : ",
            'telephone' => "Téléphone : +212 5 22 22 22 22"
        ],
        
        'section3' => [
            'title' => "3. Propriété intellectuelle",
            'content' => "L'ensemble des éléments constituant le site efacture-maroc.com (textes, images, vidéos, logos, etc.) sont la propriété exclusive de SARL efacture-maroc ou de ses partenaires et sont protégés par les lois marocaines et internationales relatives à la propriété intellectuelle."
        ],
        
        'section4' => [
            'title' => "4. Protection des données personnelles",
            'content' => "Conformément à la loi 09-08 relative à la protection des personnes physiques à l'égard du traitement des données à caractère personnel, vous disposez d'un droit d'accès, de rectification et d'opposition aux données vous concernant.",
            'contact' => "Pour exercer ce droit, vous pouvez nous contacter à l'adresse email :"
        ],
        
        'section5' => [
            'title' => "5. Cookies",
            'content' => "Le site efacture-maroc.com utilise des cookies pour améliorer l'expérience utilisateur. Ces cookies ne contiennent aucune information personnelle et sont uniquement utilisés pour le fonctionnement technique du site."
        ],
        
        'section6' => [
            'title' => "6. Responsabilité",
            'content' => "SARL efacture-maroc ne pourra être tenue responsable des dommages directs ou indirects résultant de l'utilisation du site ou des services proposés."
        ],
        
        'section7' => [
            'title' => "7. Droit applicable",
            'content' => "Les présentes mentions légales sont régies par le droit marocain. Tout litige relatif à leur interprétation ou à leur exécution relève de la compétence exclusive des tribunaux marocains."
        ]
    ],
    'paiement_create' => [
        'title' => 'Enregistrer un paiement',
        'error_loading' => 'Erreur lors du chargement des factures: ',
        'select_invoice' => 'Veuillez sélectionner une facture',
        'amount_error' => 'Le montant doit être supérieur à 0',
        'date_required' => 'La date de paiement est obligatoire',
        'save_error' => "Erreur lors de l'enregistrement: ",
        'general_info' => 'Informations générales',
        'invoice' => 'Facture',
        'amount' => 'Montant',
        'payment_method' => 'Mode de paiement',
        'payment_date' => 'Date de paiement',
        'reference' => 'Référence',
        'notes' => 'Notes',
        'methods' => [
            'bank_transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'cash' => 'Espèces',
            'card' => 'Carte bancaire'
        ],
        'reference_placeholder' => 'N° chèque, référence virement...',
        'cancel' => 'Annuler',
        'save' => 'Enregistrer'
    ],
    'paiement_edit' => [
        'title' => 'Modifier le paiement',
        'back_link' => 'Retour à la liste',
        'error_retrieving' => 'Erreur lors de la récupération du paiement: ',
        'update_success' => 'Paiement mis à jour avec succès',
        'update_error' => 'Erreur lors de la mise à jour: ',
        'error_factures' => 'Erreur lors de la récupération des factures: ',
        'invoice_label' => 'Facture associée',
        'amount_label' => 'Montant (DH)',
        'date_label' => 'Date de paiement',
        'method_label' => 'Mode de paiement',
        'method_cash' => 'Espèces',
        'method_check' => 'Chèque',
        'method_transfer' => 'Virement',
        'method_card' => 'Carte bancaire',
        'method_other' => 'Autre',
        'reference_label' => 'Référence',
        'save_button' => 'Enregistrer',
    ],
    
    'paiement_view' => [
        'title' => 'Paiement PAY-:id - efacture-maroc.com',
        'not_found' => 'Paiement non trouvé ou accès refusé',
        'back_button' => 'Retour aux paiements',
        'download_pdf' => 'Télécharger PDF',
        'edit_button' => 'Modifier',
        'delete_button' => 'Supprimer',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce paiement?',
        'company' => [
            'ice' => 'ICE',
            'address' => 'Adresse',
            'phone' => 'Tél',
            'email' => 'Email',
        ],
        'receipt_title' => 'REÇU DE PAIEMENT',
        'receipt_number' => 'N° PAY-:id',
        'date' => 'Date',
        'received_from' => 'Reçu de:',
        'client' => [
            'ice' => 'ICE',
            'address' => 'Adresse',
        ],
        'invoice_concerned' => 'Facture concernée',
        'amount_paid' => 'Montant payé',
        'payment_method' => 'Mode de paiement',
        'reference' => 'Référence',
        'notes' => 'Notes',
        'no_notes' => 'Aucune note',
        'total_amount' => 'MONTANT TOTAL',
        'bank_details' => 'Détails bancaires',
        'bank' => 'Banque',
        'rib' => 'RIB',
        'swift_code' => 'Code Swift',
        'legal_mentions' => [
            'title' => 'Mentions légales',
            'content' => 'Ce reçu atteste du paiement de la facture mentionnée ci-dessus.',
        ],
        'date_signed' => 'Le :date',
        'signature' => 'Signature',
        'signature_note' => 'Cachet et signature du responsable',
        'generating_pdf' => 'Génération en cours...',
    ],
    'paiements' => [
        'title' => 'Gestion des paiements',
        'add_button' => 'Enregistrer un paiement',
        'error_retrieving' => 'Erreur lors de la récupération des paiements: ',
        'delete_success' => 'Paiement supprimé avec succès',
        'delete_error' => 'Erreur lors de la suppression: ',
        'empty_title' => 'Aucun paiement enregistré',
        'empty_subtitle' => 'Commencez par enregistrer votre premier paiement',
        'table' => [
            'date' => 'Date',
            'invoice' => 'N° Facture',
            'client' => 'Client',
            'amount' => 'Montant',
            'method' => 'Mode',
            'reference' => 'Référence',
            'actions' => 'Actions'
        ],
        'view_button' => 'Voir',
        'edit_button' => 'Modifier',
        'delete_button' => 'Supprimer',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce paiement?'
    ],
    'settings' => [
        'title' => 'Paramètres',
        'tabs' => [
            'general' => 'Général',
            'profile' => 'Profil',
            'about' => 'À propos'
        ]
    ],
    'language' => [
        'title' => 'Langue',
        'options' => [
            'fr' => 'Français',
            'ar' => 'العربية'
        ],
        'button' => 'Changer la langue'
    ],
    'notifications' => [
        'title' => 'Notifications',
        'email' => 'Notifications par email',
        'deadlines' => 'Alertes échéances',
        'payments' => 'Alertes paiements'
    ],
    'profile' => [
        'title' => 'Profil Utilisateur',
        'subtitle' => 'Gérez vos informations personnelles',
        'fullname' => 'Nom complet',
        'email' => 'Adresse email',
        'phone' => 'Téléphone',
        'company' => 'Entreprise',
        'update_button' => 'Mettre à jour'
    ],
    'password' => [
        'title' => 'Mot de passe',
        'subtitle' => 'Changez votre mot de passe régulièrement',
        'current' => 'Mot de passe actuel',
        'new' => 'Nouveau mot de passe',
        'confirm' => 'Confirmer le mot de passe',
        'update_button' => 'Changer le mot de passe'
    ],
    'about' => [
        'title' => 'À propos de efacture-maroc.com',
        'subtitle' => 'Solution de facturation en ligne pour les professionnels marocains',
        'version' => 'Version',
        'support' => 'Support technique'
    ],
    'error' => [
        'required_field' => 'Ce champ est obligatoire',
        'database' => 'Erreur de base de données',
        'password_mismatch' => 'Les mots de passe ne correspondent pas',
        'wrong_password' => 'Mot de passe actuel incorrect'
    ],
    'success' => [
        'profile_update' => 'Profil mis à jour avec succès',
        'password_update' => 'Mot de passe changé avec succès'
    ],
    'process_contact' => [
        'title' => 'Traitement du contact - efacture-maroc.com',
        
        'success' => [
            'title' => 'Message envoyé!',
            'message' => 'Nous avons bien reçu votre message et nous vous répondrons dans les plus brefs délais.',
            'thank_you' => 'Merci pour votre message',
            'follow_up' => 'Notre équipe vous contactera bientôt.',
            'back_button' => 'Retour à la page de contact'
        ],
        
        'errors' => [
            'title' => 'Erreur!',
            'subtitle' => 'Veuillez corriger les erreurs ci-dessous.',
            'description' => 'Votre message n\'a pas pu être envoyé en raison des erreurs ci-dessus.',
            'back_button' => 'Retour au formulaire',
            
            'name_required' => 'Le nom complet est obligatoire',
            'email_required' => 'L\'email est obligatoire',
            'email_invalid' => 'L\'email n\'est pas valide',
            'message_required' => 'Le message est obligatoire'
        ],
        
        'processing' => [
            'title' => 'Traitement de votre message',
            'message' => 'Traitement en cours...',
            'subtitle' => 'Veuillez patienter pendant que nous traitons votre demande.'
        ]
    ],
    

    'produit_create' => [
        'title' => 'Nouveau produit - efacture-maroc.com',
        'heading' => 'Ajouter un nouveau produit/service',
        'back_link' => 'Retour à la liste',
        'success_message' => 'Produit créé avec succès!',
        'view_list' => 'Voir la liste',
        'errors' => [
            'name_required' => 'Le nom du produit est obligatoire',
            'price_invalid' => 'Le prix doit être un nombre valide supérieur à 0',
            'image_upload' => 'Erreur lors du téléchargement de l\'image',
            'image_type' => 'Type de fichier non autorisé. Formats acceptés: JPG, PNG, GIF',
            'db_error' => 'Erreur lors de la création du produit: '
        ],
        'categories' => [
            'service' => 'Service',
            'software' => 'Logiciel',
            'hardware' => 'Matériel',
            'consultation' => 'Consultation',
            'training' => 'Formation',
            'maintenance' => 'Maintenance',
            'other' => 'Autre'
        ],
        'form' => [
            'name_label' => 'Nom du produit',
            'price_label' => 'Prix (DH)',
            'category_label' => 'Catégorie',
            'category_select' => 'Sélectionner une catégorie',
            'image_label' => 'Image du produit',
            'image_hint' => 'Formats acceptés: JPG, PNG, GIF (max 2MB)',
            'description_label' => 'Description',
            'current_image' => 'Image actuelle:',
            'image_alt' => 'Image produit'
        ],
        'cancel_button' => 'Annuler',
        'save_button' => 'Enregistrer'
    ],
    'produit_edit' => [
        'title' => 'Modifier produit',
        'edit_product' => 'Modifier le produit: :name',
        'back_to_list' => 'Retour à la liste',
        'success_message' => 'Produit mis à jour avec succès!',
        'view_list' => 'Voir la liste',
        'error_retrieving' => 'Erreur lors de la récupération du produit: ',
        'update_error' => 'Erreur lors de la mise à jour du produit: ',
        'name_required' => 'Le nom du produit est obligatoire',
        'price_invalid' => 'Le prix doit être un nombre valide supérieur à 0',
        'upload_error' => "Erreur lors du téléchargement de l'image",
        'file_type_error' => "Type de fichier non autorisé. Formats acceptés: JPG, PNG, GIF",
        'name_label' => 'Nom du produit',
        'price_label' => 'Prix (DH)',
        'category_label' => 'Catégorie',
        'select_category' => 'Sélectionner une catégorie',
        'new_image_label' => 'Nouvelle image',
        'image_keep_note' => "Laissez vide pour conserver l'image actuelle",
        'description_label' => 'Description',
        'current_image' => 'Image actuelle:',
        'delete_image' => 'Supprimer cette image',
        'cancel_button' => 'Annuler',
        'update_button' => 'Mettre à jour'
    ],
    'produits' => [
        'title' => 'Gestion des produits',
        'add_button' => 'Ajouter un produit',
        'error_retrieving' => 'Erreur lors de la récupération des produits: ',
        'delete_success' => 'Produit supprimé avec succès',
        'delete_error' => 'Erreur lors de la suppression: ',
        'empty_title' => 'Vous n\'avez aucun produit enregistré',
        'empty_subtitle' => 'Commencez par ajouter votre premier produit',
        'table' => [
            'name' => 'Nom',
            'description' => 'Description',
            'category' => 'Catégorie',
            'price' => 'Prix (DH)',
            'actions' => 'Actions'
        ],
        'edit_button' => 'Modifier',
        'delete_button' => 'Supprimer',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce produit?'
    ],
    'prospects' => [
        'title' => 'Gestion des prospects',
        'add_button' => 'Ajouter un prospect',
        'error_retrieving' => 'Erreur lors de la récupération des prospects: ',
        'delete_success' => 'Prospect supprimé avec succès',
        'delete_error' => 'Erreur lors de la suppression: ',
        'empty_title' => 'Vous n\'avez aucun prospect enregistré',
        'empty_subtitle' => 'Commencez par ajouter votre premier prospect',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce prospect?',
        'edit_button' => 'Modifier',
        'delete_button' => 'Supprimer',
        'table' => [
            'name' => 'Nom',
            'company' => 'Entreprise',
            'phone' => 'Téléphone',
            'email' => 'Email',
            'status' => 'Statut',
            'actions' => 'Actions'
        ],
        'status' => [
            'nouveau' => 'Nouveau',
            'contacte' => 'Contacté',
            'suivi' => 'En suivi',
            'converti' => 'Converti'
        ]
    ],
    'prospects_create' => [
        'title' => 'Ajouter un prospect',
        'info_section' => 'Informations du prospect',
        'fullname_label' => 'Nom complet',
        'fullname_placeholder' => 'Nom et prénom du prospect',
        'company_label' => 'Entreprise',
        'company_placeholder' => 'Nom de l\'entreprise',
        'phone_label' => 'Téléphone',
        'phone_placeholder' => 'Numéro de téléphone',
        'email_label' => 'Email',
        'email_placeholder' => 'Adresse email',
        'source_label' => 'Source',
        'source_default' => 'Sélectionner une source',
        'source_website' => 'Site web',
        'source_social' => 'Réseaux sociaux',
        'source_recommendation' => 'Recommandation',
        'source_event' => 'Salon/Événement',
        'source_other' => 'Autre',
        'status_label' => 'Statut',
        'status_new' => 'Nouveau',
        'status_contacted' => 'Contacté',
        'status_followup' => 'En suivi',
        'status_converted' => 'Converti',
        'cancel_button' => 'Annuler',
        'save_button' => 'Enregistrer le prospect',
        'error_name_required' => 'Le nom du prospect est obligatoire',
        'error_invalid_email' => 'L\'email n\'est pas valide',
        'error_adding' => 'Erreur lors de l\'ajout du prospect: ',
        'success_added' => 'Prospect ajouté avec succès'
    ],
    'prospects_edit' => [
        'title' => 'Modifier prospect',
        'info_title' => 'Informations du prospect',
        'name_label' => 'Nom',
        'name_placeholder' => 'Nom complet du prospect',
        'company_label' => 'Entreprise',
        'company_placeholder' => 'Nom de l\'entreprise',
        'phone_label' => 'Téléphone',
        'phone_placeholder' => 'Numéro de téléphone',
        'email_label' => 'Email',
        'email_placeholder' => 'Adresse email',
        'source_label' => 'Source',
        'source_placeholder' => 'Comment avez-vous trouvé ce prospect',
        'status_label' => 'Statut',
        'status_new' => 'Nouveau',
        'status_contacted' => 'Contacté',
        'status_followup' => 'En suivi',
        'status_converted' => 'Converti',
        'cancel_button' => 'Annuler',
        'save_button' => 'Mettre à jour',
        'errors' => [
            'name_required' => 'Le nom est obligatoire',
            'company_required' => 'L\'entreprise est obligatoire',
        ],
        'db_error' => 'Erreur d\'accès au prospect: ',
        'update_error' => 'Erreur lors de la mise à jour du prospect: ',
        'success' => 'Prospect mis à jour avec succès',
    ],
    'register' => [
        'title' => 'Inscription - efacture-maroc.com',
        'heading' => 'Créer un compte',
        'subtitle' => 'Rejoignez notre plateforme de facturation en ligne',
        'success_message' => 'Compte créé avec succès! <a href="login.php" class="font-medium text-green-800 hover:text-green-700">Connectez-vous</a>',
        'general_error' => 'Erreur technique. Veuillez réessayer.',
        'form' => [
            'name' => 'Nom complet',
            'name_placeholder' => 'Votre nom complet',
            'name_error' => 'Le nom complet est obligatoire',
            'email' => 'Adresse email',
            'email_placeholder' => 'exemple@email.com',
            'email_error' => [
                'required' => "L'email est obligatoire",
                'invalid' => "Format d'email invalide",
                'used' => "Cet email est déjà utilisé"
            ],
            'company' => "Nom de l'entreprise",
            'company_placeholder' => "Votre entreprise (facultatif)",
            'password' => 'Mot de passe',
            'password_placeholder' => 'Votre mot de passe',
            'password_error' => [
                'required' => "Le mot de passe est obligatoire",
                'length' => "Le mot de passe doit contenir au moins 6 caractères"
            ],
            'confirm_password' => 'Confirmer le mot de passe',
            'confirm_password_placeholder' => 'Confirmez votre mot de passe',
            'confirm_password_error' => 'Les mots de passe ne correspondent pas',
            'submit' => "S'inscrire",
            'login_link' => 'Vous avez déjà un compte? <a href="login.php" class="font-medium text-primary hover:text-green-800">Connectez-vous</a>',
            'required_fields' => '* Champs obligatoires'
        ],
        'account_creation_error' => 'Erreur lors de la création du compte: '
    ],
    'responsable_create' => [
        'title' => 'Ajouter un nouveau responsable',
        'personal_info' => 'Informations personnelles',
        'fullname' => 'Nom complet',
        'email' => 'Email',
        'role' => 'Rôle',
        'status' => 'Statut',
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'permissions_title' => 'Permissions',
        'permissions' => [
            'invoices' => 'Gestion des factures',
            'quotes' => 'Gestion des devis',
            'clients' => 'Gestion des clients',
            'products' => 'Gestion des produits',
            'payments' => 'Gestion des paiements',
            'reports' => 'Consultation des rapports'
        ],
        'cancel' => 'Annuler',
        'save' => 'Enregistrer',
        'success' => 'Responsable créé avec succès',
        'errors' => [
            'name_required' => 'Le nom est obligatoire',
            'email_required' => 'L\'email est obligatoire',
            'email_invalid' => 'L\'email n\'est pas valide',
            'role_required' => 'Le rôle est obligatoire',
            'email_exists' => 'Cet email est déjà utilisé',
            'general' => 'Erreur lors de la création du responsable: '
        ]
    ],
    'responsable_edit' => [
        'title' => 'Modifier responsable',
        'general_info' => 'Informations générales',
        'fullname' => 'Nom complet',
        'email' => 'Email',
        'role' => 'Rôle',
        'permissions_title' => 'Permissions',
        'permissions' => [
            'invoices' => 'Gestion des factures',
            'quotes' => 'Gestion des devis',
            'clients' => 'Gestion des clients',
            'products' => 'Gestion des produits',
            'payments' => 'Gestion des paiements',
            'reports' => 'Accès aux rapports'
        ],
        'cancel_button' => 'Annuler',
        'update_button' => 'Mettre à jour',
        'update_success' => 'Responsable mis à jour avec succès',
        'db_error' => 'Erreur d\'accès au responsable: ',
        'update_error' => 'Erreur lors de la mise à jour: ',
        'errors' => [
            'name_required' => 'Le nom est obligatoire',
            'email_required' => 'L\'email est obligatoire',
            'email_invalid' => 'L\'email n\'est pas valide',
            'role_required' => 'Le rôle est obligatoire'
        ]
    ],
    'responsables' => [
        'title' => 'Gestion des Responsables',
        'subtitle' => 'Gérez les accès et permissions de votre équipe',
        'add_button' => 'Ajouter un responsable',
        'search_placeholder' => 'Rechercher...',
        'search_button' => 'Rechercher',
        'count_label' => 'responsable(s)',
        'delete_success' => 'Responsable supprimé avec succès',
        'delete_error' => 'Erreur lors de la suppression: ',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce responsable?',
        'empty_message' => 'Aucun responsable enregistré',
        'table' => [
            'name' => 'Nom',
            'email' => 'Email',
            'role' => 'Rôle',
            'permissions' => 'Permissions',
            'actions' => 'Actions'
        ]
    ],
    'error' => [
        'db_error' => 'Erreur: '
    ],
    'stock' => [
        'title' => 'Gestion du Stock',
        'subtitle' => 'Suivez et gérez votre inventaire',
        'add_button' => 'Ajouter au stock',
        'table' => [
            'product' => 'Produit',
            'category' => 'Catégorie',
            'unit_price' => 'Prix unitaire',
            'quantity' => 'Quantité',
            'alert_threshold' => 'Seuil d\'alerte',
            'location' => 'Emplacement',
            'actions' => 'Actions',
            'empty' => 'Aucun produit en stock pour le moment',
        ],
        'modal' => [
            'add_title' => 'Ajouter au stock',
            'edit_title' => 'Modifier le stock',
            'product_label' => 'Produit',
            'product_select' => 'Sélectionnez un produit',
            'quantity_label' => 'Quantité',
            'threshold_label' => 'Seuil d\'alerte',
            'location_label' => 'Emplacement',
            'cancel' => 'Annuler',
            'save' => 'Enregistrer',
        ],
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer cet élément du stock ?',
        'alerts' => [
            'critical' => 'Stock épuisé',
            'low' => 'Stock faible',
        ],
        'errors' => [
            'db_error' => 'Erreur de base de données: ',
        ],
    ],
    'tarifs' => [
        'title' => 'Nos offres d\'abonnement',
        'subtitle' => 'Choisissez le plan qui correspond à vos besoins',
        'popular' => 'Le plus populaire',
        
        'basic' => [
            'name' => 'Basique',
            'price' => 'Gratuit',
            'period' => 'à vie',
            'button' => 'Commencer'
        ],
        
        'pro' => [
            'name' => 'Professionnel',
            'price' => '199 DH',
            'period' => 'par mois',
            'button' => 'Essayer gratuitement'
        ],
        
        'enterprise' => [
            'name' => 'Entreprise',
            'price' => 'Personnalisé',
            'period' => 'solution sur mesure',
            'button' => 'Contact commercial'
        ],
        
        'features' => [
            'invoices' => 'Factures illimitées',
            'clients' => 'Clients illimités',
            'support' => 'Support par email',
            'storage' => 'Stockage de documents',
            'advanced' => 'Fonctionnalités avancées',
            'team' => 'Gestion d\'équipe',
            'priority' => 'Support prioritaire',
            'reports' => 'Rapports avancés'
        ],
        
        'cta_title' => 'Vous avez des besoins spécifiques ?',
        'cta_subtitle' => 'Contactez notre équipe pour une solution personnalisée adaptée à votre entreprise.',
        'cta_button' => 'Demander un devis'
    ],

    'contact' => [
        'title' => 'Contact - efacture-maroc.com',
        'contact_us' => 'Contactez-nous',
        'address' => 'Adresse',
        'address_value' => '',
        'phone' => 'Téléphone',
        'phone_value' => '+212 6 12 34 56 78',
        'email' => 'Email',
        'email_value' => 'contact@efacture-maroc.com',
        'follow_us' => 'Suivez-nous',
        'send_message' => 'Envoyez-nous un message',
        'form' => [
            'fullname' => 'Nom complet',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'subject' => 'Sujet',
            'subject_options' => [
                'general' => 'Question générale',
                'support' => 'Support technique',
                'partnership' => 'Partenariat',
                'other' => 'Autre'
            ],
            'message' => 'Message',
            'submit' => 'Envoyer le message'
        ]
    ],
    'clients' => [
        'title' => 'Gestion des Clients',
        'add_button' => 'Ajouter un client',
        'empty_title' => 'Aucun client enregistré',
        'empty_subtitle' => 'Commencez par ajouter votre premier client',
        'error_retrieving' => 'Erreur lors de la récupération des clients: ',
        'delete_success' => 'Client supprimé avec succès',
        'delete_error' => 'Erreur lors de la suppression du client: ',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce client?',
        'delete_button' => 'Supprimer',
        'edit_button' => 'Modifier',
        'table' => [
            'name' => 'Nom',
            'phone' => 'Téléphone',
            'email' => 'Email',
            'ice' => 'ICE',
            'actions' => 'Actions'
        ]
    ],
    'client_edit' => [
        'title' => 'Modifier un client',
        'success_message' => 'Client mis à jour avec succès.',
        'back_to_list' => 'Retour à la liste des clients',
        'cancel_button' => 'Annuler',
        'save_button' => 'Enregistrer',
        'errors' => [
            'name_required' => 'Le nom du client est requis.',
            'ice_required' => 'L\'ICE est requis.',
            'ice_invalid' => 'L\'ICE doit contenir exactement 15 chiffres.',
            'email_invalid' => 'L\'email n\'est pas valide.'
        ],
        'form' => [
            'name_label' => 'Nom complet',
            'name_placeholder' => 'Nom du client',
            'ice_label' => 'ICE',
            'ice_placeholder' => '15 chiffres ICE',
            'phone_label' => 'Téléphone',
            'phone_placeholder' => 'Numéro de téléphone',
            'email_label' => 'Email',
            'email_placeholder' => 'Adresse email',
            'address_label' => 'Adresse',
            'address_placeholder' => 'Adresse complète',
            'city_label' => 'Ville',
            'city_placeholder' => 'Nom de la ville',
            'postal_label' => 'Code postal',
            'postal_placeholder' => 'Code postal'
        ]
    ],
    'error' => [
        'db_error' => 'Erreur de base de données : '
    ],
    'client_create' => [
        'title' => 'Créer un nouveau client',
        'errors' => [
            'name_required' => 'Le nom du client est requis',
            'ice_required' => 'L\'ICE est requis',
            'ice_invalid' => 'L\'ICE doit contenir exactement 15 chiffres',
            'email_invalid' => 'L\'email n\'est pas valide',
            'db_error' => 'Erreur lors de l\'enregistrement : '
        ],
        'success' => [
            'message' => 'Client créé avec succès. ',
            'link' => 'Voir la liste des clients'
        ],
        'form' => [
            'name' => 'Nom complet',
            'name_placeholder' => 'Entrez le nom complet du client',
            'ice' => 'ICE',
            'ice_placeholder' => 'Entrez les 15 chiffres de l\'ICE',
            'phone' => 'Téléphone',
            'phone_placeholder' => 'Entrez le numéro de téléphone',
            'email' => 'Email',
            'email_placeholder' => 'Entrez l\'email du client',
            'address' => 'Adresse',
            'address_placeholder' => 'Entrez l\'adresse complète',
            'city' => 'Ville',
            'city_placeholder' => 'Entrez la ville',
            'zip' => 'Code postal',
            'zip_placeholder' => 'Entrez le code postal'
        ],
        'buttons' => [
            'cancel' => 'Annuler',
            'save' => 'Enregistrer'
        ]
    ],
    'cgu' => [
        'title' => 'Conditions Générales d\'Utilisation',
        'page_title' => 'Conditions Générales d\'Utilisation',
        'last_update' => 'Dernière mise à jour',
        'section1' => [
            'title' => '1. Objet',
            'content' => 'Les présentes conditions générales d\'utilisation (CGU) ont pour objet de définir les modalités de mise à disposition des services du site efacture-maroc.com et les conditions d\'utilisation des services par l\'Utilisateur.'
        ],
        'section2' => [
            'title' => '2. Acceptation des CGU',
            'item1' => [
                'label' => 'Acceptation',
                'text' => 'Toute utilisation du service implique l\'acceptation sans réserve des présentes CGU.'
            ],
            'item2' => [
                'label' => 'Modification',
                'text' => 'Les CGU peuvent être modifiées à tout moment, les utilisateurs sont invités à les consulter régulièrement.'
            ],
            'item3' => [
                'label' => 'Validité',
                'text' => 'Les CGU sont applicables pendant toute la durée d\'utilisation du service.'
            ],
            'item4' => [
                'label' => 'Juridiction',
                'text' => 'En cas de litige, les tribunaux marocains sont seuls compétents.'
            ]
        ],
        'section3' => [
            'title' => '3. Description des services',
            'paragraph1' => 'Le site efacture-maroc.com propose un service de facturation électronique conforme à la législation marocaine.',
            'paragraph2' => 'Les services incluent la création, l\'envoi et le suivi des factures électroniques.'
        ],
        'section4' => [
            'title' => '4. Obligations de l\'utilisateur',
            'intro' => 'L\'utilisateur s\'engage à :',
            'item1' => 'Fournir des informations exactes et à jour',
            'item2' => 'Respecter la législation en vigueur au Maroc',
            'item3' => 'Ne pas utiliser le service à des fins illégales',
            'item4' => 'Conserver la confidentialité de ses identifiants'
        ],
        'section5' => [
            'title' => '5. Responsabilités',
            'paragraph1' => 'efacture-maroc.com ne peut être tenu responsable des erreurs commises par l\'utilisateur dans la saisie des informations.',
            'paragraph2' => 'La responsabilité de efacture-maroc.com ne saurait être engagée en cas de force majeure.'
        ],
        'section6' => [
            'title' => '6. Propriété intellectuelle',
            'intro' => 'Tous les éléments du site sont protégés par le droit d\'auteur :',
            'item1' => 'Les textes, images, graphismes',
            'item2' => 'Les logiciels et bases de données',
            'conclusion' => 'Toute reproduction sans autorisation est interdite.'
        ],
        'section7' => [
            'title' => '7. Données personnelles',
            'paragraph1' => 'Les données collectées sont traitées conformément à la loi 09-08 relative à la protection des personnes physiques à l\'égard du traitement des données à caractère personnel.',
            'paragraph2' => 'L\'utilisateur dispose d\'un droit d\'accès, de rectification et d\'opposition.'
        ],
        'section8' => [
            'title' => '8. Tarifs et paiement',
            'paragraph1' => 'Les tarifs des services sont disponibles sur le site et peuvent être modifiés à tout moment.',
            'paragraph2' => 'Le paiement s\'effectue par virement bancaire ou carte de crédit.'
        ],
        'section9' => [
            'title' => '9. Résiliation',
            'content' => 'L\'utilisateur peut résilier son compte à tout moment. La résiliation prend effet immédiatement.'
        ],
        'contact' => [
            'title' => 'Contact',
            'email' => 'contact@efacture-maroc.com',
            'phone' => '+212 5 22 22 22 22',
            'address' => '123 Avenue Mohammed V, Casablanca, Maroc'
        ]
    ]
];