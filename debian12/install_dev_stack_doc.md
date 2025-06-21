# 🚀 Installation automatique Stack de développement Debian 12

Ce dossier contient des scripts d'installation automatique pour configurer une stack de développement complète sur Debian 12 avec redémarrage automatique des services.

## 📁 Structure du projet

```
Install_dev_stack/
└── debian12/
    ├── install_dev_stack.sh         # Script Bash (recommandé)
    ├── install_dev_stack.php        # Script PHP (alternative)
    ├── install_dev_stack_doc.md     # Documentation détaillée
    └── README.md                    # Cette documentation
```

## 📦 Composants installés

Les scripts installent automatiquement les éléments suivants :

### Serveur Web & Bases de données
- **Apache 2** - Serveur web avec redémarrage automatique
- **MariaDB** - Base de données relationnelle (avec sécurisation automatique)
- **PostgreSQL** - Base de données relationnelle avancée
- **Adminer** - Interface web pour la gestion des bases de données

### Langages & Outils de développement
- **PHP** avec extensions complètes :
  - **Base** : php-cli, php-fpm, php-common, php-opcache
  - **Bases de données** : php-mysql, php-pgsql, php-sqlite3
  - **Web** : php-gd, php-curl, php-zip, php-xml
  - **Texte** : php-mbstring, php-json, php-intl, php-bcmath
  - **Avancées** : php-imagick, php-ldap, php-soap
  - **Système** : php-ctype, php-tokenizer, php-dom, php-fileinfo
- **Composer** - Gestionnaire de dépendances PHP
- **Node.js & npm** - Runtime JavaScript et gestionnaire de paquets
- **Git** - Contrôle de version
- **curl** - Outil de transfert de données

### Outils système
- **GParted** - Gestionnaire de partitions

## 📋 Scripts disponibles

### 1. Script Bash (`install_dev_stack.sh`)
Script shell traditionnel, rapide et efficace. Recommandé pour la plupart des utilisateurs.
- **Gestion automatique** des services avec redémarrage
- **Vérification du statut** et auto-réparation

### 2. Script PHP (`install_dev_stack.php`)
Version orientée objet avec gestion d'erreurs avancée et commentaires détaillés.
- **Méthodes spécialisées** pour la gestion des services
- **Diagnostic avancé** des états de services

## 🎯 Utilisation recommandée

1. **Téléchargez** ou **clonez** ce projet sur votre système Debian 12
2. **Placez-vous** dans le dossier `debian12`
3. **Exécutez** le script bash (recommandé) ou PHP selon votre préférence
4. **Suivez** les instructions interactives pour MariaDB
5. **Profitez** de votre environnement de développement complet !

## 🔧 Prérequis

- **Système** : Debian 12 (Bookworm) - **Version testée et validée**
- **Droits** : Accès sudo
- **Connexion** : Accès internet pour télécharger les paquets
- **Espace disque** : ~2-3 GB d'espace libre

## 🚀 Installation

### Prérequis
Assurez-vous d'être dans le dossier `debian12` :
```bash
cd Install_dev_stack/debian12
```

### Méthode 1 : Script Bash (Recommandé)

```bash
# 1. Rendre le script exécutable
chmod +x install_dev_stack.sh

# 2. Exécuter le script
./install_dev_stack.sh
```

### Méthode 2 : Script PHP

```bash
# 1. Rendre le script exécutable  
chmod +x install_dev_stack.php

# 2. Exécuter le script
php install_dev_stack.php
```

## ⚙️ Processus d'installation amélioré

Les scripts suivent cette séquence optimisée :

1. **Vérification des droits sudo**
2. **Mise à jour du système**
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

3. **Installation des paquets de base**
   - Apache 2, MariaDB, PostgreSQL
   - PHP avec 20+ extensions
   - Node.js, npm, Git, curl
   - Outils système (GParted)

4. **Installation de Composer**
   - Téléchargement et installation globale
   - Configuration des permissions

5. **Installation et configuration d'Adminer**
   - Téléchargement d'Adminer
   - Configuration Apache pour l'accès web

6. **Démarrage et redémarrage des services** *(NOUVEAU)*
   - **Activation** pour démarrage automatique
   - **Démarrage initial** des services
   - **Redémarrage complet** pour prise en compte des configurations
   - **Vérification automatique** du statut
   - **Auto-réparation** en cas de problème

7. **Sécurisation de MariaDB**
   - Exécution interactive de `mysql_secure_installation`
   - **Redémarrage final** de MariaDB après sécurisation

## 🌐 Accès aux services après installation

Une fois l'installation terminée, vous pouvez accéder à :

- **Apache** : http://localhost
- **Adminer** : http://localhost/adminer
- **MariaDB** : Via les identifiants configurés lors de la sécurisation
- **PostgreSQL** : `sudo -u postgres psql`

## 📝 Configuration post-installation

### MariaDB
- Le script exécute `mysql_secure_installation` de manière interactive
- **Redémarrage automatique** après sécurisation
- Vous devrez choisir vos préférences de sécurité
- Aucun utilisateur supplémentaire n'est créé automatiquement
- Compatible avec toutes les applications MySQL

### PostgreSQL
- Installation par défaut sans modification
- **Service redémarré** pour configuration optimale
- Utilisez `sudo -u postgres psql` pour l'accès administrateur
- Aucun utilisateur supplémentaire n'est créé automatiquement

### Apache
- Configuration par défaut sécurisée
- **Redémarrage automatique** après configuration PHP et Adminer
- Document root : `/var/www/html`
- Adminer accessible via `/adminer`
- PHP activé et configuré

## 🔍 Vérification de l'installation

Après l'installation, vérifiez que tout fonctionne :

```bash
# Vérifier les services (nouveau : affichage du statut dans le résumé)
sudo systemctl status apache2
sudo systemctl status mariadb
sudo systemctl status postgresql

# Vérifier les versions installées
apache2 -v
mariadb --version
psql --version
php -v
composer --version
node --version
npm --version
git --version

# Tester PHP et ses extensions
php -m | grep -E "(mysql|pgsql|gd|curl|zip|imagick)"

# Tester les connexions aux bases de données
php -r "echo 'MySQL/MariaDB: ' . (extension_loaded('mysql') || extension_loaded('mysqli') ? 'OK' : 'ERREUR') . PHP_EOL;"
php -r "echo 'PostgreSQL: ' . (extension_loaded('pgsql') ? 'OK' : 'ERREUR') . PHP_EOL;"
```

## ⚠️ Points importants

### Fiabilité améliorée *(NOUVEAU)*
- **Redémarrage automatique** de tous les services après installation
- **Vérification du statut** avec tentative de redémarrage en cas de problème
- **Affichage du statut** en temps réel dans le résumé final
- **Auto-réparation** des services défaillants

### Sécurité
- **MariaDB** : Le script respecte les configurations par défaut et exécute la sécurisation standard
- **PostgreSQL** : Configuration par défaut maintenue
- **Apache** : Configuration de base sécurisée

### Gestion des erreurs
- Les scripts s'arrêtent en cas d'erreur critique
- Messages colorés pour suivre l'avancement
- Vérification des droits sudo avant démarrage
- **Diagnostic automatique** des services

### Fichiers temporaires
- Nettoyage automatique des fichiers de téléchargement
- Pas de résidus laissés sur le système

## 🛠️ Dépannage

### Erreur de droits sudo
```bash
# Si vous obtenez une erreur de droits sudo
sudo usermod -aG sudo $USER
# Puis redémarrez votre session
```

### Échec de téléchargement
- Vérifiez votre connexion internet
- Certains téléchargements peuvent nécessiter plusieurs tentatives

### Services non démarrés *(Amélioration automatique)*
```bash
# Les scripts redémarrent automatiquement les services défaillants
# Mais vous pouvez le faire manuellement si nécessaire :
sudo systemctl restart apache2
sudo systemctl restart mariadb
sudo systemctl restart postgresql

# Vérifier les logs en cas de problème persistant :
sudo journalctl -u apache2 -f
sudo journalctl -u mariadb -f
sudo journalctl -u postgresql -f
```

### Extensions PHP manquantes
```bash
# Vérifier les extensions installées
php -m

# Installer une extension manquante (exemple)
sudo apt install php-[extension]
sudo systemctl restart apache2
```

### Diagnostic avancé *(NOUVEAU)*
```bash
# Vérifier l'état détaillé des services
systemctl list-units --state=failed

# Tester la connectivité des bases de données
sudo mysql -u root -p -e "SELECT VERSION();"
sudo -u postgres psql -c "SELECT version();"

# Tester PHP via Apache
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/test.php
# Puis visiter : http://localhost/test.php
```

## 📊 Temps d'installation estimé

- **Durée totale** : 10-15 minutes
- **Téléchargements** : 5-8 minutes (selon connexion)
- **Installation** : 5-7 minutes
- **Configuration et redémarrages** : 1-2 minutes *(NOUVEAU)*
- **Configuration MariaDB** : 2-3 minutes (interaction utilisateur)

## 🌟 Avantages de cette stack améliorée

### 🔄 Fiabilité des services *(NOUVEAU)*
- **Redémarrage systématique** après installation
- **Vérification automatique** du statut
- **Auto-réparation** en cas de défaillance
- **Diagnostic intégré** dans le résumé final

### 🔄 MariaDB vs MySQL
- **Performance** : MariaDB offre de meilleures performances
- **Compatibilité** : 100% compatible avec MySQL
- **Open Source** : Vraiment libre et ouvert
- **Fonctionnalités** : Plus de fonctionnalités avancées

### 📦 Extensions PHP complètes
- **Développement web** : Toutes les extensions pour sites modernes
- **APIs** : Support complet pour REST, SOAP, GraphQL
- **Images** : Manipulation avancée avec GD et ImageMagick
- **Bases de données** : Support MySQL, PostgreSQL, SQLite

## 🆘 Support

En cas de problème :

1. **Vérifiez le résumé final** du script (affiche le statut des services)
2. Vérifiez les logs système : `journalctl -xe`
3. Vérifiez l'état des services : `systemctl status [service]`
4. Consultez les logs Apache : `/var/log/apache2/`
5. Consultez les logs MariaDB : `/var/log/mysql/`
6. Testez les extensions PHP : `php -m`
7. **Relancez le script** si nécessaire (idempotent)

## 📄 Licence

Ces scripts sont fournis "en l'état" pour faciliter l'installation d'un environnement de développement sur Debian 12.

---

**Stack installée** : Apache 2 + MariaDB + PostgreSQL + PHP (20+ extensions) + Composer + Adminer + Node.js + Git

**✨ Nouveau** : Redémarrage automatique et vérification des services pour une fiabilité maximale