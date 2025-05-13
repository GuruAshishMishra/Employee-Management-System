<?php
/**
 * Frontend admin functionality for the Employee Management System.
 */
class EMS_Frontend_Admin {
    /**
     * Initialize the frontend admin functionality.
     */
    public static function init() {
        // Register shortcodes
        add_shortcode('ems_admin_dashboard', array('EMS_Frontend_Admin', 'admin_dashboard_shortcode'));
        add_shortcode('ems_admin_employees', array('EMS_Frontend_Admin', 'admin_employees_shortcode'));
        add_shortcode('ems_admin_attendance', array('EMS_Frontend_Admin', 'admin_attendance_shortcode'));
        add_shortcode('ems_admin_salary', array('EMS_Frontend_Admin', 'admin_salary_shortcode'));
        add_shortcode('ems_employee_registration', array('EMS_Frontend_Admin', 'employee_registration_shortcode'));
        add_shortcode('ems_employee_attendance', array('EMS_Frontend_Admin', 'employee_attendance_shortcode'));
        
        // Handle AJAX requests
        add_action('wp_ajax_ems_get_employee_data', array('EMS_Frontend_Admin', 'ajax_get_employee_data'));
        add_action('wp_ajax_ems_get_admin_attendance_data', array('EMS_Frontend_Admin', 'ajax_get_admin_attendance_data'));
        add_action('wp_ajax_ems_get_admin_salary_data', array('EMS_Frontend_Admin', 'ajax_get_admin_salary_data'));
        add_action('wp_ajax_ems_register_employee', array('EMS_Frontend_Admin', 'ajax_register_employee'));
        add_action('wp_ajax_nopriv_ems_register_employee', array('EMS_Frontend_Admin', 'ajax_register_employee'));

        // ✅ Corrected handler method references
        add_action('wp_ajax_ems_mark_attendance', array('EMS_Frontend_Admin', 'ems_handle_mark_attendance'));
        add_action('wp_ajax_ems_export_attendance', array('EMS_Frontend_Admin', 'ems_handle_export_attendance'));
    }


    public static function ems_handle_mark_attendance() {
        check_ajax_referer('ems_frontend_nonce', 'nonce');

        $date = sanitize_text_field($_POST['attendance_date']);
        $employee_ids = isset($_POST['employee_id']) ? array_map('intval', $_POST['employee_id']) : array();
        $statuses = isset($_POST['attendance_status']) ? array_map('sanitize_text_field', $_POST['attendance_status']) : array();

        $result = EMS_Attendance::mark_bulk_attendance($employee_ids, $date, $statuses);

        wp_send_json_success([
            'message' => sprintf(__('Attendance marked for %d employees. %d failed.', 'employee-management-system'), $result['success'], $result['failed']),
            'data' => $result
        ]);
    }

    public static function ems_handle_export_attendance() {
        check_ajax_referer('ems_frontend_nonce', 'nonce');

        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);
        $employee_id = !empty($_POST['employee_id']) ? intval($_POST['employee_id']) : null;

        $result = EMS_Attendance::export_attendance_to_csv($start_date, $end_date, $employee_id);

        if (!is_wp_error($result)) {
            wp_send_json_success([
                'download_url' => $result['file_url'] ?? '',
                'message' => __('Export successful.', 'employee-management-system')
            ]);
        } else {
            wp_send_json_error([
                'message' => $result->get_error_message()
            ]);
        }
    }



    
    /**
     * Admin dashboard shortcode callback.
     */
    public static function admin_dashboard_shortcode($atts) {
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            return self::admin_access_denied_message();
        }
        
        // Get counts
        global $wpdb;
        $employees_table = $wpdb->prefix . 'ems_employees';
        $attendance_table = $wpdb->prefix . 'ems_attendance';
        $salary_table = $wpdb->prefix . 'ems_salary';
        
        $employee_count = $wpdb->get_var("SELECT COUNT(*) FROM $employees_table");
        $present_today = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $attendance_table WHERE date = %s AND status = 'present'",
            date('Y-m-d')
        ));
        $absent_today = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $attendance_table WHERE date = %s AND status = 'absent'",
            date('Y-m-d')
        ));
        $pending_salary = $wpdb->get_var(
            "SELECT COUNT(*) FROM $salary_table WHERE payment_status = 'pending'"
        );
        
        // Get recent employees
        $recent_employees = $wpdb->get_results(
            "SELECT * FROM $employees_table ORDER BY created_at DESC LIMIT 5"
        );
        
        // Start output buffering
        ob_start();
        
        // Include the admin dashboard template
        include EMS_PLUGIN_DIR . 'templates/frontend/admin-dashboard.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Admin employees shortcode callback.
     */
    public static function admin_employees_shortcode($atts) {
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            return self::admin_access_denied_message();
        }
        
        // Get all employees
        $employees = EMS_Employee::get_all_employees();
        
        // Start output buffering
        ob_start();
        
        // Include the admin employees template
        include EMS_PLUGIN_DIR . 'templates/frontend/admin-employees.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Admin attendance shortcode callback.
     */
    public static function admin_attendance_shortcode($atts) {
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            return self::admin_access_denied_message();
        }
        
        // Get current date if not specified
        $current_date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : date('Y-m-d');
        
        // Get employee ID if specified
        $employee_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : null;
        
        // Get attendance records
        $attendance_records = EMS_Attendance::get_attendance($current_date, $current_date, $employee_id);
        
        // Get all employees for filter
        $employees = EMS_Employee::get_all_employees();
        
        // Start output buffering
        ob_start();
        
        // Include the admin attendance template
        include EMS_PLUGIN_DIR . 'templates/frontend/admin-attendance.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Admin salary shortcode callback.
     */
    public static function admin_salary_shortcode($atts) {
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            return self::admin_access_denied_message();
        }
        
        // Get current month and year if not specified
        $current_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $current_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        // Get employee ID if specified
        $employee_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : null;
        
        // Get salary records
        $salary_records = EMS_Salary::get_salary_records($current_month, $current_year, $employee_id);
        
        // Get all employees for filter
        $employees = EMS_Employee::get_all_employees();
        
        // Start output buffering
        ob_start();
        
        // Include the admin salary template
        include EMS_PLUGIN_DIR . 'templates/frontend/admin-salary.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Employee registration shortcode callback.
     */
    public static function employee_registration_shortcode($atts) {
        // Check if user has admin permissions or if registration is allowed for all
        $allow_public_registration = get_option('ems_allow_public_registration', 'no');
        
        if ($allow_public_registration !== 'yes' && !self::current_user_is_admin()) {
            return self::admin_access_denied_message();
        }
        
        // Process registration form
        $message = '';
        if (isset($_POST['ems_registration_nonce']) && wp_verify_nonce($_POST['ems_registration_nonce'], 'ems_registration_action')) {
            // Get form data
            $name = sanitize_text_field($_POST['name']);
            $email = sanitize_email($_POST['email']);
            $job_title = sanitize_text_field($_POST['job_title']);
            $company_name = sanitize_text_field($_POST['company_name']);
            $joining_date = sanitize_text_field($_POST['joining_date']);
            $salary_type = sanitize_text_field($_POST['salary_type']);
            $salary_amount = floatval($_POST['salary_amount']);
            $address = sanitize_textarea_field($_POST['address']);
            
            // Handle profile picture upload
            $profile_picture = '';
            if (!empty($_FILES['profile_picture']['name'])) {
                if (!function_exists('wp_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                }
                
                $upload_overrides = array('test_form' => false);
                $uploaded_file = wp_handle_upload($_FILES['profile_picture'], $upload_overrides);
                
                if (!isset($uploaded_file['error'])) {
                    $profile_picture = $uploaded_file['url'];
                } else {
                    $message = '<div class="ems-alert ems-alert-danger">' . $uploaded_file['error'] . '</div>';
                }
            }
            
            // Create employee if no error
            if (empty($message)) {
                $employee_data = array(
                    'name' => $name,
                    'email' => $email,
                    'profile_picture' => $profile_picture,
                    'joining_date' => $joining_date,
                    'job_title' => $job_title,
                    'company_name' => $company_name,
                    'address' => $address,
                    'salary_type' => $salary_type,
                    'salary_amount' => $salary_amount
                );
                
                $result = EMS_Employee::add_employee($employee_data);
                
                if (!is_wp_error($result)) {
                    // Create WordPress user if option is enabled
                    $create_wp_user = get_option('ems_create_wp_user', 'yes');
                    
                    if ($create_wp_user === 'yes') {
                        // Generate username from email
                        $username = explode('@', $email)[0];
                        $username = sanitize_user($username, true);
                        
                        // Check if username exists
                        $suffix = 1;
                        $original_username = $username;
                        while (username_exists($username)) {
                            $username = $original_username . $suffix;
                            $suffix++;
                        }
                        
                        // Generate random password
                        $password = wp_generate_password(12, true, true);
                        
                        // Create user
                        $user_id = wp_create_user($username, $password, $email);
                        
                        if (!is_wp_error($user_id)) {
                            // Set user role
                            $user = new WP_User($user_id);
                            $user->set_role('subscriber');
                            
                            // Update user meta
                            update_user_meta($user_id, 'first_name', explode(' ', $name)[0]);
                            if (strpos($name, ' ') !== false) {
                                update_user_meta($user_id, 'last_name', substr($name, strpos($name, ' ') + 1));
                            }
                            
                            // Send email to user with login details
                            $subject = sprintf(__('Welcome to %s - Your Account Details', 'employee-management-system'), get_bloginfo('name'));
                            $message = sprintf(
                                __('Hello %s,

                                Your employee account has been created successfully. You can now log in to the employee portal using the following details:

                                Username: %s
                                Password: %s
                                Login URL: %s

                                Please change your password after the first login.

                                Regards,
                                %s', 'employee-management-system'),
                                $name,
                                $username,
                                $password,
                                get_permalink(get_option('ems_login_page_id')),
                                get_bloginfo('name')
                            );
                            
                            wp_mail($email, $subject, $message);
                        }
                    }
                    
                    $message = '<div class="ems-alert ems-alert-success">' . __('Employee registered successfully.', 'employee-management-system') . '</div>';
                } else {
                    $message = '<div class="ems-alert ems-alert-danger">' . $result->get_error_message() . '</div>';
                }
            }
        }
        
        // Start output buffering
        ob_start();
        
        // Include the registration template
        include EMS_PLUGIN_DIR . 'templates/frontend/employee-registration.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Employee registration shortcode callback.
     */
    public static function employee_attendance_shortcode($atts) {
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            return self::admin_access_denied_message();
        }
        
        // Get current date if not specified
        $current_date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : date('Y-m-d');
        
        // Get employee ID if specified
        $employee_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : null;
        
        // Get attendance records
        $attendance_records = EMS_Attendance::get_attendance($current_date, $current_date, $employee_id);
        
        // Get all employees for filter
        $employees = EMS_Employee::get_all_employees();
        
        // Start output buffering
        ob_start();
        
        // Include the admin attendance template
        include EMS_PLUGIN_DIR . 'templates/frontend/employee-attendance.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * AJAX handler to get employee data.
     */
    public static function ajax_get_employee_data() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            wp_send_json_error(__('You do not have permission to access this data.', 'employee-management-system'));
        }
        
        // Get employee ID
        $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : 0;
        
        if (!$employee_id) {
            wp_send_json_error(__('Invalid employee ID.', 'employee-management-system'));
        }
        
        // Get employee data
        $employee = EMS_Employee::get_employee($employee_id);
        
        if (!$employee) {
            wp_send_json_error(__('Employee not found.', 'employee-management-system'));
        }
        
        wp_send_json_success($employee);
    }
    
    /**
     * AJAX handler to get admin attendance data.
     */
    public static function ajax_get_admin_attendance_data() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            wp_send_json_error(__('You do not have permission to access this data.', 'employee-management-system'));
        }
        
        // Get date range
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : date('Y-m-01');
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : date('Y-m-t');
        
        // Get employee ID if specified
        $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : null;
        
        // Get attendance data
        $attendance_records = EMS_Attendance::get_attendance($start_date, $end_date, $employee_id);
        
        // Format data for chart
        $attendance_data = array(
            'present' => 0,
            'absent' => 0,
            'half_day' => 0,
            'leave' => 0,
            'dates' => array()
        );
        
        foreach ($attendance_records as $record) {
            $attendance_data[$record->status]++;
            
            if (!isset($attendance_data['dates'][$record->date])) {
                $attendance_data['dates'][$record->date] = array(
                    'present' => 0,
                    'absent' => 0,
                    'half_day' => 0,
                    'leave' => 0
                );
            }
            
            $attendance_data['dates'][$record->date][$record->status]++;
        }
        
        wp_send_json_success($attendance_data);
    }
    
    /**
     * AJAX handler to get admin salary data.
     */
    public static function ajax_get_admin_salary_data() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user has admin permissions
        if (!self::current_user_is_admin()) {
            wp_send_json_error(__('You do not have permission to access this data.', 'employee-management-system'));
        }
        
        // Get year
        $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
        
        // Get employee ID if specified
        $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : null;
        
        // Get salary data
        $salary_data = array();
        
        for ($month = 1; $month <= 12; $month++) {
            $monthly_data = array(
                'month' => date_i18n('F', mktime(0, 0, 0, $month, 1)),
                'total' => 0,
                'paid' => 0,
                'pending' => 0
            );
            
            $salary_records = EMS_Salary::get_salary_records($month, $year, $employee_id);
            
            foreach ($salary_records as $record) {
                $monthly_data['total'] += $record->total_salary;
                
                if ($record->payment_status === 'paid') {
                    $monthly_data['paid'] += $record->total_salary;
                } else {
                    $monthly_data['pending'] += $record->total_salary;
                }
            }
            
            $salary_data[] = $monthly_data;
        }
        
        wp_send_json_success($salary_data);
    }
    
    /**
     * AJAX handler to register employee.
     */
    public static function ajax_register_employee() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user has admin permissions or if registration is allowed for all
        $allow_public_registration = get_option('ems_allow_public_registration', 'no');
        
        if ($allow_public_registration !== 'yes' && !self::current_user_is_admin()) {
            wp_send_json_error(__('You do not have permission to register employees.', 'employee-management-system'));
        }
        
        // Get form data
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $job_title = sanitize_text_field($_POST['job_title']);
        $company_name = sanitize_text_field($_POST['company_name']);
        $joining_date = sanitize_text_field($_POST['joining_date']);
        $salary_type = sanitize_text_field($_POST['salary_type']);
        $salary_amount = floatval($_POST['salary_amount']);
        $address = sanitize_textarea_field($_POST['address']);
        
        // Validate required fields
        if (empty($name) || empty($email) || empty($job_title) || empty($joining_date) || empty($salary_amount)) {
            wp_send_json_error(__('Please fill in all required fields.', 'employee-management-system'));
        }
        
        // Validate email
        if (!is_email($email)) {
            wp_send_json_error(__('Please enter a valid email address.', 'employee-management-system'));
        }
        
        // Create employee
        $employee_data = array(
            'name' => $name,
            'email' => $email,
            'profile_picture' => '',
            'joining_date' => $joining_date,
            'job_title' => $job_title,
            'company_name' => $company_name,
            'address' => $address,
            'salary_type' => $salary_type,
            'salary_amount' => $salary_amount
        );
        
        $result = EMS_Employee::add_employee($employee_data);
        
        if (!is_wp_error($result)) {
            // Create WordPress user if option is enabled
            $create_wp_user = get_option('ems_create_wp_user', 'yes');
            
            if ($create_wp_user === 'yes') {
                // Generate username from email
                $username = explode('@', $email)[0];
                $username = sanitize_user($username, true);
                
                // Check if username exists
                $suffix = 1;
                $original_username = $username;
                while (username_exists($username)) {
                    $username = $original_username . $suffix;
                    $suffix++;
                }
                
                // Generate random password
                $password = wp_generate_password(12, true, true);
                
                // Create user
                $user_id = wp_create_user($username, $password, $email);
                
                if (!is_wp_error($user_id)) {
                    // Set user role
                    $user = new WP_User($user_id);
                    $user->set_role('subscriber');
                    
                    // Update user meta
                    update_user_meta($user_id, 'first_name', explode(' ', $name)[0]);
                    if (strpos($name, ' ') !== false) {
                        update_user_meta($user_id, 'last_name', substr($name, strpos($name, ' ') + 1));
                    }
                    
                    // Send email to user with login details
                    $subject = sprintf(__('Welcome to %s - Your Account Details', 'employee-management-system'), get_bloginfo('name'));
                    $message = sprintf(
                        __('Hello %s,

                            Your employee account has been created successfully. You can now log in to the employee portal using the following details:

                            Username: %s
                            Password: %s
                            Login URL: %s

                            Please change your password after the first login.

                            Regards,
                            %s', 'employee-management-system'),
                        $name,
                        $username,
                        $password,
                        get_permalink(get_option('ems_login_page_id')),
                        get_bloginfo('name')
                    );
                    
                    wp_mail($email, $subject, $message);
                    
                    wp_send_json_success(array(
                        'message' => __('Employee registered successfully. Login details have been sent to the employee\'s email.', 'employee-management-system'),
                        'employee_id' => $result
                    ));
                } else {
                    wp_send_json_success(array(
                        'message' => __('Employee registered successfully, but there was an error creating the user account.', 'employee-management-system'),
                        'employee_id' => $result
                    ));
                }
            } else {
                wp_send_json_success(array(
                    'message' => __('Employee registered successfully.', 'employee-management-system'),
                    'employee_id' => $result
                ));
            }
        } else {
            wp_send_json_error($result->get_error_message());
        }
    }
    
    /**
     * Check if current user is an admin.
     */
    private static function current_user_is_admin() {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return false;
        }
        
        // Check if user is a WordPress admin
        if (current_user_can('manage_options')) {
            return true;
        }
        
        // Check if user is an EMS admin or HR manager
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_user_roles';
        $user_id = get_current_user_id();
        
        $role = $wpdb->get_var($wpdb->prepare(
            "SELECT role FROM $table_name WHERE user_id = %d",
            $user_id
        ));
        
        return $role === 'admin' || $role === 'hr_manager';
    }
    
    /**
     * Display admin access denied message.
     */
    private static function admin_access_denied_message() {
        return '<div class="ems-access-denied">' . 
            __('You do not have permission to access this page. Please log in with an administrator account.', 'employee-management-system') . 
            '</div>';
    }
}

// Initialize frontend admin
add_action('init', array('EMS_Frontend_Admin', 'init'));