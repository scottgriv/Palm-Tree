# Palm Tree
![Logo](https://imgur.com/J7ApAiV.png)
#

Palm Tree is a web based CRM application with Google Business Review email request capabilities.

 * Keep track of your Customers in a digital format - ditch the pen and paper!
 * Send marketing emails out, including emails requesting Google Business Reviews!
    * Directly open a Review window on your Google Business page with a click of a button within the email.

## Fully Interactive Table

![App Screenshot](https://imgur.com/ec4MeEY.jpg)

*Note: Data privacy is important to me, which is why the above data is fake data.*

* **CRUD-enabled:**
    * Create Customers
    * Update Customers
    * Delete Customers
* Export table to a .csv file.
* Scroll up, down, and refresh the table easily using the provided buttons.
* Highlighted editable fields.
* Log Customer Notes.
* Sort Customers by:
    * First Name
    * Last Name
    * Email
    * Phone
    * Created Date
* Search for Customers based on:
    * First Name
    * Last Name
    * Email
    * Phone
* Check the **Send Email** box to send an email to the Customer.
* Mobile capabilties with Bootstrap.

## Custom Email Template

![App Screenshot](https://imgur.com/BifS2gK.jpg)

* Configure your Email Server to send Business emails using your own plain-text or HTML email template (Gmail Supported).
* Use variables from a predefined list to send curated emails to your Customers in the **Subject** and **Body**:

    ``Hello {{ customer_first_name }} {{ customer_last_name }}!``

    will translate to:

    ``Hello John Smith!``

* **CC** & **BCC** capabilties.
* Send a mass email to **ALL** of your customers at once.

## Configure Your Business

![App Screenshot](https://imgur.com/2TbykTe.jpg)

* Upload and display your Company Logo.
* Display your Company Title.
* Display your Company Description.
* Add contact information to your emails using the pre-defined variables in your email template:
    * Company Owner
    * Company Address
    * Company Contact Phone
    * Company Contact Email
    * Google Place ID
        * Used to automatically open up Google Reviews for your business using the provided email template.
    * Social Media Email Hyperlinks:
        * Google, Facebook, Twitter, LinkedIn, Instagram, YouTube, Amazon, Pinterest, Etsy, and Shopify.

## Installation

Install with **Homebrew**:

```bash
  brew tap scottgriv/palm-tree https://github.com/scottgriv/Palm-Tree
```

## Built using: 
* PHP v8.1.6
* jQuery v2.1.3 
* Bootstrap v3.3.5
* MariaDB v10.4.21
* XAMPP v8.1.6

## Dependencies: 

* [jQuery-Tabledit v1.2.3](https://github.com/markcell/jquery-tabledit)
* [PHPMailer v6.7.1](https://github.com/PHPMailer/PHPMailer/tree/5.2-stable)

## Quick Start

1. I'd recommend using the XAMPP stack to run this application (although, its not required).
    * Download the latest version of **XAMPP** [here](https://www.apachefriends.org/download.html).
    * XAMPP comes a Apache HTTP Server, PHP, and a MariaDB database stack already installed - everything you need to get Palm Tree up and running.
2. Start the XAMPP MySQL and Apache Web Server Services.
3. Import the provided database shell located in `Palm-Tree/sql/palm_tree.sql` into your MySQL database.
4. Place Palm-Tree in the htdocs folder and reach it using your machines IP address i.e. `http://127.0.0.1/Palm-Tree` or ``http://localhost/Palm-Tree``.
5. Upload the provided email templates in ``Palm-Tree/templates`` in the *Email Template* menu (adjust it according to your needs or create your own).
6. Configure your Email Template and Business.
7. Add Customers.

* You're ready to go!  
