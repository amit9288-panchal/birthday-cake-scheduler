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

3. **Install NPM Dependencies:**
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

6. **Set Up Your Database:** Create a database for application and update your .env file with your database credentials
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
  
   ![img_1](https://github.com/user-attachments/assets/6e66cb81-3688-42ea-be46-ddfd537e1aa0)

3. **Execute Test Command :**

    ````
    php artisan test
    ````
   <img width="1506" alt="img_2" src="https://github.com/user-attachments/assets/411d0c58-118a-4a3c-a09b-a9be76d7b208" />


## Execution Description :
1. **Home Page : Loged Out Case**

   <img width="1189" alt="img_3" src="https://github.com/user-attachments/assets/dead10bd-9a94-47cf-a917-87e1304fb44f" />

    * It will display upcoming date including today's small or large cake will arrive and list of developers.
    * If an admin user is longed in it allow to upload a developer birthday list, based on developer entry daily schedule command executed at 1:00 AM and generate cake event and calculate


1. **Home Page : Logged In Case**
   <img width="1199" alt="img_4" src="https://github.com/user-attachments/assets/3d1c73a9-751a-4498-847f-e175dd26cac2" />
   <img width="1197" alt="img_5" src="https://github.com/user-attachments/assets/7d5cfccb-9654-46b9-a1a3-60cecb189717" />
   <img width="1198" alt="img_6" src="https://github.com/user-attachments/assets/b3545e85-69c8-465d-9d59-8182f08e1e8f" />

2. **Once developer birthday list uploaded if you want you can manually execute command to generate cake event**
    ````
    php artisan calculate:dayCake
    ````
3. **Login Screen :**   
   <img width="1198" alt="img_7" src="https://github.com/user-attachments/assets/130a883e-9ce7-402d-9833-d927e9026292" />

4. **Register Screen:**
   <img width="1201" alt="img_8" src="https://github.com/user-attachments/assets/f66ff8db-08bb-467c-9631-23a8cbeb61c1" />

## API:

**To generate API swagger, please execute below command to generate api doc (Each apis are authenticated with token):**
````
php artisan l5-swagger:generate
````
<img width="1511" alt="img_9" src="https://github.com/user-attachments/assets/12b702bd-a74a-4043-85e9-7d329dcea8dd" />

