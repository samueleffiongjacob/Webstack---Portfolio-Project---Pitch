# To set up Laravel with MySQL on a Linux system (such as Ubuntu), follow these step-by-step instructions

---

## 1. **Update Your System**

Ensure your system is updated to avoid dependency issues.

```bash
sudo apt update && sudo apt upgrade -y
```

---

## 2. **Install PHP and Required Extensions**

Laravel requires PHP (8.0 or later) and some extensions.

```bash
sudo apt install php-cli php-mbstring php-xml php-bcmath php-curl php-zip php-mysql unzip composer -y
```

---

## 3. **Install MySQL**

Install and secure MySQL if it’s not already installed.

```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

- During the setup, you'll be prompted to:
  - Set a root password.
  - Remove anonymous users.
  - Disallow root login remotely.
  - Remove test databases.
  - Reload privilege tables.

---

## 4. **Set Up a MySQL Database**

Log in to MySQL and create a database for your Laravel project.

```bash
sudo mysql -u root -p
```

Then run the following SQL commands:

```sql
CREATE DATABASE laravel_db;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON laravel_db.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 5. **Install Composer**

If Composer is not already installed, download and set it up globally.

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Test the installation:

```bash
composer --version
```

---

## 6 . **Set Up Environment Variables**

Navigate to the Laravel project folder:

```bash
cd laravel_project
```

Modify the `.env` file to include your MySQL database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=your_password
```

---

## 7. **Run Database Migrations**

 project has migrations, run them to set up the database schema:

```bash
php artisan migrate
```

---

### 8. **Serve the Laravel Application**

Start the Laravel development server:

```bash
php artisan serve
```

Access the application in your browser at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 11. **(Optional) Configure File Permissions**

If you're using Linux, you may need to set write permissions for `storage` and `bootstrap/cache`:

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

With these steps, Laravel and MySQL should be set up and ready to go! Let me know if you run into any issues email: ![Samuel effiong](samueleffiongjacob@gmail.com). 😊

---

## 12. **Testing the Setup**

- Get endpoint:

  - [TEST SERVER IF IT UP](http://127.0.0.1:8000/up)
  - [WALLETS](http://127.0.0.1:8000/api/v1/wallets)
  - [ALL WALLETS](http://127.0.0.1:8000/api/v1/Allwallets)
  - [GET SPECIFIC WALLETS](http://127.0.0.1:8000/api/v1/wallets/{ID})
  - [GET ALL USERS](http://127.0.0.1:8000/api/v1/users)
  - [GET SPECIFIC USERS](http://127.0.0.1:8000/api/v1/users/{ID})
  - [GET SPECIFIC USERS AND WALLETS](http://127.0.0.1:8000/api/v1/users/{ID}/wallets)

- POST endpoint
  - [POST A USER](http://127.0.0.1:8000/api/v1/users)
  - [CREATE A WALLET](http://127.0.0.1:8000/api/v1/wallets)
  - [SEND MONEY](http://127.0.0.1:8000/api/v1/transactions)
NOTE: ALL ID : ARE INT: eg 1 base on how many users created repleace th {ID} -> 1 or any number base on how many user created

### POST A NEW USER

{
  "name": "Samuel Effiong",
  "email": "<samm@example.com>",
  "password": "password123"
}

### HOW TO CREATE A WALLET

{
  "user_id": 3,
  "wallet_type_id":3,
  "name":"Samuel Effiong",
  "email": "<samm@example.com>",
  "wallet_type": "Premium Current",
  "balance":0

}

### how to send money

{
  "sender_wallet_id": 1,
  "receiver_wallet_id": 1,
  "amount": 650
}

---
