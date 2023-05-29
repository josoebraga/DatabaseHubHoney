# [Sistema auxiliar no pré-processamento de mailing]

![version](https://img.shields.io/badge/version-1.0.0-blue.svg) 
![license](https://img.shields.io/badge/license-MIT-blue.svg)
[![GitHub issues open](https://img.shields.io/github/issues/creativetimofficial/soft-ui-dashboard-laravel-livewire.svg)](https://github.com/creativetimofficial/soft-ui-dashboard-laravel-livewire/issues?q=is%3Aopen+is%3Aissue) 
[![GitHub issues closed](https://img.shields.io/github/issues-closed-raw/creativetimofficial/soft-ui-dashboard-laravel-livewire.svg)](https://github.com/creativetimofficial/soft-ui-dashboard-laravel-livewire/issues?q=is%3Aissue+is%3Aclosed)


*Frontend version*: Soft UI Dashboard v1.0.0. More info at https://www.creative-tim.com/product/soft-ui-dashboard-laravel-livewire/?ref=sudl-readme 

## Pré-requisitos

1. Git
2. Docker
3. WSL2 (Apenas para usuários do Windows)
4. PHP ^8.1
5. Laravel ^8

## Instalação

1. Copie o projeto para dentro de uma pasta do WSL2, em caso de estar usando o Windows, ou em qualquer pasta do Linux, e execute: git clone https://github.com/josoebraga/DatabaseHubHoney.git
2. Entre na pasta code e execute o camando: cp .env.example .env
3. Entre na pasta code e execute o camando: php artisan key:generate
4. Configure a conexão com o banco de dados PostgreSQL no arquivo .env
5. Faça duas cópias do .env chamadas de .env.dev e .env.prd 
6. Na pasta raiz execute: docker-compose build
7. Na pasta raiz execute: docker-compose up -d
8. O projeto executará localmente em um endereço similar a http://127.0.0.1:7877/ 
