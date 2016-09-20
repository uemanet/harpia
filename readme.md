
Harpia - UemaNet
=======================

Introdução
------------
Esse é o repositório do sistema de gestão modular Núcleo de Tecnologias para Educação - UemaNet.

Tecnologias utilizadas:
-----------------------
Backend:
--------
 * PHP 5.6+
 * Laravel Framework 5.3
 * Docker

Frontend:
---------
 * Twitter Bootstrap 3.3
 * jQuery 3.1
 * Font Awesome 3.2 
 * Bootbox
 * AdminLTE

Instalação
------------

Usando composer (recomendado)
----------------------------
Clone o repositório e manualmente execute o 'composer':

    cd /var/www/html
    git clone http://200.166.97.120/dte/harpia.git
    cd harpia
    php composer self-update
    php composer install

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

Colaboradores (ordem alfabética)
-------------
* **Bruno Luan**

* **Felipe Pimenta**

* **Lucas Vieira**

* **Pedro Fellipe**

* **Willian Mano**