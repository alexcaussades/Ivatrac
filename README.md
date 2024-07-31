# IVATRAC 

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Navigateur supporté
![image](https://img.shields.io/badge/Firefox_Browser-FF7139?style=for-the-badge&logo=Firefox-Browser&logoColor=white)
![image](https://img.shields.io/badge/Google_Chrome-4285F4?style=for-the-badge&logo=Google-Chrome&logoColor=white)
![image](https://img.shields.io/badge/Opera-FF1B2D?style=for-the-badge&logo=Opera&logoColor=white)
![image](https://img.shields.io/badge/Microsoft_Edge-0078D7?style=for-the-badge&logo=Microsoft-Edge&logoColor=white)

## Technologies utilisées
![image](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=PHP&logoColor=white)
![image](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=Laravel&logoColor=white)
![image](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=HTML5&logoColor=white)
![image](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=CSS3&logoColor=white)
![image](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=JavaScript&logoColor=black)
![image](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=Bootstrap&logoColor=white)
![image](https://img.shields.io/badge/Sqlite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)

> [!NOTE]
> Gestion de la documentation du projet sera faite avec le [Wiki GitHub](https://github.com/alexcaussades/Ivatrac/wiki)


## Description
Ce projet a pour but de gérer le suivit du biogaz des sites de production. :hotsprings:

## Prérequis
- [Git](https://git-scm.com/downloads)
- [PHP](https://windows.php.net/download/)
- [Composer](https://getcomposer.org/Composer-Setup.exe)

Vous voulez suivre les étapes d'[installation pas à pas](https://github.com/alexcaussades/Ivatrac/wiki/%5BTech%5D-Installation) 

# Installation
Pour installer le projet, il suffit de suivre les étapes suivantes :

```bash
git clone https://github.com/alexcaussades/ivatrac.git
cd Gestion_biogaz
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php artisan migrate
php artisan serve
````