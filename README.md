# Innkeeper 

The Innkeeper is a multi-tenancy package that takes advantage of multiple databases and converts a normal
application to a multi-tenant application without changing the application structure.

The package provides a fluent API to write your own Innkeeper administrative panel where all the tenants
must exist before they can use the application.

## Use Cases

This package is essential for the following scenarios.
* You have an awesome application that you want to offer as SAAS
* You want to offer the application as both on premise and hosted (as subdomain)
* You want to offer subscriptions for the application (future implementation)
* 


##usage

Of course to get started with this application, the first thing you need to do is install the package.
This can be achieved by:

````php
composer require tyondo/innkeeper
````
After installing the next thing you need to do is define the database connection that will be used for registering the tenants
details allowed to use your application. This can be achieved by running the command below
````php
php artisan innkeeper:setup:env
````

When the command is issued, it will add the following environmental variables

````php
#InnKeeper DB Connections
INNKEEPER_MAIN_DB_SERVER=mysql
INNKEEPER_TENANT_DB_SERVER=mysql

#InnKeeper Main SQL Connection
INNKEEPER_MAIN_DB_HOST=127.0.0.1
INNKEEPER_MAIN_DB_PORT=3306
INNKEEPER_MAIN_DB_DATABASE=innkeeper_main
INNKEEPER_MAIN_DB_USERNAME=root
INNKEEPER_MAIN_DB_PASSWORD=tyondo123
````
The assumption with the env variables is that your application is running on linux server with the database server being mysql.
Update the connection variables appropriately.

Once this is done, the next step is running the Innkeeper migration command to populate the new db.
You can achieve this through:

````php
php artisan innkeeper:setup:migrate
````

To quickly get a taste of the set-up, you can run a demo tenant command that will do the following
* Create a new tenant organization
* Create a fresh db for that organization
* Create an initial user for the new SAAS tenant

The command to achieve the above stated items is:

````php
 php artisan innkeeper:tenant:demo
````

## UI Options
UI options [Maze](https://github.com/zuramai/mazer)

Innkeeper comes equipped with an administration front-end to enable you manage your application tenants. I would like to
give a shout to the guys at [Ionic Design](https://github.com/iqonicdesignofficial/hope-ui-design-system) for the beautiful and responsive free bootstrap 5
admin UI as it powers Innkeeper.

To make use of this interface you first need to publish the assets to your application public dir. Use this command to set that up

````php
 php artisan innkeeper:publish:assets
````


After installing the package, you need to publish the package config file and modify it suit the environment
where your application currently sit. Out the box, the package supports mysql whether its installed natively
or though a server management solution Plesk (for now).

In addition to this you need to define a new route group in the HTTP kernel (this will be automated later).
This route will contain the middlewares essential for routing your users to various parts of the application

