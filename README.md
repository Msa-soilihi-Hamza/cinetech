

# Cinetech - Guide de Déploiement

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- Node.js et NPM
- Git
- Une clé API TMDB (The Movie Database)

## Installation en Local

1. **Cloner le projet**

```bash
git clone https://github.com/votre-username/cinetech.git
cd cinetech
```

2. **Installer les dépendances**

```bash
composer install
npm install
```

3. **Configuration de l'environnement**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
- Créer une base de données MySQL
- Modifier le fichier `.env` avec vos informations :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinetech
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

5. **Configurer l'API TMDB**
- Créer un compte sur [TMDB](https://www.themoviedb.org/)
- Obtenir une clé API dans les paramètres de votre compte
- Ajouter la clé dans le fichier `.env` :

```env
TMDB_API_KEY=votre_cle_api
```

6. **Lancer les migrations**
```bash
php artisan migrate
```

7. **Compiler les assets**
```bash
npm run dev
```

8. **Démarrer le serveur**
```bash
php artisan serve
```

L'application est maintenant accessible à l'adresse `http://localhost:8000`

## Déploiement sur GitHub

1. **Créer un nouveau repository sur GitHub**

2. **Initialiser Git et pousser le code**
```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/votre-username/cinetech.git
git push -u origin main
```

## Maintenance

- Pour mettre à jour les dépendances :
```bash
composer update
npm update
```

- Pour les migrations après modification de la base de données :
```bash
php artisan migrate
```

## Sécurité

- Ne jamais commiter le fichier `.env`
- Protéger votre clé API TMDB
- Mettre à jour régulièrement les dépendances

## Support

En cas de problème, vous pouvez :
- Ouvrir une issue sur GitHub
- Consulter la [documentation Laravel](https://laravel.com/docs)
- Consulter la [documentation TMDB](https://developers.themoviedb.org/3)

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.