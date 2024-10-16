# **StockMaster**

![License](https://img.shields.io/badge/license-MIT-blue.svg)

## Description

**StockMaster** is a logistics and stock management solution integrated with clients' accounting and invoicing systems. The application is designed to allow stock control and traceability, with features for pallet storage management, stay calculation, loading/unloading operations, and the issuance of monthly invoices. The system will be used both by administrators and warehouse operators, with a mobile-optimized interface for logistic operations. The project also includes a chat function for sending and receiving messages and files in real time, as well as a notification system to facilitate non-verbal communication between administrators and warehouse operators.

## Requirements

Before installing, ensure you have the following requirements:

- [PHP](https://www.php.net/) 8.1.2 or higher
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)
- [Node.js](https://nodejs.org/) (for compiling front-end assets)
- [NPM](https://www.npmjs.com/) (for front-end package management)
- [Pusher](https://pusher.com/) account (for real-time chat)

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository**

    ```sh
    git clone https://github.com/TiagoMurtinho/stockmaster.git
    ```

2. **Navigate to the project directory**

    ```sh
    cd stockmaster
    ```

3. **Install PHP dependencies**

    ```sh
    composer install
    ```

4. **Create a copy of the environment configuration file**

    ```sh
    cp .env.example .env
    ```

5. **Configure your database**

   Edit the `.env` file to set up your database credentials and other environment variables.


   
6. **Set up Pusher for real-time chat**

    - Create an account on [Pusher](https://pusher.com/).
    - Create a new Pusher app and obtain your credentials (App ID, Key, Secret, and Cluster).
    - Add your Pusher credentials to the `.env` file:
    
    ```plaintext
    PUSHER_APP_ID=your-pusher-app-id
    PUSHER_APP_KEY=your-pusher-key
    PUSHER_APP_SECRET=your-pusher-secret
    PUSHER_APP_CLUSTER=your-pusher-cluster
    ```

7. **Generate the application key**

    ```sh
    php artisan key:generate
    ```

8. **Run the migrations**

    ```sh
    php artisan migrate
    ```

 9. **Run the seeders to create an admin account**

    After running the migrations, you can seed the database to create a default administrator account:

    ```sh
    php artisan db:seed
    ```

    This will create a default admin user with the following credentials:

    - **Email:** admin@example.com
    - **Password:** password

    Be sure to change these credentials after logging in for the first time for security reasons.

10. **Compile the assets**

    ```sh
    npm install
    npm run dev
    npm run build
    ```

11. **Start the server**

    ```sh
    php artisan serve
    ```

    The application will be available at [http://localhost:8000](http://localhost:8000).

## Usage

Once the application is running, you can use it to manage logistics and stock operations.

### Stock Management

- Track and manage stock levels with real-time updates.
- Use the mobile-optimized interface for efficient warehouse operations like loading, unloading, and pallet management.

### Invoicing

- Issue monthly invoices based on stock movements and storage calculations.

### Chat and Notifications

- Use the real-time chat to communicate with team members and send/receive files.
- Receive system notifications to stay informed about important updates, such as stock alerts or operation tasks.

### Managing User Accounts

As an administrator, you are responsible for creating accounts for the warehouse operators. To create an account for an operator:

1. **Navigate to the Admin Panel**: Log in as the admin and access the admin panel where user management features are located.
   
2. **Create Operator Accounts**: 
   - Enter the operator's email address in the designated field.
   - Set the operator's **NIF** (Tax Identification Number) as the password manually.

3. **Manage Operators**: 
   - Once accounts are created, you can view, update, or deactivate operator accounts as needed.

It is recommended that operators change their passwords after the first login for security reasons. As an admin, you can also reset operator passwords if necessary.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

If you have any questions or suggestions, feel free to reach out:

- **Name:** Tiago Murtinho
- **Email:** tiago_miguelmurtinho@hotmail.com
- **LinkedIn:** [Tiago Murtinho](https://www.linkedin.com/in/tiago-murtinho/)
