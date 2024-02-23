### Blog
un Blog cree avec Laravel 10.45.1 et Vue.js 3.4.15
### Prérequis
Avant de commencer, assurez-vous d'avoir installé les logiciels suivants :

- PHP >= 8
- Composer
- Node.js et npm
- Un serveur de base de données (MySQL, PostgreSQL, etc.)
### Installation
- Backend (Laravel)
1. Cloner le dépôt:
   ```bash
   git clone git@github.com:pouyasadri/blog-laravel-vue.git
    ```
2. Naviguer vers le dossier du backend :
   Remplacez `chemin/vers/votre/projet` par le chemin réel sur votre système.
    ```bash
      cd chemin/vers/votre/projet/api/blog_api
3. Installer les dépendances:
      ```bash
         composer install
4. Configurer l'environnement et la base de données:
   1. Copiez le fichier `.env.example` en `.env` :
     ```bash
     cp .env.example .env
     ```
   2. Ouvrez le fichier .env et modifiez les valeurs relatives à la base de données (DB_DATABASE, DB_USERNAME, et DB_PASSWORD) pour correspondre à votre configuration locale.
      Si vous utilisez un autre type de base de données (comme PostgreSQL), assurez-vous de modifier également DB_CONNECTION et DB_PORT en conséquence.
5. Générer une clé d'application
     ```bash
       php artisan key:generate
     ```
6. Créer un lien symbolique pour le répertoire de stockage
   ```bash
      php artisan storage:link
7. Exécuter les migrations et les seeders

   Cela va configurer votre base de données et la remplir avec des données initiales.
      ```bash
      php artisan migrate:fresh --seed --seeder=ArticleSeeder
8. Lancer le serveur de développement
   ```bash
   php artisan serve
- Frontend (Vue 3)
1. Naviguer vers le dossier du frontend
   Remplacez `chemin/vers/votre/projet` par le chemin réel sur votre système.
   ```bash
   cd chemin/vers/votre/projet/frontend/blog_frontend
3. Installer les dépendances
   ```bash
   npm install
4. Lancer le serveur de développement
   ```bash
   npm run dev
- Votre application frontend devrait maintenant être accessible à l'adresse indiquée dans la console (`typiquement http://localhost:5137`).
   
