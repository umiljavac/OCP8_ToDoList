# OCP8_ToDoList

Eighth project of OpenClassrooms "Développeur d'application PHP/Symfony" cursus. 

Improve an existing project.

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/461ce1cb-8722-417c-8142-c657039da368/mini.png)](https://insight.sensiolabs.com/projects/461ce1cb-8722-417c-8142-c657039da368)

## 1-Intro 
The aim of this project is to improve an existing project.  
We have to correct some anomalies and implement new functionalities.
  
## 2-Requirements
This project use Symfony 4 framework and Symfony 4 requires PHP version > 7.1.3 to run. 

## 3-Installation 
1. Clone this repository (Master branche)
2. Put the downloaded repository into your server root folder. You can also use the Symfony server (excellent choice), in this case you don't have to put the dowloaded repository in your root server folder, but after complete installation you will have to run the `$ php bin/console server:run` command.
3. Install the vendors : 
  * Download [composer](https://getcomposer.org/)
  * Put the composer.phar file into the root folder of the downloaded repository.
  * Then run `$ php composer.phar update`
  * Now all the vendors are installed.
4. Set the database :
  * In .env file customize the line :
  `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"`
  * You may also have to configure the config/packages/doctrine.yaml file for adjust your MySQL version.
  * Create the database `$ php bin/console doctrine:database:create`
  * Create all the tables 
    * `$ bin/console doctrine:migrations:diff`  
    * `$ bin/console doctrine:migrations:migrate`
5. Configure SwiftMailer
  * The application uses SwiftMailer to send username and password when an Admin create a new user account or edit an existing one. To test this, configure the .env file (Swiftmailer section) and the config/packages/dev/swiftmailer.yaml file. 
6. Optional :
  * Just after installation, you can fill the database with a set of data examples allready written in the Datafixtures folder. 
  * Fill the database with the data set example `$ bin/console doctrine:fixtures:load` press `y`.
  * You can also use `$ bin\console app:fixturesReload` command to achieve this. 
  
## 4-Tests coverage
You can generate an html code coverage file by running `$ ./vendor/bin/simple-phpunit --coverage-html public/test-coverage `.

Now the code coverage can be displayed on address "localhost:yourPort/test-coverage/index.html"
