# birthday-cake-scheduler


This project is about displaying a developer's birthday cake listing, like on a particular date there will be a celebration with a small or large cake and a list of developer names for whom that cake day is celebrating

#### <em>Prerequisite : php version(^8.0 or greater), Install MariaDB, Install composer, Install npm
________________________

## This document covered below points :
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Testing](#testing)
- [Execution Description](#execution-description)
- [API](#api)

## Installation

To get started with this project, you'll need to install the necessary dependencies. Here's how to set up the project locally:

1. **Clone the Repository:**
   ````
   git clone https://github.com/amit9288-panchal/birthday-cake-scheduler.git
   cd birthday-cake-scheduler
   ```` 

2. **Install Composer Dependencies:**
   ````
   composer install
   ````

3. ** Install NPM Dependencies:**
   ````
   npm install
   ````

4. **Create the .env File: Copy the .env.example file to .env:**
    ````
    cp .env.example .env
    cp .env.example.testing .env.testing (For testing environment)
    ````

5. **Generate the Application Key :** Generate an application key and configured in .env [APP_KEY]
    ````
   php artisan key:generate
   ````

6. ** Set Up Your Database:** Create a database for application and update your .env file with your database credentials
   #You can create a separate testing database and configure in .env.testing
   ````
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ````

7. **Migrate the Database :** Please execute migration
   ````
   php artisan migrate
   ````

## Configuration
1. .env configuration is required
2.  Schedule command is created which will execute on every day 1:00 AM

    *Configured in Kernel :*
    ````
    $schedule->command('calculate:dayCake')->dailyAt('01:00'); 
    ````
    *Command :*
    ````
    php artisan calculate:dayCake
    ````
## Running the Application
   ````
    php artisan serve
   ````

## Testing
1. **Configure your testing database to phpunit.xml (if you are not using separate environment file for testing .env.testing) :**
   ![img_1.png](storage/app/public/execution_images/img_1.png)
2. **Execute Test Command :**
    ````
    php artisan test
    ````
   ![img_2.png](storage/app/public/execution_images/img_2.png)

## Execution Description :
1. **Home Page : Loged Out Case**

   ![img_3.png](storage/app/public/execution_images/img_3.png)
    * It will display upcoming date including today's small or large cake will arrive and list of developers.
    * If an admin user is longed in it allow to upload a developer birthday list, based on developer entry daily schedule command executed at 1:00 AM and generate cake event and calculate


1. **Home Page : Logged In Case**
   ![img_4.png](storage/app/public/execution_images/img_4.png)
   ![img_5.png](storage/app/public/execution_images/img_5.png)
   ![img_6.png](storage/app/public/execution_images/img_6.png)

2. **Once developer birthday list uploaded if you want you can manually execute command to generate cake event**
    ````
    php artisan calculate:dayCake
    ````
3. **Login Screen :**   
   ![img_7.png](storage/app/public/execution_images/img_7.png)

4. **Register Screen:**
   ![img_8.png](storage/app/public/execution_images/img_8.png)

## API:

**To generate API swagger, please execute below command to generate api doc (Each apis are authenticated with token):**
````
php artisan l5-swagger:generate
````
![img_9.png](storage/app/public/execution_images/img_9.png)
