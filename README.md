# 🖥️ Tracker: Admin Logistics Dashboard

The Tracker Admin Logistics Dashboard is the centralized management interface for the entire fleet, built using Laravel and Blade. It provides administrators and logistics managers with real-time oversight of all operational assets, order fulfillment, and historical trip data.

## 🌟 Core Features

This dashboard serves as the command center for fleet management:

### 1. Real-Time Fleet Tracking

- **Live Map View**: Displays the current, real-time location of all active drivers and vehicles on an interactive map (e.g., using Leaflet or Google Maps integration).
- **Status Indicators**: Quick visual status of each driver (Online, Offline, On Trip, Idle).
- **Historical Trace**: Ability to select a date and driver to view the exact path taken throughout their day.

### 2. Order and Trip Management

- **Order Synchronization**: Automatically pulls new order data from the Customer App database, displaying pickup and delivery points.
- **Trip Assignment**: Admins can view unassigned orders and manually assign them to available drivers, updating the driver app's manifest.
- **Fulfillment Monitoring**: Track the progress of orders against their expected timelines (Pending, In Transit, Delivered, Canceled).

### 3. Reporting and Analytics

- **Daily Route Review**: Comprehensive view of the distance traveled, duration, and stop points for any given driver on any day.
- **Offline Data Visibility**: Clearly identifies trips that included segments logged in offline mode, and displays the synchronized data points.
- **Performance Metrics**: Generate reports on driver efficiency, adherence to planned routes, and average delivery times.

## 🏗 Technology Stack

- **Backend Framework**: Laravel (PHP)
- **Frontend Templating**: Blade (with minor JavaScript for interactivity)
- **Database**: MySQL / PostgreSQL
- **Real-time Communication**: Pusher, Laravel Echo, or WebSockets for instant location updates.
- **Mapping**: Integrated via API (e.g., Google Maps API or OpenStreetMap/Leaflet).

## ⚙️ Setup and Installation

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js & npm (for asset compilation)
- Database (MySQL recommended)

### Installation Steps

1. Clone the repository:

   ```
   git clone https://github.com/your-org/tracker-admin-dashboard.git
   cd tracker-admin-dashboard
   ```

2. Install PHP Dependencies:

   ```
   composer install
   ```

3. Configure Environment:

   ```
   cp .env.example .env
   php artisan key:generate
   ```

   Edit the newly created .env file to configure your database connection (DB_*), mapping API keys (MAPS_API_KEY), and WebSocket/Pusher credentials (PUSHER_* or similar).

4. Database Migration:  
   Run migrations and seed the database with initial admin users (optional).

   ```
   php artisan migrate --seed
   ```

5. Install Frontend Dependencies & Compile Assets:

   ```
   npm install
   npm run dev  # or npm run production
   ```

6. Run the Application:  
   Start the local development server.

   ```
   php artisan serve
   ```

   The dashboard will be accessible at http://127.0.0.1:8000.

## 🔒 Security & Roles

The application uses Laravel's built-in authentication and Laravel Passport/Sanctum for API authentication, ensuring secure interaction with the Driver App's location relay endpoints. Access is restricted via role-based access control (RBAC) to ensure only authorized administrators can modify order assignments and access sensitive reporting data.
