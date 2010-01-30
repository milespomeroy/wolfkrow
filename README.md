# Software Development Project: The One Restaurant in Wolfkrow

## Introduction. 

In the town of Wolfkrow there is just one restaurant, called the Wolfkrow Diner, for its 10000 inhabitants. Like most restaurants, patrons of Wolfkrow Diner come into the restaurant and find a hostess who will seat them, order from a menu, eat, and leave. Unlike other restaurants, Wolfkrow Diner doesn't employ anyone but a manager for the thousands of meals served each day. A major difference between Wolfkrow Diner and normal restaurants is that patrons pay for each person that helps them individually. At the stage when a host greets the patron, the patron selects one of several hosts based on their hosting ability and their fee and pays right then. Next when a waiter seats the patron, the patron selects one of several waiters based on their ability and appearance, and they pay for the waiter to seat them. 

## Roles.

The roles can include: host/hostesses, waiters, cooks, food deliverers, and table cleaners. Feel free to add roles if you would like. For each role, patrons select from several competing providers of the service available at that stage in the workflow. Each provider lists qualifications and pricing information.

## Restaurant Manager. 

The manager's job at Wolfkrow Diner is vital. She examines the credentials of each provider for the positions of hostess, waiter, cook, food deliverer, and cashier to make sure they are qualified before they begin working the restaurant. She also constantly monitors the bottlenecks in the system on a dashboard. The dashboard tells her how much revenue is coming in, how many meal orders are in each stage of the workflow, and includes analytical and trend information. The restaurant has an unlimited size, and there is no need to allocate specific tables. The menu can be either fixed or dynamically driven off of the ability of the cooks. Either is fine. 
Ratings. Patrons provide ratings for each service provider in the restaurant. These ratings are available for the manager to see on the dashboard, as well as for other patrons to see when selecting their own service provider. If a service provider is performing poorly in the patrons' ratings, the manager can fire the service provider. 

## Revenue.

The manager attaches a fee to each provider for each of their transactions with patrons. The fee should be configurable for each workflow step. 

There has recently been a disaster in the town of Wolfkrow and they have lost their restaurant software. Your role is to recreate the restaurant for the patrons, service providers, and manager to interact. Do NOT build an autonomous simulation. 

## Project Evaluation Criteria 

Software modularity, database design, comments & documentation, creation of an environment where users of different types interact, speed to creation of a functioning product, intuitive and visually appealing interface, incorporation of javascript as appropriate, and thoroughness in keeping statistical and user behavior information for analysis. 

## Location. 

We assume that you have a sandbox somewhere on the web that you can use as your environment to dev/test.  If not, please dev/test using a localhost installation for your PHP/MySQL, and send us the code as a zip file along with a database dump with table structure descriptions. If you are indifferent to PHP frameworks, we prefer using the Zend Framework or Cake PHP. You do not have to use a framework at all if you so choose: evaluation does not depend on it. 

## Time Limit.

There is no time limit, but understand that expectations are proportional to time taken for completion. Please expect to receive feedback and needing to add features to your project after the initial submission. 

## Expectations.

We don't expect perfection, but we do want to see that in a few days you are capable of building the foundation for something useful for many users.  Not all features will be reasonable to build in the prototype. For features which you would build in a production system but are not worth the effort to prototype, please write down the issue and how you would account for it so we can see your approach. 

## Deliverable. 

A link to your online sandbox, login information for all applicable user types, a zip file containing your source code, and a SQL export with structure and data.

MY IMPLEMENTATION 
-----------------

Prototype for the Wolfkrow Restaurant Managment Software. Built using the CodeIgniter framework.

INSTALL
-------

- Set up database using db.sql.
- Copy and rename ./rms/application/config/database-example.php 
  to ./rms/application/config/database.php
- Edit the database configuration so it works on your server.
- Copy and rename ./rms/application/config/config-example.php 
  to ./rms/application/config/config.php
- Edit the site url to make you server site url.