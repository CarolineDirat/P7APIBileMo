# P7APIBileMo

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/03e69ed939f34f3190133629f48d7255)](https://app.codacy.com/gh/CarolineDirat/P7APIBileMo?utm_source=github.com&utm_medium=referral&utm_content=CarolineDirat/P7APIBileMo&utm_campaign=Badge_Grade_Settings)

API REST (level 3 on the Richardson model) - Symfony 5 - Student project
"PHP/Symfony Application Developer" course on OpenClassrooms (Projet n°7: Create a web service exposing an API)

## Requirements

### P7APIBileMo installation needs in command line
  - **Composer**: [getcomposer.org/](https://getcomposer.org/)
  - **Git**: [git-scm.com/](https://git-scm.com/) to clone the project
  - **OpenSSL**: [openssl.org](https://www.openssl.org/) to generate public and private keys with LexikJWTAuthenticationBundle (for JWT token authentication).
  - **PHP** 7.4.*: [php.net](https://www.php.net/)

### P7APIBileMo use

  - **PHP** version: 7.4.* (server and terminal),[www.php.net/](https://www.php.net/).

  - **MySQL** database that you can manage with a **database tool** (as [phpmyadmin](https://www.phpmyadmin.net/) or [DBeaver](https://dbeaver.io/) ...).

  - **URL rewriting**, so on **Apache**, you must active **rewrite_module** module in Apache **http.conf** file.

  - **[ramsey\uuid](https://github.com/ramsey/uuid)** for uuid management
  This package require PHP +7.2 and PHP extensions:
    - ext-json
    - ext-ctype
    - ext-gmp
    - ext-bcmath

  - **[symfony requirements](https://symfony.com/doc/current/setup.html#technical-requirements)**:
  For example in **php.ini**:
    - memory_limit = 128M
    - realpath_cache_size = 5M
    - activation of [opcache] PHP extension:
      - opcache.enable=On
      - opcache.enable_cli=On

## Installation on a local server

The following instructions guide you to install the project locally, on HTTP server Apache (for example : Wampserver). [See Symfony documentation](https://symfony.com/doc/current/setup.html#setting-up-an-existing-symfony-project) 

1. **Clone the project** from Github 
   At the root of your local serveur, with command line
   > `git clone  https://github.com/CarolineDirat/P7APIBileMo.git` [**directory**]

**directory** is the name of a new directory to clone into. 
If you don't use it, the project is cloned in *P7APIBileMo* directory. And we obtain for example: C:/wamp/www/P7APIBileMo

--------
2. Create your **virtualhost** on Wampserver.
Be careful, virtualhost must point to the public directory
**_For example:_** C:/wamp/www/P7APIBileMo/public

--------
3. At the root of the project directory, use composer to **load vendor** and **var** directories with:
   > `composer install`
   
--------
4. **[Overriding Environment Values via .env.local](https://symfony.com/doc/current/configuration.html#overriding-environment-values-via-env-local)**

Create a **.env.dev.local** file, at the root of the project directory, to define (see .env file) :
- **DATABASE_URL** value ([see Symfony documentation](https://symfony.com/doc/current/doctrine.html#configuring-the-database))
_For example:_
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7" (see .env file)

- **APP_HOST_HTTP** value (see your virtualhost, ex = http://p7apibilemo)

- Constant values for lexik/jwt-authentication-bundle
  ````
  ###> lexik/jwt-authentication-bundle ###
  JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
  JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
  JWT_PASSPHRASE=write_your_own_pass_phrase
  ###< lexik/jwt-authentication-bundle ###
  ````
  You have to choose and write a pass phrase to define **JWT_PASSPHRASE**.
  
--------
5. Generate SSH key for [lexik/jwt-authentication-bundle](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#generate-the-ssh-keys)

5.1. Create config/jwt directory
````
mkdir -p config/jwt
````
5.2. Generate config/jwt/private.pem file (private key): with the following line command
````
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
````
**Enter PEM pass phrase:** _write the value of JWT_PASSPHRASE, in .env.dev.local_

**Verifying - Enter PEM pass phrase:** _write the value of JWT_PASSPHRASE, in .env.dev.local_

5.3. Generate config/jwt/public.pem file (public key), with the following line command:
````
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
````
**Enter pass phrase for config/jwt/private.pem:** _write the value of JWT_PASSPHRASE, in .env.dev.local_

--------
6. Now that your connection parameters are setup, Doctrine can **create** the db_name **database** for you:

> php bin/console doctrine:database:create

That's create a database with the "db_name" which name you defined in DATABASE_URL value.

Then, you can [create the database **tables**/schema](https://symfony.com/doc/current/doctrine.html#migrations-creating-the-database-tables-schema), with the following line command:
   
   > php bin/console doctrine:migrations:migrate

**Answer _yes_ to the question**: "_WARNING! You are about to execute a database migration that could result in schema changes and data loss. Are you sure you wish to continue?" (yes/no) [yes]:_"

--------
7. **Load initial data** (from src/DataFixtures.php) with line command:
   
> php bin/console doctrine:fixtures:load

**Answer _yes_ to the question**: _Careful, database "db_name" will be purged. Do you want to continue? (yes/no) [no]:"_

There is **10 phones**, and **2 clients** of which only the first has users.
Here are the identifiers of the two registered clients:
  username      | password
  ------------- | ----------------
  FirstClient   | password 
  SecondClient  | password 

--------
8. You can access to the **API REST documentation** on the **/api/doc** URI.

