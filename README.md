# [Sistema auxiliar no pré-processamento de mailing]

![version](https://img.shields.io/badge/version-1.0.0-blue.svg) 
![license](https://img.shields.io/badge/license-MIT-blue.svg)
[![GitHub issues open](https://img.shields.io/github/issues/creativetimofficial/soft-ui-dashboard-laravel-livewire.svg)](https://github.com/creativetimofficial/soft-ui-dashboard-laravel-livewire/issues?q=is%3Aopen+is%3Aissue) 
[![GitHub issues closed](https://img.shields.io/github/issues-closed-raw/creativetimofficial/soft-ui-dashboard-laravel-livewire.svg)](https://github.com/creativetimofficial/soft-ui-dashboard-laravel-livewire/issues?q=is%3Aissue+is%3Aclosed)


*Frontend version*: Soft UI Dashboard v1.0.0. More info at https://www.creative-tim.com/product/soft-ui-dashboard-laravel-livewire/?ref=sudl-readme 

[<img src="https://s3.amazonaws.com/creativetim_bucket/products/492/original/soft-ui-dashboard-laravel-livewire.jpg" width="100%" />](https://www.creative-tim.com/live/soft-ui-dashboard-laravel-livewire)

## Installation

1. Unzip the downloaded archive
2. Copy and paste **soft-ui-dashboard-laravel-master** folder in your **projects** folder. Rename the folder to your project's name
3. In your terminal run `composer install`
4. Copy `.env.example` to `.env` and updated the configurations (mainly the database configuration)
5. In your terminal run `php artisan key:generate`
6. Run `php artisan migrate --seed` to create the database tables and seed the roles and users tables
7. Run `php artisan storage:link` to create the storage symlink (if you are using **Vagrant** with **Homestead** for development, remember to ssh into your virtual machine and run the command from there).

php artisan migrate:refresh --seed
