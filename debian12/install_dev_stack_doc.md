# 🚀 Installation automatique Stack de développement Debian 12

Ce dossier contient des scripts d'installation automatique pour configurer une stack de développement complète sur Debian 12.

## 📁 Structure du projet

```
Install_dev_stack/
└── debian12/
    ├── install_dev_stack.sh   # Script Bash (recommandé)
    ├── install_dev_stack.php  # Script PHP (alternative)
    └── README.md             # Cette documentation
```

## 📦 Composants installés

Les scripts installent automatiquement les éléments suivants :

### Serveur Web & Bases de données
- **Apache 2** - Serveur web
- **MySQL** - Base de données relationnelle (avec sécurisation automatique)
- **PostgreSQL** - Base de données relationnelle avancée
- **Adminer** - Interface web pour la gestion des bases de données

### Langages & Outils de développement
- **PHP** avec toutes les extensions nécessaires :
  - php-apache2handler
  - php-mysql, php-pgsql
  - php-gd, php-curl, php-zip
  - php-xml, php-mbstring, php-json
  - php-intl, php-bcmath
- **Composer** - Gestionnaire de dépendances PHP
- **Node.js & npm** - Runtime JavaScript et gestionnaire de paquets
- **Git** - Contrôle de version
- **curl** - Outil de transfert de données

### Applications
- **Visual Studio Code** - Éditeur de code
- **Google Chrome** - Navigateur web
- **GParted** - Gestionnaire de partitions

## 📋 Scripts disponibles

### 1. Script Bash (`install_dev_stack.sh`)
Script shell traditionnel, rapide et efficace.

### 2. Script PHP (`install_dev_stack.php`)
Version orientée objet avec gestion d'erreurs avancée.

## 🎯 Utilisation recommandée

1. **Téléchargez** ou **clonez** ce dossier sur votre système Debian 12
2. **Placez-vous** dans le dossier `~/Bureau/Install_dev_stack/debian12`
3. **Exécutez** le script bash (recommandé) ou PHP selon votre préférence
4. **Suivez** les instructions interactives pour MySQL
5. **Profitez** de votre environnement de développement complet !

## 🔧 Prérequis

- **Système** : Debian 12 (Bookworm) - **Version testée et validée**
- **Droits** : Accès sudo
- **Connexion** : Accès internet pour télécharger les paquets
- **Emplacement** : Placez-vous dans le dossier `~/Bureau/Install_dev_stack/debian12` avant l'exécution

## 🚀 Installation

### Prérequis
Assurez-vous d'être dans le dossier `debian12` :
```bash
cd ~/Bureau/Install_dev_stack/debian12
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

## ⚙️ Processus d'installation

Les scripts suivent cette séquence :

1. **Vérification des droits sudo**
2. **Mise à jour du système**
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

3. **Installation des paquets de base**
   - Installation via apt de tous les composants principaux

4. **Installation de VS Code**
   - Ajout du repository Microsoft
   - Installation via apt

5. **Installation de Google Chrome**
   - Téléchargement du paquet .deb
   - Installation via dpkg

6. **Installation de Composer**
   - Téléchargement et installation globale

7. **Installation et configuration d'Adminer**
   - Téléchargement d'Adminer
   - Configuration Apache pour l'accès web

8. **Démarrage des services**
   - Activation et démarrage d'Apache, MySQL, PostgreSQL

9. **Sécurisation de MySQL**
   - Exécution interactive de `mysql_secure_installation`

## 🌐 Accès aux services après installation

Une fois l'installation terminée, vous pouvez accéder à :

- **Apache** : http://localhost
- **Adminer** : http://localhost/adminer
- **MySQL** : Via les identifiants configurés lors de la sécurisation
- **PostgreSQL** : `sudo -u postgres psql`

## 📝 Configuration post-installation

### MySQL
- Le script exécute `mysql_secure_installation` de manière interactive
- Vous devrez choisir vos préférences de sécurité
- Aucun utilisateur supplémentaire n'est créé automatiquement

### PostgreSQL
- Installation par défaut sans modification
- Utilisez `sudo -u postgres psql` pour l'accès administrateur
- Aucun utilisateur supplémentaire n'est créé automatiquement

### Apache
- Configuration par défaut
- Document root : `/var/www/html`
- Adminer accessible via `/adminer`

## 🔍 Vérification de l'installation

Après l'installation, vérifiez que tout fonctionne :

```bash
# Vérifier les services
sudo systemctl status apache2
sudo systemctl status mysql
sudo systemctl status postgresql

# Vérifier les versions installées
apache2 -v
mysql --version
psql --version
php -v
composer --version
node --version
npm --version
git --version
```

## ⚠️ Points importants

### Sécurité
- **MySQL** : Le script respecte les configurations par défaut et exécute la sécurisation standard
- **PostgreSQL** : Configuration par défaut maintenue
- **Apache** : Configuration de base sécurisée

### Gestion des erreurs
- Les scripts s'arrêtent en cas d'erreur critique
- Messages colorés pour suivre l'avancement
- Vérification des droits sudo avant démarrage

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

### Services non démarrés
```bash
# Redémarrer manuellement si nécessaire
sudo systemctl restart apache2
sudo systemctl restart mysql
sudo systemctl restart postgresql
```

## 📊 Temps d'installation estimé

- **Durée totale** : 10-15 minutes
- **Téléchargements** : 5-8 minutes (selon connexion)
- **Installation** : 5-7 minutes
- **Configuration MySQL** : 2-3 minutes (interaction utilisateur)

## 🆘 Support

En cas de problème :

1. Vérifiez les logs système : `journalctl -xe`
2. Vérifiez l'état des services : `systemctl status [service]`
3. Consultez les logs Apache : `/var/log/apache2/`
4. Consultez les logs MySQL : `/var/log/mysql/`

## 📄 Licence

Ces scripts sont fournis "en l'état" pour faciliter l'installation d'un environnement de développement sur Debian 12.