# 🚀 Install Dev Stack - Scripts d'installation automatique

Collection de scripts d'installation automatique pour configurer rapidement des environnements de développement complets sur différentes distributions Linux.

## 📋 Vue d'ensemble

Ce projet propose des scripts clés en main pour installer et configurer une stack de développement complète incluant :
- **Serveurs web** (Apache, Nginx)
- **Bases de données** (MySQL, PostgreSQL)
- **Langages** (PHP, Node.js)
- **Outils de développement** (VS Code, Git, Composer)
- **Applications** (Chrome, utilitaires système)

## 🎯 Installation rapide

### 1. Cloner le repository
```bash
git clone https://github.com/Cyrus2401/Install_dev_stack.git
cd Install_dev_stack
```

### 2. Choisir votre distribution
```bash
# Pour Debian 12 par exemple
cd debian12/
chmod +x install_dev_stack.sh
./install_dev_stack.sh
```

### 3. Suivre les instructions
Les scripts sont interactifs et vous guident à travers le processus d'installation.

## 🛠️ Composants installés

### Serveur Web & Bases de données
- **Apache 2** - Serveur web principal
- **MySQL** - Base de données relationnelle avec sécurisation
- **PostgreSQL** - Base de données avancée
- **Adminer** - Interface web de gestion BDD

### Développement
- **PHP** avec extensions complètes
- **Composer** - Gestionnaire de dépendances PHP
- **Node.js & npm** - Environnement JavaScript
- **Git** - Contrôle de version

### Outils & Applications
- **Visual Studio Code** - Éditeur de code moderne
- **Google Chrome** - Navigateur de développement
- **GParted** - Gestionnaire de partitions
- **curl** - Utilitaire de transfert

## 📖 Documentation détaillée

Chaque distribution dispose de sa documentation complète :

- **[Debian 12](./debian12/install_dev_stack_doc.md)** - Guide complet d'installation et configuration

## 🔧 Prérequis généraux

- **Système** : Distribution Linux supportée
- **Droits** : Accès administrateur (sudo)
- **Connexion** : Accès internet pour téléchargements
- **Espace** : ~2-3 GB d'espace disque libre

## ⚡ Avantages

### 🚀 Rapidité
- Installation complète en 10-15 minutes
- Configuration automatique des services
- Aucune intervention manuelle (sauf sécurisation MySQL)

### 🔒 Sécurité
- Respect des bonnes pratiques
- Sécurisation automatique des services
- Configurations par défaut maintenues

### 📚 Documentation
- Guides détaillés pour chaque distribution
- Instructions pas à pas
- Dépannage et support

## 🌟 Cas d'usage

- Configuration rapide de nouveaux environnements
- Stack LAMP/LEMP complète
- Outils de développement intégrés

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour ajouter une nouvelle distribution :

1. **Fork** le projet
2. **Créer** un dossier pour la nouvelle distribution
3. **Adapter** les scripts selon les spécificités
4. **Tester** sur système propre
5. **Documenter** l'installation
6. **Proposer** une Pull Request

### Structure pour nouvelle distribution
```
nouvelle_distrib/
├── install_dev_stack.sh      # Script principal
├── install_dev_stack.php     # Version alternative (optionnel)
└── install_dev_stack_doc.md  # Documentation
```