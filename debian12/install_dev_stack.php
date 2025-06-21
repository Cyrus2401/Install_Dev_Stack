<?php
/**
 * Script d'installation automatique pour stack de développement Debian 12
 * 
 * Installe : Apache, MySQL, PostgreSQL, PHP, Composer, Adminer, VS Code, 
 * Google Chrome, GParted, Node.js, npm, curl, git
 * 
 * @author Assistant Claude
 * @version 1.0
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
     * Installe les paquets de base via apt
     */
    private function installBasePackages() {
        $this->printStatus("Installation des paquets de base...");
        
        // Liste des paquets à installer
        $packages = [
            'apache2',
            'mysql-server',
            'postgresql',
            'postgresql-contrib',
            'php',
            'php-apache2handler',
            'php-mysql',
            'php-pgsql',
            'php-gd',
            'php-curl',
            'php-zip',
            'php-xml',
            'php-mbstring',
            'php-json',
            'php-intl',
            'php-bcmath',
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
     * Installe Visual Studio Code
     */
    private function installVSCode() {
        $this->printStatus("Installation de VS Code...");
        
        // Ajout de la clé GPG Microsoft
        $result = $this->executeCommand("wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /tmp/packages.microsoft.gpg");
        if ($result !== 0) {
            $this->printError("Erreur lors du téléchargement de la clé GPG Microsoft");
            return;
        }
        
        // Installation de la clé
        $this->executeCommand("sudo install -o root -g root -m 644 /tmp/packages.microsoft.gpg /etc/apt/trusted.gpg.d/");
        
        // Ajout du repository
        $repoLine = 'deb [arch=amd64,arm64,armhf signed-by=/etc/apt/trusted.gpg.d/packages.microsoft.gpg] https://packages.microsoft.com/repos/code stable main';
        $this->executeCommand("echo '$repoLine' | sudo tee /etc/apt/sources.list.d/vscode.list > /dev/null");
        
        // Mise à jour et installation
        $this->executeCommand("sudo apt update");
        $result = $this->executeCommand("sudo apt install -y code");
        
        // Nettoyage
        $this->executeCommand("rm -f /tmp/packages.microsoft.gpg");
        
        if ($result === 0) {
            $this->printSuccess("VS Code installé");
        } else {
            $this->printError("Erreur lors de l'installation de VS Code");
        }
    }
    
    /**
     * Installe Google Chrome
     */
    private function installChrome() {
        $this->printStatus("Installation de Google Chrome...");
        
        // Téléchargement du paquet .deb
        $result = $this->executeCommand("wget -q -O /tmp/google-chrome-stable_current_amd64.deb https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb");
        if ($result !== 0) {
            $this->printError("Erreur lors du téléchargement de Google Chrome");
            return;
        }
        
        // Installation du paquet
        $this->executeCommand("sudo dpkg -i /tmp/google-chrome-stable_current_amd64.deb");
        
        // Correction des dépendances si nécessaire
        $this->executeCommand("sudo apt-get install -f -y");
        
        // Nettoyage
        $this->executeCommand("rm -f /tmp/google-chrome-stable_current_amd64.deb");
        
        $this->printSuccess("Google Chrome installé");
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
     * Démarre et active tous les services nécessaires
     */
    private function startServices() {
        $this->printStatus("Démarrage des services...");
        
        // Liste des services à démarrer et activer
        $services = ['apache2', 'mysql', 'postgresql'];
        
        foreach ($services as $service) {
            $this->executeCommand("sudo systemctl enable $service");
            $this->executeCommand("sudo systemctl start $service");
        }
        
        // Redémarrage d'Apache pour prendre en compte PHP et Adminer
        $this->executeCommand("sudo systemctl reload apache2");
        
        $this->printSuccess("Services démarrés");
    }
    
    /**
     * Exécute mysql_secure_installation de manière interactive
     */
    private function secureMySQL() {
        $this->printStatus("Sécurisation de MySQL...");
        $this->printWarning("Vous allez être invité à configurer la sécurité de MySQL.");
        $this->printWarning("Répondez aux questions selon vos préférences de sécurité.");
        
        // Attendre que MySQL soit complètement démarré
        sleep(3);
        
        // Exécution interactive de mysql_secure_installation
        system("sudo mysql_secure_installation");
        
        $this->printSuccess("MySQL sécurisé");
    }
    
    /**
     * Affiche le résumé final de l'installation
     */
    private function showSummary() {
        $this->printSuccess("Installation terminée !");
        echo "\n";
        
        echo "=== RÉSUMÉ DES INSTALLATIONS ===\n";
        echo "✓ Apache 2 - http://localhost\n";
        echo "✓ MySQL (sécurisé)\n";
        echo "✓ PostgreSQL\n";
        echo "✓ PHP avec extensions\n";
        echo "✓ Composer\n";
        echo "✓ Adminer - http://localhost/adminer\n";
        echo "✓ VS Code\n";
        echo "✓ Google Chrome\n";
        echo "✓ GParted\n";
        echo "✓ Node.js et npm\n";
        echo "✓ Git\n";
        echo "✓ curl\n";
        echo "\n";
        
        echo "=== VERSIONS INSTALLÉES ===\n";
        $this->executeCommand("apache2 -v | head -n1");
        $this->executeCommand("mysql --version");
        $this->executeCommand("psql --version");
        $this->executeCommand("php -v | head -n1");
        $this->executeCommand("composer --version");
        $this->executeCommand("node --version");
        $this->executeCommand("npm --version");
        $this->executeCommand("git --version");
        echo "\n";
        
        echo "=== ACCÈS AUX SERVICES ===\n";
        echo "Apache: http://localhost\n";
        echo "Adminer: http://localhost/adminer\n";
        echo "MySQL: Utilisez les identifiants configurés lors de mysql_secure_installation\n";
        echo "PostgreSQL: Utilisez 'sudo -u postgres psql' pour accéder en tant que postgres\n";
    }
    
    /**
     * Méthode principale qui orchestrent toute l'installation
     */
    public function run() {
        echo "=== INSTALLATION STACK DE DÉVELOPPEMENT DEBIAN 12 ===\n";
        echo "\n";
        
        try {
            // Vérifications préalables
            $this->checkSudo();
            
            // Installation étape par étape
            $this->updateSystem();
            $this->installBasePackages();
            $this->installVSCode();
            $this->installChrome();
            $this->installComposer();
            $this->installAdminer();
            $this->startServices();
            $this->secureMySQL();
            
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