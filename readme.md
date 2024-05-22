Harpia - UemaNet
=======================

Introdução
------------
Esse é o repositório do sistema de gestão modular Núcleo de Tecnologias para Educação - UemaNet.

Tecnologias utilizadas:
-----------------------
Backend:
--------
 * PHP 8.1
 * Laravel Framework 8

Frontend:
---------
 * Twitter Bootstrap 3.3
 * jQuery 3.1
 * Font Awesome 3.2
 * Bootbox
 * AdminLTE

Requerimentos do sistema
------------
 * PHP >= 8.1
 * OpenSSL PHP Extension
 * PDO PHP Extension
 * Mbstring PHP Extension
 * Tokenizer PHP Extension
 * XML PHP Extension

Requerimentos do Moodle
------------
 * Versão mínima: 4.2

Instalação
------------

Usando composer (recomendado)
----------------------------
Clone o repositório e manualmente execute o 'composer':

    cd /var/www/html
    git clone https://github.com/uemanet/harpia.git
    cd harpia
    php composer self-update
    php composer install

Os comandos acima baixam o código do sistema e instalam suas dependências. Agora é preciso configurar o sistema.

Você pode copiar o arquivo .env.example e criar um novo chamado .env, nesse arquivo ficarão as configurações do banco de dados e demais configurações do sistema.

    cp .env.example .env

Abra o arquivo .env e configure-o de acordo com as informações do seu servidor.

Por fim execute o comando abaixo parar criar uma chave para a aplicação:

    php artisan key:generate

Nosso último passo é executar os comandos para criar a base de dados do sistema. Você pode com um único comando executar as migrations que irão criar as tabelas do banco de dados e também popular as tabelas básicas para o sistema em produção. O comando é:

    php artisan modulos:migrate --seed=prod

Caso queira, você pode executar um comando para criar as tabelas e populá-las com dados fictícios. Isso é muito bom para fins de desenvolvimento e também para conhecer as funcionalidades do sistema, uma vez que vários dados serão criados de forma prática. O comando está logo abaixo:

    php artisan modulos:migrate --seed=dev

Pronto! Se você seguiu todos os passos corretamente o sistema já está disponível para você. Para fazer login utilize as credenciais abaixo:

    Usuário: admin@admin.com
    Senha: 123456

Criando um virtual host(opcional)
------------
    <VirtualHost *:80>
        ServerName harpia.dev
        DocumentRoot /var/www/html/harpia/public
        SetEnv PROJECT_ROOT "/var/www/html/harpia"
        <Directory /var/www/html/harpia/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

Laravel
------------

O sistema foi desenvolvido utilizando o framework Laravel 8.0. Caso tenha alguma dúvida na configuração, instalação de dependências, ou para entender o funcionamento do framework, você pode utilizar a documentação no site oficial do Laravel.

[https://laravel.com/docs/8.x](https://laravel.com/docs/8.x/readme)
