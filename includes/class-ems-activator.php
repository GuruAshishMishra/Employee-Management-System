<?php
/**
 * Fired during plugin activation.
 */
class EMS_Activator {
    /**
     * Create the necessary database tables.
     */
    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Employees table
        $table_employees = $wpdb->prefix . 'ems_employees';
        $sql_employees = "CREATE TABLE $table_employees (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            profile_picture varchar(255),
            joining_date date NOT NULL,
            job_title varchar(100) NOT NULL,
            company_name varchar(100) NOT NULL,
            address text,
            salary_type enum('fixed', 'hourly', 'monthly') DEFAULT 'monthly',
            salary_amount decimal(10,2) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY email (email)
        ) $charset_collate;";
        
        // Attendance table
        $table_attendance = $wpdb->prefix . 'ems_attendance';
        $sql_attendance = "CREATE TABLE $table_attendance (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            employee_id mediumint(9) NOT NULL,
            date date NOT NULL,
            status enum('present', 'absent', 'half_day', 'leave') DEFAULT 'present',
            notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY employee_date (employee_id, date)
        ) $charset_collate;";
        
        // Salary table
        $table_salary = $wpdb->prefix . 'ems_salary';
        $sql_salary = "CREATE TABLE $table_salary (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            employee_id mediumint(9) NOT NULL,
            month int(2) NOT NULL,
            year int(4) NOT NULL,
            base_salary decimal(10,2) NOT NULL,
            bonus decimal(10,2) DEFAULT 0,
            deduction decimal(10,2) DEFAULT 0,
            total_salary decimal(10,2) NOT NULL,
            payment_status enum('pending', 'paid') DEFAULT 'pending',
            payment_date date,
            notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY employee_month_year (employee_id, month, year)
        ) $charset_collate;";
        
        // User roles table
        $table_roles = $wpdb->prefix . 'ems_user_roles';
        $sql_roles = "CREATE TABLE $table_roles (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            role enum('admin', 'hr_manager', 'viewer') DEFAULT 'viewer',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_employees);
        dbDelta($sql_attendance);
        dbDelta($sql_salary);
        dbDelta($sql_roles);
        
        // Initialize roles
        EMS_Roles::initialize_roles();
    }
}