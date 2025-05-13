<?php
/**
 * Plugin Name: Custom Employee Management System
 * Plugin URI: https://yourwebsite.com/employee-management-system
 * Description: A comprehensive system for managing employees, attendance, and payroll.
 * Version: 2.0.0
 * Author: Ashish Mishra
 * Author URI: https://yourwebsite.com
 * Text Domain: employee-management-system
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EMS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EMS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EMS_PLUGIN_VERSION', '2.0.0');

// Include required files
require_once EMS_PLUGIN_DIR . 'includes/class-ems-loader.php';

    // Initialize plugin and hook actions
    function ems_initialize_plugin() {
        $loader = new EMS_Loader();
        $loader->run();
        
        // Register activation hook
        register_activation_hook(__FILE__, 'ems_activate');
        
        // Register deactivation hook
        register_deactivation_hook(__FILE__, 'ems_deactivate');
        
        // Add settings link to the plugins page
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ems_add_settings_link');
        
        // Create pages on plugin activation
        add_action('admin_init', 'ems_create_pages');
        
    }
    add_action('plugins_loaded', 'ems_initialize_plugin');



// Activation hook
function ems_activate() {
    // Create necessary database tables
    require_once EMS_PLUGIN_DIR . 'includes/class-ems-activator.php';
    EMS_Activator::activate();
}

// Deactivation hook
function ems_deactivate() {
    // Clean up if needed
    require_once EMS_PLUGIN_DIR . 'includes/class-ems-deactivator.php';
    EMS_Deactivator::deactivate();
}

// Add settings link to plugins page
function ems_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=employee-management-settings">' . __('Settings', 'employee-management-system') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Create pages for frontend
function ems_create_pages() {
    // Only run once
    if (get_option('ems_pages_created')) {
        return;
    }
    
    // Create Login page
    $login_page_id = wp_insert_post(array(
        'post_title' => __('Employee Login', 'employee-management-system'),
        'post_content' => '[ems_login]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));

    $show_emp_page_id = wp_insert_post(array(
        'post_title' => __('Show Employee', 'employee-management-system'),
        'post_content' => '[ems_show]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));

    $add_emp_page_id = wp_insert_post(array(
        'post_title' => __('Add Employee', 'employee-management-system'),
        'post_content' => '[ems_add_emp]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Dashboard page
    $dashboard_page_id = wp_insert_post(array(
        'post_title' => __('Employee Dashboard', 'employee-management-system'),
        'post_content' => '[ems_dashboard]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Profile page
    $profile_page_id = wp_insert_post(array(
        'post_title' => __('My Profile', 'employee-management-system'),
        'post_content' => '[ems_profile]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Attendance page
    $attendance_page_id = wp_insert_post(array(
        'post_title' => __('My Attendance', 'employee-management-system'),
        'post_content' => '[ems_attendance]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Salary page
    $salary_page_id = wp_insert_post(array(
        'post_title' => __('My Salary', 'employee-management-system'),
        'post_content' => '[ems_salary]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Admin Dashboard page
    $admin_dashboard_page_id = wp_insert_post(array(
        'post_title' => __('Admin Dashboard', 'employee-management-system'),
        'post_content' => '[ems_admin_dashboard]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Admin Employees page
    $admin_employees_page_id = wp_insert_post(array(
        'post_title' => __('Manage Employees', 'employee-management-system'),
        'post_content' => '[ems_admin_employees]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Admin Attendance page
    $admin_attendance_page_id = wp_insert_post(array(
        'post_title' => __('Manage Attendance', 'employee-management-system'),
        'post_content' => '[ems_admin_attendance]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Admin Salary page
    $admin_salary_page_id = wp_insert_post(array(
        'post_title' => __('Manage Salary', 'employee-management-system'),
        'post_content' => '[ems_admin_salary]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Create Employee Registration page
    $employee_registration_page_id = wp_insert_post(array(
        'post_title' => __('Employee Registration', 'employee-management-system'),
        'post_content' => '[ems_employee_registration]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template'  => 'template-fullwidth.php' // <- Add this line
    ));
    
    // Save page IDs in options
    update_option('ems_add_emp_page_id', $add_emp_page_id);
    update_option('ems_show_emp_page_id', $show_emp_page_id);
    update_option('ems_login_page_id', $login_page_id);
    update_option('ems_dashboard_page_id', $dashboard_page_id);
    update_option('ems_profile_page_id', $profile_page_id);
    update_option('ems_attendance_page_id', $attendance_page_id);
    update_option('ems_salary_page_id', $salary_page_id);
    update_option('ems_admin_dashboard_page_id', $admin_dashboard_page_id);
    update_option('ems_admin_employees_page_id', $admin_employees_page_id);
    update_option('ems_admin_attendance_page_id', $admin_attendance_page_id);
    update_option('ems_admin_salary_page_id', $admin_salary_page_id);
    update_option('ems_employee_registration_page_id', $employee_registration_page_id);
    
    // Mark as created
    update_option('ems_pages_created', true);
}