```
/YomaliTracker/
├── src/   #project repository folder
├── nginx/
├── php/
├── docker-compose.yml
├── nginx.dockerfile
├── php.dockerfile
```
# Setup Instructions

--------------------------------------------------------------------

### Adjust .env file
```
    Copy the '.env.example' file to '.env' and update the environment variables if it`s necessary
``` 


### Start the Docker container in the background with command:
```
    docker-compose up -d
``` 

### Open Docker container terminal:
```
     docker exec -it php sh
```

#### Run one by one:
### Update project dependencies using:
```
     composer update
```

### Run project default seeders (Admin and one Plugin, that used by default example pages):
```
     php artisan db:seed
```

--------------------------------------------------------------------

# Overview

### The system consists of:

1. JavaScript tracker to be embedded in client websites.
2. Server-side application built with PHP and MySQL for data storage.
3. Admin interface to manage plugins and view statistics.

## Components

### Monitoring Page
This page already includes a built-in monitoring script and serves as an example of how the tracker works.

![Main Page](/readme-imgs/main-page.png)

--------------------------------------------------------------------

### Admin Login Page

Click on `Admin Login` or go to `/admin/login` to log in as an admin. After logging in, you are able to manage plugins and view analytics.
If you have already seeded the database, you can use the default admin credentials from the .env file.

![Admin Login](/readme-imgs/admin-login.png)

--------------------------------------------------------------------

### Plugins Page
List of all created tracking plugins.
Provides an opportunity to create, delete and edit plugins.
![Plugins Page](/readme-imgs/plugins.png)

#### Plugin Page
Each plugin has its own dedicated page with two tabs:
1. Statistics 
2. Details

##### Statistics Tab
![Plugin Statistic](/readme-imgs/plugin-statistic-tab.png)
Provides an opportunity to view statistics of visits and unique visits by defaults periods:
* Day
* Week 
* Month 
* All Time 
* Custom Period (select manually)

Below, you can also view a detailed log of all visits, including search and sorting functionality.
![Tracking Info](/readme-imgs/plugin-tracking-info.png)

##### Details Tab
Allows editing plugin parameters such as:
* Name
* Host – domain where tracking will be used.
* Period – defines the minimum time gap between recorded visits (e.g., 00:00:30 = 30 seconds, 12:00:00 = 12 hours).
* Identifier – must be unique.

![Tracking Info](/readme-imgs/plugin-details-tab.png)

--------------------------------------------------------------------
### Installing the Tracker

##### To install the tracker, copy the provided script and insert it into the target webpage.

![Script Installation Example](/readme-imgs/scipt-installation-example.png)

##### Testing 
1. Set the plugin period to 10 seconds.
2. Open different pages (e.g., `/example`, `/test`, `/etc`) from
   * The same browser
   * Different browsers