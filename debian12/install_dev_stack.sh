#!/bin/bash

# Script d'installation automatique pour stack de développement Debian 12
# Apache, MySQL, PostgreSQL, PHP, Composer, Adminer, VS Code, Chrome, etc.

set -e  # Arrêter le script en cas d'erreur

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher des messages colorés
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Vérifier les droits sudo
check_sudo() {
    if ! sudo -n true 2>/dev/null; then
        print_error "Ce script nécessite les droits sudo. Veuillez l'exécuter avec sudo ou être dans le groupe sudoers."
        exit 1
    fi
}

# Mise à jour du système
update_system() {
    print_status "Mise à jour du système..."
    sudo apt update && sudo apt upgrade -y
    print_success "Système mis à jour"
}

# Installation des paquets de base via apt
install_base_packages() {
    print_status "Installation des paquets de base..."
    sudo apt install -y \
        apache2 \
        mysql-server \
        postgresql \
        postgresql-contrib \
        php \
        php-apache2handler \
        php-mysql \
        php-pgsql \
        php-gd \
        php-curl \
        php-zip \
        php-xml \
        php-mbstring \
        php-json \
        php-intl \
        php-bcmath \
        gparted \
        nodejs \
        npm \
        curl \
        git \
        wget \
        software-properties-common \
        apt-transport-https \
        ca-certificates \
        gnupg \
        lsb-release
    print_success "Paquets de base installés"
}

# Installation de VS Code
install_vscode() {
    print_status "Installation de VS Code..."
    
    # Ajout de la clé GPG Microsoft
    wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /tmp/packages.microsoft.gpg
    sudo install -o root -g root -m 644 /tmp/packages.microsoft.gpg /etc/apt/trusted.gpg.d/
    
    # Ajout du repository
    echo "deb [arch=amd64,arm64,armhf signed-by=/etc/apt/trusted.gpg.d/packages.microsoft.gpg] https://packages.microsoft.com/repos/code stable main" | sudo tee /etc/apt/sources.list.d/vscode.list > /dev/null
    
    # Installation
    sudo apt update
    sudo apt install -y code
    
    # Nettoyage
    rm -f /tmp/packages.microsoft.gpg
    
    print_success "VS Code installé"
}

# Installation de Google Chrome
install_chrome() {
    print_status "Installation de Google Chrome..."
    
    # Téléchargement et installation du paquet .deb
    wget -q -O /tmp/google-chrome-stable_current_amd64.deb https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
    sudo dpkg -i /tmp/google-chrome-stable_current_amd64.deb
    
    # Correction des dépendances si nécessaire
    sudo apt-get install -f -y
    
    # Nettoyage
    rm -f /tmp/google-chrome-stable_current_amd64.deb
    
    print_success "Google Chrome installé"
}

# Installation de Composer
install_composer() {
    print_status "Installation de Composer..."
    
    # Téléchargement et installation
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    
    print_success "Composer installé"
}

# Installation d'Adminer
install_adminer() {
    print_status "Installation d'Adminer..."
    
    # Création du répertoire
    sudo mkdir -p /usr/share/adminer
    
    # Téléchargement d'Adminer
    sudo wget "https://www.adminer.org/latest.php" -O /usr/share/adminer/latest.php
    sudo ln -sf /usr/share/adminer/latest.php /usr/share/adminer/adminer.php
    
    # Configuration Apache pour Adminer
    sudo tee /etc/apache2/conf-available/adminer.conf > /dev/null <<EOF
Alias /adminer /usr/share/adminer

<Directory /usr/share/adminer>
    Options FollowSymLinks
    DirectoryIndex adminer.php
    Require all granted
</Directory>
EOF
    
    sudo a2enconf adminer
    
    print_success "Adminer installé (accessible via http://localhost/adminer)"
}

# Démarrage des services
start_services() {
    print_status "Démarrage des services..."
    
    # Démarrage et activation des services
    sudo systemctl enable apache2
    sudo systemctl start apache2
    
    sudo systemctl enable mysql
    sudo systemctl start mysql
    
    sudo systemctl enable postgresql
    sudo systemctl start postgresql
    
    # Redémarrage d'Apache pour prendre en compte PHP et Adminer
    sudo systemctl reload apache2
    
    print_success "Services démarrés"
}

# Exécution de mysql_secure_installation
secure_mysql() {
    print_status "Sécurisation de MySQL..."
    print_warning "Vous allez être invité à configurer la sécurité de MySQL."
    print_warning "Répondez aux questions selon vos préférences de sécurité."
    
    # Attendre que MySQL soit complètement démarré
    sleep 3
    
    # Exécution de mysql_secure_installation
    sudo mysql_secure_installation
    
    print_success "MySQL sécurisé"
}

# Affichage du résumé final
show_summary() {
    print_success "Installation terminée !"
    echo ""
    echo "=== RÉSUMÉ DES INSTALLATIONS ==="
    echo "✓ Apache 2 - http://localhost"
    echo "✓ MySQL (sécurisé)"
    echo "✓ PostgreSQL"
    echo "✓ PHP avec extensions"
    echo "✓ Composer"
    echo "✓ Adminer - http://localhost/adminer"
    echo "✓ VS Code"
    echo "✓ Google Chrome"
    echo "✓ GParted"
    echo "✓ Node.js et npm"
    echo "✓ Git"
    echo "✓ curl"
    echo ""
    echo "=== VERSIONS INSTALLÉES ==="
    echo "Apache: $(apache2 -v | head -n1)"
    echo "MySQL: $(mysql --version)"
    echo "PostgreSQL: $(psql --version)"
    echo "PHP: $(php -v | head -n1)"
    echo "Composer: $(composer --version)"
    echo "Node.js: $(node --version)"
    echo "npm: $(npm --version)"
    echo "Git: $(git --version)"
    echo ""
    echo "=== ACCÈS AUX SERVICES ==="
    echo "Apache: http://localhost"
    echo "Adminer: http://localhost/adminer"
    echo "MySQL: Utilisez les identifiants configurés lors de mysql_secure_installation"
    echo "PostgreSQL: Utilisez 'sudo -u postgres psql' pour accéder en tant que postgres"
}

# Fonction principale
main() {
    echo "=== INSTALLATION STACK DE DÉVELOPPEMENT DEBIAN 12 ==="
    echo ""
    
    check_sudo
    update_system
    install_base_packages
    install_vscode
    install_chrome
    install_composer
    install_adminer
    start_services
    secure_mysql
    show_summary
    
    print_success "Script terminé avec succès !"
}

# Exécution du script principal
main "$@"