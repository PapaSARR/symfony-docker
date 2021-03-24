# Appli Symfony dockerisé
Simple blog avec Symfony5, utilisation de docker pour installer le 
SGBD MySQL et l'interface Web PhpMyadmin.

## Installation et lancement du projet
1. git clone https://github.com/PapaSARR/symfony-docker
2. cd symfony-docker
   //Installation des dépendances
3. composer install & npm install
   //Lancement de MySql et phpMyAdmin via docker
4. docker-compose up -d
   //Chargement des données et migrations
5. symfony console doctrine:fixtures:load
6. symfony console doctrine:migrations:migrate
   //Compilation des assets
7. npm run watch
   //Lancement du projet
8. symfony serve -d
