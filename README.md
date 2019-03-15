## Symfony Simple Api Example for Products and Customers and Crud Controllers access to this entities

For serialize/deserialize using JMS Serialization Bundle, but you may use something other

## Installation:

- git clone https://github.com/antonkuleshov/symfony-simple-api.git

- go to project directory and run: composer install

- create .env.local file and override MySQL Connection string: DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

- run following commands to create database using Doctrine: 
    - php bin/console doctrine:database:create (to create database called `db_name`, it will figure out db name based on your DATABASE_URL config)		
    - php bin/console doctrine:schema:update --force (executes queries to create/update all Entities in the database in accordance to latest code)

- for create fixtures data run following commands:
    - php bin/console doctrine:fixtures:load