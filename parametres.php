<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$lang = $_SESSION['lang'] ?? 'fr';
$translations = require __DIR__ . "/lang/$lang.php";

$success_message = '';
$error_message = '';

if (isset($_POST['change_language'])) {
    $_SESSION['lang'] = $_POST['language'];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('settings.title') ?> - efacture-maroc.com</title>
    
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .form-input:focus {
            border-color: #006233;
            box-shadow: 0 0 0 3px rgba(0, 98, 51, 0.1);
        }
        
        .tab-container {
            perspective: 1000px;
        }
        
        .tab-content {
            transform-style: preserve-3d;
            transition: transform 0.5s ease;
        }
        
        .tab-active {
            transform: translateZ(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            position: relative;
            z-index: 10;
        }
        
        .tab-inactive {
            transform: translateZ(0);
            opacity: 0.7;
            filter: blur(1px);
        }
        
        .tab-button {
            transition: all 0.3s ease;
            position: relative;
            z-index: 5;
            color: white !important;
        }
        
        .tab-button:hover {
            transform: translateY(-3px);
        }
        
        .tab-button-active {
            background: linear-gradient(145deg, #006233, #004d29);
            color: white !important;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 98, 51, 0.3);
            z-index: 20;
        }
        
        .card-3d {
            transform: translateZ(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-3d:hover {
            transform: translateZ(20px) translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #006233, #0a9c5e, #006233);
            border-radius: 15px 15px 0 0;
            opacity: 0.8;
        }
        
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke='%239fa6b2' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <?php if ($success_message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= __($success_message) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?= __($error_message) ?>
                </div>
            <?php endif; ?>
            
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-cog text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('settings.title') ?></h1>
            </div>
            
            <div class="tab-container mb-8">
                <div class="flex flex-wrap gap-2 justify-center">
                    <button 
                        data-tab="general" 
                        class="tab-button tab-button-active px-6 py-3 border border-gray-200 rounded-t-lg font-medium bg-primary shadow-sm"
                    >
                        <i class="fas fa-cog mr-2"></i><?= __('settings.tabs.general') ?>
                    </button>
                    
                    <button 
                        data-tab="profile" 
                        class="tab-button px-6 py-3 border border-gray-200 rounded-t-lg font-medium bg-gray-600 hover:bg-gray-700 shadow-sm"
                    >
                        <i class="fas fa-user mr-2"></i><?= __('settings.tabs.profile') ?>
                    </button>
                    
                    <button 
                        data-tab="about" 
                        class="tab-button px-6 py-3 border border-gray-200 rounded-t-lg font-medium bg-gray-600 hover:bg-gray-700 shadow-sm"
                    >
                        <i class="fas fa-info-circle mr-2"></i><?= __('settings.tabs.about') ?>
                    </button>
                </div>
                
                <div class="bg-white rounded-b-lg rounded-tr-lg shadow-xl overflow-hidden">
                    <div id="general-tab" class="tab-content tab-active p-6">
                        <div class="glow"></div>
                        <h2 class="text-xl font-bold text-gray-800 mb-6"><?= __('settings.tabs.general') ?></h2>
                        
                        <div class="card-3d bg-gray-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-language text-primary mr-2"></i><?= __('language.title') ?>
                            </h3>
                            <form method="post" class="flex items-center gap-4">
                                <select name="language" class="form-select block w-full rounded-md border-gray-300 py-2 px-3">
                                    <option value="fr" <?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'selected' : '' ?>><?= __('language.options.fr') ?></option>
                                    <option value="ar" <?= ($_SESSION['lang'] ?? 'fr') === 'ar' ? 'selected' : '' ?>><?= __('language.options.ar') ?></option>
                                </select>
                                <button 
                                    type="submit" 
                                    name="change_language"
                                    class="bg-primary hover:bg-green-800 text-white py-2 px-4 rounded-md font-medium whitespace-nowrap"
                                >
                                    <i class="fas fa-sync-alt mr-2"></i><?= __('language.button') ?>
                                </button>
                            </form>
                        </div>
                        
                        <div class="card-3d bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-bell text-primary mr-2"></i><?= __('notifications.title') ?>
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="text-gray-700"><?= __('notifications.email') ?></label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <label class="text-gray-700"><?= __('notifications.deadlines') ?></label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <label class="text-gray-700"><?= __('notifications.payments') ?></label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="profile-tab" class="tab-content tab-inactive p-6 hidden">
                        <div class="glow"></div>
                        <h2 class="text-xl font-bold text-gray-800 mb-6"><?= __('profile.title') ?></h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-8">
                            <div class="card-3d bg-white p-6 rounded-lg">
                                <div class="flex items-center mb-6">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-full mr-4">
                                        <i class="fas fa-user-circle text-primary text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg"><?= htmlspecialchars($user_data['email']) ?></h3>
                                        <p class="text-gray-600"><?= __('profile.subtitle') ?></p>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <?= __('profile.fullname') ?>
                                        </label>
                                        <div class="form-input block w-full rounded-md border-gray-300 py-2 px-3 bg-gray-100">
                                            <?= htmlspecialchars($user_data['nom'] ?? '') ?>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <?= __('profile.email') ?>
                                        </label>
                                        <div class="form-input block w-full rounded-md border-gray-300 py-2 px-3 bg-gray-100">
                                            <?= htmlspecialchars($user_data['email'] ?? '') ?>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <?= __('profile.phone') ?>
                                        </label>
                                        <div class="form-input block w-full rounded-md border-gray-300 py-2 px-3 bg-gray-100">
                                            <?= htmlspecialchars($user_data['telephone'] ?? '') ?>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Adresse
                                        </label>
                                        <div class="form-input block w-full rounded-md border-gray-300 py-2 px-3 bg-gray-100">
                                            <?= htmlspecialchars($user_data['adresse'] ?? '') ?>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <?= __('profile.company') ?>
                                        </label>
                                        <div class="form-input block w-full rounded-md border-gray-300 py-2 px-3 bg-gray-100">
                                            <?= htmlspecialchars($user_data['entreprise'] ?? '') ?>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-4">
                                        <a 
                                            href="info_edit.php" 
                                            class="w-full inline-block text-center bg-primary hover:bg-green-800 text-white py-2 px-4 rounded-md font-medium"
                                        >
                                            <i class="fas fa-edit mr-2"></i><?= __('profile.edit_button') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="about-tab" class="tab-content tab-inactive p-6 hidden">
                        <div class="glow"></div>
                        <div class="flex flex-col items-center mb-8">
                            <div class="w-24 h-24 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-info-circle text-primary text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800"><?= __('about.title') ?></h2>
                            <p class="text-gray-600 mt-2 text-center"><?= __('about.subtitle') ?></p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="card-3d bg-white p-6 rounded-lg">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-code-branch text-primary text-2xl"></i>
                                    </div>
                                    <h3 class="font-bold text-lg mb-2"><?= __('about.version') ?></h3>
                                    <p class="text-gray-700">2.1.0</p>
                                </div>
                            </div>
                            
                            <div class="card-3d bg-white p-6 rounded-lg">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-headset text-primary text-2xl"></i>
                                    </div>
                                    <h3 class="font-bold text-lg mb-2"><?= __('about.support') ?></h3>
                                    <p class="text-gray-700">support@efacture-maroc.com</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 text-center text-gray-500 text-sm">
                            <p>© <?= date('Y') ?> efacture-maroc.com - <?= __('settings.title') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    
    <script>
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('tab-button-active');
                    btn.classList.add('bg-gray-600');
                    btn.classList.remove('bg-primary');
                });
                button.classList.add('tab-button-active');
                button.classList.remove('bg-gray-600');
                button.classList.add('bg-primary');
                
                const tabId = button.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('tab-active');
                    content.classList.add('tab-inactive');
                    content.classList.add('hidden');
                });
                
                const activeTab = document.getElementById(`${tabId}-tab`);
                activeTab.classList.remove('tab-inactive');
                activeTab.classList.remove('hidden');
                activeTab.classList.add('tab-active');
            });
        });
        
        document.querySelectorAll('.card-3d').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateZ(20px) translateY(-5px)';
                card.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.2)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateZ(10px)';
                card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            
            if (tab) {
                const tabButton = document.querySelector(`.tab-button[data-tab="${tab}"]`);
                if (tabButton) {
                    tabButton.click();
                }
            }
        });
    </script>
</body>
</html>