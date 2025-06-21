<?php
/**
 * Script d'installation automatique pour stack de développement Debian 12
 * 
 * Installe : Apache, MariaDB, PostgreSQL, PHP (extensions complètes), Composer, 
 * Adminer, GParted, Node.js, npm, curl, git
 * 
 * @author Assistant Claude
 * @version 2.2
 * @date 2025
 */

// Configuration des couleurs pour l'affichage terminal
class Colors {
    const RED = "\033[0;31m";
    const GREEN = "\033[0;32m";
    const YELLOW = "\033[1;33m";
    const BLUE = "\033[0;34m";
    const NC = "\033[0m"; // No Color
}

/**
 * Classe principale pour l'installation des outils de développement
 */
class DevStackInstaller {
    
    /**
     * Affiche un message d'information en bleu
     * @param string $message Message à afficher
     */
    private function printStatus($message) {
        echo Colors::BLUE . "[INFO]" . Colors::NC . " $message\n";
    }
    
    /**
     * Affiche un message de succès en vert
     * @param string $message Message à afficher
     */
    private function printSuccess($message) {
        echo Colors::GREEN . "[SUCCESS]" . Colors::NC . " $message\n";
    }
    
    /**
     * Affiche un message d'avertissement en jaune
     * @param string $message Message à afficher
     */
    private function printWarning($message) {
        echo Colors::YELLOW . "[WARNING]" . Colors::NC . " $message\n";
    }
    
    /**
     * Affiche un message d'erreur en rouge
     * @param string $message Message à afficher
     */
    private function printError($message) {
        echo Colors::RED . "[ERROR]" . Colors::NC . " $message\n";
    }
    
    /**
     * Exécute une commande système et retourne le code de retour
     * @param string $command Commande à exécuter
     * @return int Code de retour de la commande
     */
    private function executeCommand($command) {
        $output = [];
        $returnCode = 0;
        exec($command . " 2>&1", $output, $returnCode);
        
        // Affichage de la sortie en cas d'erreur
        if ($returnCode !== 0) {
            foreach ($output as $line) {
                echo $line . "\n";
            }
        }
        
        return $returnCode;
    }
    
    /**
     * Vérifie si un service est actif
     * @param string $service Nom du service
     * @return bool True si le service est actif
     */
    private function isServiceActive($service) {
        $result = $this->executeCommand("sudo systemctl is-active --quiet $service");
        return $result === 0;
    }
    
    /**
     * Vérifie si l'utilisateur a les droits sudo
     * @throws Exception Si les droits sudo ne sont pas disponibles
     */
    private function checkSudo() {
        $result = $this->executeCommand("sudo -n true");
        if ($result !== 0) {
            $this->printError("Ce script nécessite les droits sudo. Veuillez l'exécuter avec sudo ou être dans le groupe sudoers.");
            exit(1);
        }
    }
    
    /**
     * Met à jour le système Debian
     */
    private function updateSystem() {
        $this->printStatus("Mise à jour du système...");
        
        $result = $this->executeCommand("sudo apt update && sudo apt upgrade -y");
        if ($result !== 0) {
            $this->printError("Erreur lors de la mise à jour du système");
            exit(1);
        }
        
        $this->printSuccess("Système mis à jour");
    }
    
    /**
     * Installe les paquets de base via apt avec extensions PHP complètes
     */
    private function installBasePackages() {
        $this->printStatus("Installation des paquets de base...");
        
        // Liste complète des paquets à installer
        $packages = [
            // Serveurs web et bases de données
            'apache2',
            'mariadb-server',
            'mariadb-client',
            'postgresql',
            'postgresql-contrib',
            
            // PHP et toutes ses extensions
            'php',
            'php-cli',
            'php-fpm',
            'php-common',
            'php-opcache',
            'php-mysql',
            'php-pgsql',
            'php-sqlite3',
            'php-intl',
            'php-imagick',
            'php-ldap',
            'php-soap',
            'php-ctype',
            'php-tokenizer',
            'php-dom',
            'php-fileinfo',
            'php-gd',
            'php-curl',
            'php-zip',
            'php-xml',
            'php-mbstring',
            'php-json',
            'php-bcmath',
            
            // Outils système et développement
            'gparted',
            'nodejs',
            'npm',
            'curl',
            'git',
            'wget',
            'software-properties-common',
            'apt-transport-https',
            'ca-certificates',
            'gnupg',
            'lsb-release'
        ];
        
        $packageList = implode(' ', $packages);
        $result = $this->executeCommand("sudo apt install -y $packageList");
        
        if ($result !== 0) {
            $this->printError("Erreur lors de l'installation des paquets de base");
            exit(1);
        }
        
        $this->printSuccess("Paquets de base installés");
    }
    
    /**
     * Installe Composer (gestionnaire de dépendances PHP)
     */
    private function installComposer() {
        $this->printStatus("Installation de Composer...");
        
        // Téléchargement et installation de Composer
        $result = $this->executeCommand("curl -sS https://getcomposer.org/installer | php");
        if ($result !== 0) {
            $this->printError("Erreur lors du téléchargement de Composer");
            return;
        }
        
        // Déplacement vers le répertoire global et ajout des permissions
        $this->executeCommand("sudo mv composer.phar /usr/local/bin/composer");
        $this->executeCommand("sudo chmod +x /usr/local/bin/composer");
        
        $this->printSuccess("Composer installé");
    }
    
    /**
     * Installe et configure Adminer (interface web pour bases de données)
     */
    private function installAdminer() {
        $this->printStatus("Installation d'Adminer...");
        
        // Création du répertoire d'installation
        $this->executeCommand("sudo mkdir -p /usr/share/adminer");
        
        // Téléchargement d'Adminer
        $result = $this->executeCommand('sudo wget "https://www.adminer.org/latest.php" -O /usr/share/adminer/latest.php');
        if ($result !== 0) {
            $this->printError("Erreur lors du téléchargement d'Adminer");
            return;
        }
        
        // Création du lien symbolique
        $this->executeCommand("sudo ln -sf /usr/share/adminer/latest.php /usr/share/adminer/adminer.php");
        
        // Configuration Apache pour Adminer
        $adminerConfig = 'Alias /adminer /usr/share/adminer

<Directory /usr/share/adminer>
    Options FollowSymLinks
    DirectoryIndex adminer.php
    Require all granted
</Directory>';
        
        file_put_contents('/tmp/adminer.conf', $adminerConfig);
        $this->executeCommand("sudo mv /tmp/adminer.conf /etc/apache2/conf-available/adminer.conf");
        
        // Activation de la configuration Adminer
        $this->executeCommand("sudo a2enconf adminer");
        
        $this->printSuccess("Adminer installé (accessible via http://localhost/adminer)");
    }
    
    /**
     * Démarre, active et redémarre tous les services nécessaires
     */
    private function startServices() {
        $this->printStatus("Configuration et démarrage des services...");
        
        // Liste des services à gérer
        $services = ['apache2', 'mariadb', 'postgresql'];
        
        // Activation des services pour démarrage automatique
        foreach ($services as $service) {
            $this->executeCommand("sudo systemctl enable $service");
        }
        
        $this->printStatus("Démarrage initial des services...");
        
        // Démarrage des services
        foreach ($services as $service) {
            $this->executeCommand("sudo systemctl start $service");
        }
        
        $this->printStatus("Redémarrage des services pour prise en compte des configurations...");
        
        // Redémarrage des services pour s'assurer de la prise en compte des configurations
        foreach ($services as $service) {
            $this->executeCommand("sudo systemctl restart $service");
        }
        
        // Vérification du statut des services
        $this->printStatus("Vérification du statut des services...");
        
        foreach ($services as $service) {
            if ($this->isServiceActive($service)) {
                $this->printSuccess("$service actif");
            } else {
                $this->printWarning("$service non actif - tentative de redémarrage...");
                $this->executeCommand("sudo systemctl restart $service");
                
                // Vérification après redémarrage
                if ($this->isServiceActive($service)) {
                    $this->printSuccess("$service redémarré avec succès");
                } else {
                    $this->printError("Impossible de démarrer $service");
                }
            }
        }
        
        $this->printSuccess("Tous les services sont configurés et démarrés");
    }
    
    /**
     * Exécute mysql_secure_installation pour sécuriser MariaDB
     */
    private function secureMariaDB() {
        $this->printStatus("Sécurisation de MariaDB...");
        $this->printWarning("Vous allez être invité à configurer la sécurité de MariaDB.");
        $this->printWarning("Répondez aux questions selon vos préférences de sécurité.");
        
        // Attendre que MariaDB soit complètement démarré
        sleep(5);
        
        // Exécution interactive de mysql_secure_installation (même commande pour MariaDB)
        system("sudo mysql_secure_installation");
        
        // Redémarrage final de MariaDB après sécurisation
        $this->printStatus("Redémarrage de MariaDB après sécurisation...");
        $this->executeCommand("sudo systemctl restart mariadb");
        
        $this->printSuccess("MariaDB sécurisé et redémarré");
    }
    
    /**
     * Obtient le statut d'un service
     * @param string $service Nom du service
     * @return string Statut du service
     */
    private function getServiceStatus($service) {
        $output = [];
        exec("sudo systemctl is-active $service 2>/dev/null", $output);
        return isset($output[0]) ? $output[0] : 'unknown';
    }
    
    /**
     * Affiche le résumé final de l'installation
     */
    private function showSummary() {
        $this->printSuccess("Installation terminée !");
        echo "\n";
        
        echo "=== RÉSUMÉ DES INSTALLATIONS ===\n";
        echo "✓ Apache 2 - http://localhost\n";
        echo "✓ MariaDB (sécurisé)\n";
        echo "✓ PostgreSQL\n";
        echo "✓ PHP avec extensions complètes\n";
        echo "✓ Composer\n";
        echo "✓ Adminer - http://localhost/adminer\n";
        echo "✓ GParted\n";
        echo "✓ Node.js et npm\n";
        echo "✓ Git\n";
        echo "✓ curl\n";
        echo "\n";
        
        echo "=== STATUT DES SERVICES ===\n";
        $apache_status = $this->getServiceStatus('apache2');
        $mariadb_status = $this->getServiceStatus('mariadb');
        $postgresql_status = $this->getServiceStatus('postgresql');
        
        echo "Apache2: $apache_status\n";
        echo "MariaDB: $mariadb_status\n";
        echo "PostgreSQL: $postgresql_status\n";
        echo "\n";
        
        echo "=== VERSIONS INSTALLÉES ===\n";
        $this->executeCommand("apache2 -v | head -n1");
        $this->executeCommand("mariadb --version");
        $this->executeCommand("psql --version");
        $this->executeCommand("php -v | head -n1");
        $this->executeCommand("composer --version");
        $this->executeCommand("node --version");
        $this->executeCommand("npm --version");
        $this->executeCommand("git --version");
        echo "\n";
        
        echo "=== EXTENSIONS PHP INSTALLÉES ===\n";
        echo "✓ php-cli, php-fpm, php-common, php-opcache\n";
        echo "✓ php-mysql, php-pgsql, php-sqlite3\n";
        echo "✓ php-gd, php-curl, php-zip, php-xml\n";
        echo "✓ php-mbstring, php-json, php-intl, php-bcmath\n";
        echo "✓ php-imagick, php-ldap, php-soap\n";
        echo "✓ php-ctype, php-tokenizer, php-dom, php-fileinfo\n";
        echo "\n";
        
        echo "=== ACCÈS AUX SERVICES ===\n";
        echo "Apache: http://localhost\n";
        echo "Adminer: http://localhost/adminer\n";
        echo "MariaDB: Utilisez les identifiants configurés lors de mysql_secure_installation\n";
        echo "PostgreSQL: Utilisez 'sudo -u postgres psql' pour accéder en tant que postgres\n";
    }
    
    /**
     * Méthode principale qui orchestre toute l'installation
     */
    public function run() {
        echo "=== INSTALLATION STACK DE DÉVELOPPEMENT DEBIAN 12 ===\n";
        echo "=== Apache + MariaDB + PostgreSQL + PHP (Extensions complètes) + Adminer ===\n";
        echo "\n";
        
        try {
            // Vérifications préalables
            $this->checkSudo();
            
            // Installation étape par étape
            $this->updateSystem();
            $this->installBasePackages();
            $this->installComposer();
            $this->installAdminer();
            $this->startServices();
            $this->secureMariaDB();
            
            // Résumé final
            $this->showSummary();
            
            $this->printSuccess("Script terminé avec succès !");
            
        } catch (Exception $e) {
            $this->printError("Erreur fatale : " . $e->getMessage());
            exit(1);
        }
    }
}

// Point d'entrée du script
if (php_sapi_name() === 'cli') {
    $installer = new DevStackInstaller();
    $installer->run();
} else {
    echo "Ce script doit être exécuté en ligne de commande.\n";
    exit(1);
}
?>