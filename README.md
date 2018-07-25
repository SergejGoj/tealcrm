# TealCRM
TealCRM 1.0 running on CodeIgniter 3.x.  TealCRM is a customer relationship management (CRM) tool built on LAMP stack.

You can see a demo of TealCRM at <https://demo.tealcrm.com>.

Full project information is available at the website <https://www.tealcrm.com>.  You can find the roadmap information there as well to see where this project is going over the next little while.

# Help Wanted
If you like this project and might be interested in working on it, please reach out to me at derek@tealcrm.com.

# Reporting Issues
Please report issues through GitHub.  This is an open source volunteer driven project and issues will be addressed as we can get to them.

# Getting Started

## Introduction
Welcome to TealCRM.  This is a short guide on how to install Version 1.0 of TealCRM.  This application is a basic CRM system that was designed for small businesses.  It's a simple CRM but powerful enough for the most basic use cases.

## Some History
TealCRM was built in 2014 on CodeIgniter 2.x.  Originally built by my company, it was abandoned in 2017.  In 2018, we reached an agreement to release TealCRM was open source with some clean up.  I've stripped out a lot of the software-as-a-service features to make it stand alone.  It needs a lot of TLC admittedly after having 10+ different developers in the code over time.  My goal is to turn TealCRM into a niche small business CRM option for small businesses looking for a simple customer relationship management tool.

## Features
* Companies & People Tracking
* Deals Management
* Visual Sales Pipeline
* Tasks, Meetings, Calendar
* Note Tracking and Attachments
* CSV Data Import
* Basic customization of fields, list views
* Advanced Search Functionality
* User Management and basic role management
* Basic Dashboard

## System Requirements
TealCRM is designed for LAMP stack.  The following are required at a minimum:

* PHP 5.6 or higher
* MySQL 5.6 or higher
* Apache 2.0 or higher
* Linux-based system supporting LAMP
* We are aware of PHP 5.6 EOL for security patches.  We are waiting on CodeIgniter 4.x release.

## MIT License
TealCRM is released under the MIT License.  See the LICENSE file for more information.

### Other source code
TealCRM borrows from a number of open source projects.  Credit is given throughout the code where applicable.  In the future we hope to clean this up and give proper credit through a dedicated page.

## Disclaimer
TealCRM is a hot mess right now, at least from a developer stand point.  It's a completely functional CRM for the every day business user but behind the scenes we've got a lot of work to do.  I'm up to the challenge and I'm excited to see where we can take this project.

## Installation
TealCRM 1.0 does not have an auto installer so there are a few steps that need to be taken to get started.

1. Download a ZIP file from GitHub.
2. Unzip file into a directory on your server.  It's recommended that all folders and files except for the **html** directory are located outside of the web root directory.
3. Ensure that your **attachments** folder is writable by the server so that uploads can be placed here.
4. Point your web root to the **html** directory.
5. Upload the **blank_teal.sql** file into MySQL.
6. Follow database configuration steps below.
7. Go to **/files_directory/application/config/config.php** and update your **base_url** to your specific site.
8. Navigate to your web root.

### Database Configuration
All database configurations are found inside of **/files_directory/application/config/database.php**.

You must set the following:

* username
* password
* database name

There are other options for configuration as needed depending on your installation.

## Logging In
You can login by visiting the web root 
The default login info is as follows:

username: admin
password: tealcrm123

It is highly recommended that you change this ASAP.
