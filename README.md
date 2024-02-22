### Blog
un Blog cree avec Laravel 10.45.1 et Vue.js 3.4.15
### Prérequis
Avant de commencer, assurez-vous d'avoir installé les logiciels suivants :

- PHP >= 8
- Composer
- Node.js et npm
- Un serveur de base de données (MySQL, PostgreSQL, etc.)
### Installation
1. Cloner le dépôt:``` bash git clone git@github.com:pouyasadri/blog-laravel-vue.git chemin/vers/votre/projet```
2. `cd chemin/vers/votre/projet/api/blog_api`
3. Installer les dépendances: `composer install`
4. Configurer l'environnement et la base de données:
   - Copiez le fichier `.env.example` en `.env` :
     ```bash
     cp .env.example .env
     ```
  -  Ouvrez le fichier .env et modifiez les valeurs relatives à la base de données (DB_DATABASE, DB_USERNAME, et DB_PASSWORD) pour correspondre à votre configuration locale. Par exemple, pour une base de données MySQL :
      ```bash
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=blog
      DB_USERNAME=votre_utilisateur
      DB_PASSWORD=votre_mot_de_passe
      ```
      Si vous utilisez un autre type de base de données (comme PostgreSQL), assurez-vous de modifier également DB_CONNECTION et DB_PORT en conséquence.
5. Générer une clé d'application
  ```bash
    php artisan key:generate
```
6. Exécuter les migrations et les seeders

Cela va configurer votre base de données et la remplir avec des données initiales.
```bash
php artisan migrate --seed
```
7. 3. Lancer le serveur de développement
   ```bash
   php artisan serve
   ```
### Frontend (Vue 3)
1. Naviguer vers le dossier du frontend
   ```bash
   cd chemin/vers/votre/projet/frontend/blog_frontend
   ```
2. Installer les dépendances
   ```bash
   npm install
   ```
3. Lancer le serveur de développement
   ```bash
   npm run dev
   ```
- Votre application frontend devrait maintenant être accessible à l'adresse indiquée dans la console (typiquement http://localhost:5137).
   
