#!/bin/bash

# Script d'installation automatique pour stack de développement Debian 12
# Apache, MariaDB, PostgreSQL, PHP, Composer, Adminer, etc.

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
        mariadb-server \
        mariadb-client \
        postgresql \
        postgresql-contrib \
        php \
        php-cli \
        php-fpm \
        php-common \
        php-opcache \
        php-mysql \
        php-pgsql \
        php-sqlite3 \
        php-intl \
        php-imagick \
        php-ldap \
        php-soap \
        php-ctype \
        php-tokenizer \
        php-dom \
        php-fileinfo \
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

# Démarrage et redémarrage des services
start_services() {
    print_status "Configuration et démarrage des services..."
    
    # Activation des services pour démarrage automatique
    sudo systemctl enable apache2
    sudo systemctl enable mariadb
    sudo systemctl enable postgresql
    
    print_status "Démarrage initial des services..."
    
    # Démarrage des services
    sudo systemctl start apache2
    sudo systemctl start mariadb
    sudo systemctl start postgresql
    
    print_status "Redémarrage des services pour prise en compte des configurations..."
    
    # Redémarrage des services pour s'assurer de la prise en compte des configurations
    sudo systemctl restart mariadb
    sudo systemctl restart postgresql
    sudo systemctl restart apache2
    
    # Vérification du statut des services
    print_status "Vérification du statut des services..."
    
    if sudo systemctl is-active --quiet apache2; then
        print_success "Apache2 actif"
    else
        print_warning "Apache2 non actif - tentative de redémarrage..."
        sudo systemctl restart apache2
    fi
    
    if sudo systemctl is-active --quiet mariadb; then
        print_success "MariaDB actif"
    else
        print_warning "MariaDB non actif - tentative de redémarrage..."
        sudo systemctl restart mariadb
    fi
    
    if sudo systemctl is-active --quiet postgresql; then
        print_success "PostgreSQL actif"
    else
        print_warning "PostgreSQL non actif - tentative de redémarrage..."
        sudo systemctl restart postgresql
    fi
    
    print_success "Tous les services sont configurés et démarrés"
}

# Exécution de mysql_secure_installation pour MariaDB
secure_mariadb() {
    print_status "Sécurisation de MariaDB..."
    print_warning "Vous allez être invité à configurer la sécurité de MariaDB."
    print_warning "Répondez aux questions selon vos préférences de sécurité."
    
    # Attendre que MariaDB soit complètement démarré
    sleep 5
    
    # Exécution de mysql_secure_installation (même commande pour MariaDB)
    sudo mysql_secure_installation
    
    # Redémarrage final de MariaDB après sécurisation
    print_status "Redémarrage de MariaDB après sécurisation..."
    sudo systemctl restart mariadb
    
    print_success "MariaDB sécurisé et redémarré"
}

# Affichage du résumé final
show_summary() {
    print_success "Installation terminée !"
    echo ""
    echo "=== RÉSUMÉ DES INSTALLATIONS ==="
    echo "✓ Apache 2 - http://localhost"
    echo "✓ MariaDB (sécurisé)"
    echo "✓ PostgreSQL"
    echo "✓ PHP avec extensions complètes"
    echo "✓ Composer"
    echo "✓ Adminer - http://localhost/adminer"
    echo "✓ GParted"
    echo "✓ Node.js et npm"
    echo "✓ Git"
    echo "✓ curl"
    echo ""
    echo "=== STATUT DES SERVICES ==="
    apache_status=$(sudo systemctl is-active apache2)
    mariadb_status=$(sudo systemctl is-active mariadb)
    postgresql_status=$(sudo systemctl is-active postgresql)
    
    echo "Apache2: $apache_status"
    echo "MariaDB: $mariadb_status"
    echo "PostgreSQL: $postgresql_status"
    echo ""
    echo "=== VERSIONS INSTALLÉES ==="
    echo "Apache: $(apache2 -v | head -n1)"
    echo "MariaDB: $(mariadb --version)"
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
    echo "MariaDB: Utilisez les identifiants configurés lors de mysql_secure_installation"
    echo "PostgreSQL: Utilisez 'sudo -u postgres psql' pour accéder en tant que postgres"
}

# Fonction principale
main() {
    echo "=== INSTALLATION STACK DE DÉVELOPPEMENT DEBIAN 12 ==="
    echo "=== Apache + MariaDB + PostgreSQL + PHP + Adminer ==="
    echo ""
    
    check_sudo
    update_system
    install_base_packages
    install_composer
    install_adminer
    start_services
    secure_mariadb
    show_summary
    
    print_success "Script terminé avec succès !"
}

# Exécution du script principal
main "$@"