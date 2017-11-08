# inventi

**Pré-Requisitos**

 1. GIT
 2. Php 7.1 ou superior
 3. Mysql 5.7 ou Superior
 4. Composer

**Roteiro**

 - Clonar o projeto em uma pasta do SO.
```sh
git clone https://github.com/xeninhu/inventi.git`
```
 - Instalar dependências via composer
```sh
composer install
```
 - Configurar .env
	 - Existe um arquivo .env de template(.env.example). Crie um arquivo de igual modelo com o nome de .env, e configure as diretivas de banco e SMTP.
		 - Antes você deverá ter configurado seu mysql e criado um esquema, que será apontado na diretiva DB_DATABASE
 - Gerar chave da aplicação
```sh
php artisan key:generate
```
 - Instalar as migrations, com seeds
```sh
php artisan migrate:install
php artisan migrate --seed
```
 - Iniciar o servidor
```sh
php artisan serve
```

**Acesso ao sistema**

Basta acessar a URL http://localhost:8000. É criado um usuário master com as seguintes credenciais:

 - E-mail: admin@admin.com
 - Senha: n60CQwEHN5

**Seja feliz :)**