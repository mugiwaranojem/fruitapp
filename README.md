## Pre-requisite
* PHP 7.4 or greater installed on local
* Mysql 8 or higher
* Symfony CLI version 5.6.0 (https://symfony.com/download)
* Composer version 2.0.14 or higher
* node v16.16.0 or higher

## Stacks
* Symfony 5.4
* ApiPlatform
* VueJs 2
* Bootstrap
* Mysql 8

### Setup Instructions
* git clone https://github.com/mugiwaranojem/fruitapp.git && cd fruitapp
* Update .env file database url update DATABASE_URL credentials, mysql username, password, port
  - DATABASE_URL=mysql://root:root@localhost:3306/fruits_project
* ```composer install```
* ``php bin/console doctrine:database:create``
* ```symfony serve```
* Open new terminal go to app directory and run migrations:
  - ```php bin/console doctrine:migrations:migrate```
* Run the script command to fetch data
  - ```php bin/console app:fetch:fruits```
* Open new terminal go to app directory and initialize frontend
  - ```yarn install```
  - ```yarn run watch```
* Go to browser and start navigating the page, view list, paginate, add favorite and view favorite page
  - http://localhost:8000/
 
### Scripts
#### ESLint
```npx eslint --fix --ext .js,.vue assets```

#### PHP Coding Standard Fix
```./vendor/bin/php-cs-fixer fix```


### Further enhancement
#### Infra
* Containerize app PHP, Mysql
* Update MAILER_DSN param as a secret variable for username:password as SSM secret

#### Application
* Add queueing for email service
* Add auto linter php cs fixer/eslinter

