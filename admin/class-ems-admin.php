<?php
/**
 * The admin-specific functionality of the plugin.
 */
class EMS_Admin {
    /**
     * Display the dashboard page.
     */
    public static function display_dashboard() {
        // Get counts for dashboard
        $employee_count = self::get_employee_count();
        $present_today = self::get_present_today_count();
        $absent_today = self::get_absent_today_count();
        $pending_salary = self::get_pending_salary_count();
        
        // Include the dashboard view
        include EMS_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    /**
     * Display the settings page.
     */
    public static function display_settings() {
        // Process settings form submission
        self::process_settings();
        
        // Get current settings
        $settings = get_option('ems_settings', array(
            'company_name' => get_bloginfo('name'),
            'company_address' => '',
            'company_email' => get_bloginfo('admin_email'),
            'company_phone' => '',
            'company_logo' => '',
            'working_days' => array('1', '2', '3', '4', '5'), // Monday to Friday by default
            'working_hours' => '8',
            'currency_symbol' => '$',
            'date_format' => 'Y-m-d',
            'enable_email_notifications' => '0'
        ));
        
        // Include the settings view
        include EMS_PLUGIN_DIR . 'admin/views/settings.php';
    }
    
    /**
     * Process settings form submission.
     */
    private static function process_settings() {
        // Check for nonce
        if (isset($_POST['ems_settings_nonce']) && wp_verify_nonce($_POST['ems_settings_nonce'], 'ems_settings_action')) {
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'save_settings') {
                $settings = array(
                    'company_name' => sanitize_text_field($_POST['company_name']),
                    'company_address' => sanitize_textarea_field($_POST['company_address']),
                    'company_email' => sanitize_email($_POST['company_email']),
                    'company_phone' => sanitize_text_field($_POST['company_phone']),
                    'company_logo' => esc_url_raw($_POST['company_logo']),
                    'working_days' => isset($_POST['working_days']) ? array_map('sanitize_text_field', $_POST['working_days']) : array(),
                    'working_hours' => sanitize_text_field($_POST['working_hours']),
                    'currency_symbol' => sanitize_text_field($_POST['currency_symbol']),
                    'date_format' => sanitize_text_field($_POST['date_format']),
                    'enable_email_notifications' => isset($_POST['enable_email_notifications']) ? '1' : '0'
                );
                
                // Save settings
                update_option('ems_settings', $settings);
                
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-success is-dismissible"><p>' . 
                        __('Settings saved successfully.', 'employee-management-system') . 
                        '</p></div>';
                });
            }
        }
    }
    
    /**
     * Get the total number of employees.
     */
    private static function get_employee_count() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_employees';
        
        return $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }
    
    /**
     * Get the number of employees present today.
     */
    private static function get_present_today_count() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_attendance';
        $today = date('Y-m-d');
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE date = %s AND status = 'present'",
            $today
        ));
    }
    
    /**
     * Get the number of employees absent today.
     */
    private static function get_absent_today_count() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_attendance';
        $today = date('Y-m-d');
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE date = %s AND status = 'absent'",
            $today
        ));
    }
    
    /**
     * Get the number of pending salary payments.
     */
    private static function get_pending_salary_count() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_salary';
        $current_month = date('n');
        $current_year = date('Y');
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE month = %d AND year = %d AND payment_status = 'pending'",
            $current_month, $current_year
        ));
    }
    
    /**
     * Register AJAX handlers.
     */
    public static function register_ajax_handlers() {
        // Employee AJAX handlers
        add_action('wp_ajax_ems_get_employee', array('EMS_Admin', 'ajax_get_employee'));
        
        // Salary AJAX handlers
        add_action('wp_ajax_ems_get_salary', array('EMS_Admin', 'ajax_get_salary'));
        add_action('wp_ajax_ems_get_payslip', array('EMS_Admin', 'ajax_get_payslip'));
    }
    
    /**
     * AJAX handler to get employee data.
     */
    public static function ajax_get_employee() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_ajax_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : 0;
        
        if (!$employee_id) {
            wp_send_json_error(__('Invalid employee ID.', 'employee-management-system'));
        }
        
        $employee = EMS_Employee::get_employee($employee_id);
        
        if ($employee) {
            wp_send_json_success($employee);
        } else {
            wp_send_json_error(__('Employee not found.', 'employee-management-system'));
        }
    }
    
    /**
     * AJAX handler to get salary data.
     */
    public static function ajax_get_salary() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_ajax_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        $salary_id = isset($_POST['salary_id']) ? intval($_POST['salary_id']) : 0;
        
        if (!$salary_id) {
            wp_send_json_error(__('Invalid salary ID.', 'employee-management-system'));
        }
        
        $salary = EMS_Salary::get_salary_record($salary_id);
        
        if ($salary) {
            // Get employee name
            $employee = EMS_Employee::get_employee($salary->employee_id);
            $salary->employee_name = $employee ? $employee->name : '';
            
            wp_send_json_success($salary);
        } else {
            wp_send_json_error(__('Salary record not found.', 'employee-management-system'));
        }
    }
    
    /**
     * AJAX handler to get payslip HTML.
     */
    public static function ajax_get_payslip() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_ajax_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        $salary_id = isset($_POST['salary_id']) ? intval($_POST['salary_id']) : 0;
        
        if (!$salary_id) {
            wp_send_json_error(__('Invalid salary ID.', 'employee-management-system'));
        }
        
        $salary = EMS_Salary::get_salary_record($salary_id);
        
        if (!$salary) {
            wp_send_json_error(__('Salary record not found.', 'employee-management-system'));
        }
        
        // Get employee details
        $employee = EMS_Employee::get_employee($salary->employee_id);
        
        if (!$employee) {
            wp_send_json_error(__('Employee not found.', 'employee-management-system'));
        }
        
        // Get settings
        $settings = get_option('ems_settings', array(
            'company_name' => get_bloginfo('name'),
            'company_address' => '',
            'company_email' => get_bloginfo('admin_email'),
            'company_phone' => '',
            'currency_symbol' => '$'
        ));
        
        // Get month name
        $month_name = date_i18n('F', mktime(0, 0, 0, $salary->month, 1));
        
        // Generate payslip HTML
        ob_start();
        include EMS_PLUGIN_DIR . 'admin/views/payslip.php';
        $payslip_html = ob_get_clean();
        
        wp_send_json_success($payslip_html);
    }
}

// Register AJAX handlers
add_action('init', array('EMS_Admin', 'register_ajax_handlers'));