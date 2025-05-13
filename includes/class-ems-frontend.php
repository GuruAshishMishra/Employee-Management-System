<?php
/**
 * Frontend functionality for the Employee Management System.
 */
class EMS_Frontend {
    /**
     * Initialize the frontend functionality.
     */
    public static function init() {
        // Register shortcodes
        add_shortcode('ems_login', array('EMS_Frontend', 'login_shortcode'));
        add_shortcode('ems_dashboard', array('EMS_Frontend', 'dashboard_shortcode'));
        add_shortcode('ems_profile', array('EMS_Frontend', 'profile_shortcode'));
        add_shortcode('ems_attendance', array('EMS_Frontend', 'attendance_shortcode'));
        add_shortcode('ems_salary', array('EMS_Frontend', 'salary_shortcode'));
        
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array('EMS_Frontend', 'enqueue_scripts'));
        
        // Handle AJAX requests
        add_action('wp_ajax_ems_update_profile', array('EMS_Frontend', 'ajax_update_profile'));
        add_action('wp_ajax_ems_get_attendance_data', array('EMS_Frontend', 'ajax_get_attendance_data'));
        add_action('wp_ajax_ems_get_salary_data', array('EMS_Frontend', 'ajax_get_salary_data'));
        add_action('wp_ajax_ems_download_payslip', array('EMS_Frontend', 'ajax_download_payslip'));
    }
    
    /**
     * Enqueue frontend scripts and styles.
     */
    public static function enqueue_scripts() {
        // Only enqueue on pages with our shortcodes
        global $post;
        if (is_a($post, 'WP_Post') && (
            has_shortcode($post->post_content, 'ems_login') ||
            has_shortcode($post->post_content, 'ems_dashboard') ||
            has_shortcode($post->post_content, 'ems_profile') ||
            has_shortcode($post->post_content, 'ems_attendance') ||
            has_shortcode($post->post_content, 'ems_salary')
        )) {
            // CSS
            wp_enqueue_style(
                'ems-frontend-css',
                plugins_url('/assets/css/ems-frontend.css', dirname(__FILE__)),
                array(),
                EMS_PLUGIN_VERSION
            );
            
            // JavaScript
            wp_enqueue_script(
                'ems-frontend-js',
                plugins_url('/assets/js/ems-frontend.js', dirname(__FILE__)),
                array('jquery'),
                EMS_PLUGIN_VERSION,
                true
            );
            
            // Chart.js for attendance and salary charts
            wp_enqueue_script(
                'chartjs',
                'https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js',
                array(),
                '3.7.1',
                true
            );
            
            // Localize script for AJAX
            wp_localize_script(
                'ems-frontend-js',
                'ems_frontend',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('ems_frontend_nonce'),
                    'is_logged_in' => is_user_logged_in(),
                    'login_url' => wp_login_url(get_permalink()),
                    'logout_url' => wp_logout_url(get_permalink()),
                    'home_url' => home_url(),
                    'current_user_id' => get_current_user_id(),
                    'i18n' => array(
                        'error' => __('Error', 'employee-management-system'),
                        'success' => __('Success', 'employee-management-system'),
                        'loading' => __('Loading...', 'employee-management-system'),
                        'confirm_download' => __('Are you sure you want to download this payslip?', 'employee-management-system'),
                        'no_data' => __('No data available', 'employee-management-system')
                    )
                )
            );
        }
    }
    
    /**
     * Login shortcode callback.
     */
    public static function login_shortcode($atts) {
        // If user is already logged in, redirect to dashboard
        if (is_user_logged_in()) {
            $dashboard_page_id = get_option('ems_dashboard_page_id');
            if ($dashboard_page_id) {
                wp_redirect(get_permalink($dashboard_page_id));
                exit;
            }
        }
        
        // Process login form submission
        $error = '';
        if (isset($_POST['ems_login_nonce']) && wp_verify_nonce($_POST['ems_login_nonce'], 'ems_login_action')) {
            $username = sanitize_user($_POST['username']);
            $password = $_POST['password'];
            
            $user = wp_authenticate($username, $password);
            
            if (is_wp_error($user)) {
                $error = $user->get_error_message();
            } else {
                wp_set_auth_cookie($user->ID);
                
                // Redirect to dashboard
                $dashboard_page_id = get_option('ems_dashboard_page_id');
                if ($dashboard_page_id) {
                    wp_redirect(get_permalink($dashboard_page_id));
                    exit;
                } else {
                    wp_redirect(home_url());
                    exit;
                }
            }
        }
        
        // Start output buffering
        ob_start();
        
        // Include the login template
        include EMS_PLUGIN_DIR . 'templates/frontend/login.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Dashboard shortcode callback.
     */
    public static function dashboard_shortcode($atts) {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return self::login_required_message();
        }
        
        // Check if user is an employee
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            return '<div class="ems-error">' . __('You are not registered as an employee.', 'employee-management-system') . '</div>';
        }
        
        // Get employee data
        $attendance_summary = EMS_Attendance::get_monthly_summary($employee->id, date('n'), date('Y'));
        $current_month_salary = EMS_Salary::get_employee_salary($employee->id, date('n'), date('Y'));
        
        // Start output buffering
        ob_start();
        
        // Include the dashboard template
        include EMS_PLUGIN_DIR . 'templates/frontend/dashboard.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Profile shortcode callback.
     */
    public static function profile_shortcode($atts) {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return self::login_required_message();
        }
        
        // Check if user is an employee
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            return '<div class="ems-error">' . __('You are not registered as an employee.', 'employee-management-system') . '</div>';
        }
        
        // Process profile update
        $message = '';
        if (isset($_POST['ems_profile_nonce']) && wp_verify_nonce($_POST['ems_profile_nonce'], 'ems_profile_action')) {
            // Update user data
            $user_data = array(
                'ID' => $user_id,
                'user_email' => sanitize_email($_POST['email']),
                'first_name' => sanitize_text_field($_POST['first_name']),
                'last_name' => sanitize_text_field($_POST['last_name'])
            );
            
            $user_id = wp_update_user($user_data);
            
            if (is_wp_error($user_id)) {
                $message = '<div class="ems-error">' . $user_id->get_error_message() . '</div>';
            } else {
                // Update employee data
                $employee_data = array(
                    'email' => sanitize_email($_POST['email']),
                    'address' => sanitize_textarea_field($_POST['address'])
                );
                
                $result = EMS_Employee::update_employee($employee->id, $employee_data);
                
                if ($result !== false) {
                    $message = '<div class="ems-success">' . __('Profile updated successfully.', 'employee-management-system') . '</div>';
                    
                    // Refresh employee data
                    $employee = self::get_employee_by_user_id($user_id);
                } else {
                    $message = '<div class="ems-error">' . __('Failed to update profile.', 'employee-management-system') . '</div>';
                }
            }
        }
        
        // Get user data
        $user = get_userdata($user_id);
        
        // Start output buffering
        ob_start();
        
        // Include the profile template
        include EMS_PLUGIN_DIR . 'templates/frontend/profile.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Attendance shortcode callback.
     */
    public static function attendance_shortcode($atts) {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return self::login_required_message();
        }
        
        // Check if user is an employee
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            return '<div class="ems-error">' . __('You are not registered as an employee.', 'employee-management-system') . '</div>';
        }
        
        // Get current month and year
        $current_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $current_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        // Get attendance data for the current month
        $start_date = sprintf('%d-%02d-01', $current_year, $current_month);
        $end_date = date('Y-m-t', strtotime($start_date));
        $attendance_records = EMS_Attendance::get_attendance($start_date, $end_date, $employee->id);
        
        // Get attendance summary
        $attendance_summary = EMS_Attendance::get_monthly_summary($employee->id, $current_month, $current_year);
        
        // Start output buffering
        ob_start();
        
        // Include the attendance template
        include EMS_PLUGIN_DIR . 'templates/frontend/attendance.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Salary shortcode callback.
     */
    public static function salary_shortcode($atts) {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return self::login_required_message();
        }
        
        // Check if user is an employee
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            return '<div class="ems-error">' . __('You are not registered as an employee.', 'employee-management-system') . '</div>';
        }
        
        // Get salary records
        $salary_records = EMS_Salary::get_salary_records(null, null, $employee->id);
        
        // Start output buffering
        ob_start();
        
        // Include the salary template
        include EMS_PLUGIN_DIR . 'templates/frontend/salary.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * AJAX handler to update profile.
     */
    public static function ajax_update_profile() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to update your profile.', 'employee-management-system'));
        }
        
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            wp_send_json_error(__('You are not registered as an employee.', 'employee-management-system'));
        }
        
        // Update user data
        $user_data = array(
            'ID' => $user_id,
            'user_email' => sanitize_email($_POST['email']),
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name' => sanitize_text_field($_POST['last_name'])
        );
        
        $user_id = wp_update_user($user_data);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error($user_id->get_error_message());
        }
        
        // Update employee data
        $employee_data = array(
            'email' => sanitize_email($_POST['email']),
            'address' => sanitize_textarea_field($_POST['address'])
        );
        
        $result = EMS_Employee::update_employee($employee->id, $employee_data);
        
        if ($result !== false) {
            wp_send_json_success(__('Profile updated successfully.', 'employee-management-system'));
        } else {
            wp_send_json_error(__('Failed to update profile.', 'employee-management-system'));
        }
    }
    
    /**
     * AJAX handler to get attendance data.
     */
    public static function ajax_get_attendance_data() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to view attendance data.', 'employee-management-system'));
        }
        
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            wp_send_json_error(__('You are not registered as an employee.', 'employee-management-system'));
        }
        
        // Get month and year from request
        $month = isset($_POST['month']) ? intval($_POST['month']) : date('n');
        $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
        
        // Get attendance data
        $start_date = sprintf('%d-%02d-01', $year, $month);
        $end_date = date('Y-m-t', strtotime($start_date));
        $attendance_records = EMS_Attendance::get_attendance($start_date, $end_date, $employee->id);
        
        // Format data for chart
        $days_in_month = date('t', strtotime($start_date));
        $attendance_data = array();
        
        for ($day = 1; $day <= $days_in_month; $day++) {
            $date = sprintf('%d-%02d-%02d', $year, $month, $day);
            $status = 'absent'; // Default status
            
            foreach ($attendance_records as $record) {
                if ($record->date === $date) {
                    $status = $record->status;
                    break;
                }
            }
            
            $attendance_data[] = array(
                'date' => $date,
                'status' => $status
            );
        }
        
        wp_send_json_success($attendance_data);
    }
    
    /**
     * AJAX handler to get salary data.
     */
    public static function ajax_get_salary_data() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to view salary data.', 'employee-management-system'));
        }
        
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            wp_send_json_error(__('You are not registered as an employee.', 'employee-management-system'));
        }
        
        // Get year from request
        $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
        
        // Get salary data for the year
        $salary_data = array();
        
        for ($month = 1; $month <= 12; $month++) {
            $salary = EMS_Salary::get_employee_salary($employee->id, $month, $year);
            
            $salary_data[] = array(
                'month' => date_i18n('F', mktime(0, 0, 0, $month, 1)),
                'total_salary' => $salary ? $salary->total_salary : 0
            );
        }
        
        wp_send_json_success($salary_data);
    }
    
    /**
     * AJAX handler to download payslip.
     */
    public static function ajax_download_payslip() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ems_frontend_nonce')) {
            wp_send_json_error(__('Security check failed.', 'employee-management-system'));
        }
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to download payslips.', 'employee-management-system'));
        }
        
        $user_id = get_current_user_id();
        $employee = self::get_employee_by_user_id($user_id);
        
        if (!$employee) {
            wp_send_json_error(__('You are not registered as an employee.', 'employee-management-system'));
        }
        
        // Get salary ID from request
        $salary_id = isset($_POST['salary_id']) ? intval($_POST['salary_id']) : 0;
        
        if (!$salary_id) {
            wp_send_json_error(__('Invalid salary ID.', 'employee-management-system'));
        }
        
        // Get salary record
        $salary = EMS_Salary::get_salary_record($salary_id);
        
        if (!$salary || $salary->employee_id != $employee->id) {
            wp_send_json_error(__('Salary record not found or you do not have permission to access it.', 'employee-management-system'));
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
        include EMS_PLUGIN_DIR . 'templates/frontend/payslip-pdf.php';
        $payslip_html = ob_get_clean();
        
        // Generate PDF
        if (!class_exists('TCPDF')) {
            require_once EMS_PLUGIN_DIR . 'includes/tcpdf/tcpdf.php';
        }
        
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($settings['company_name']);
        $pdf->SetTitle(__('Payslip', 'employee-management-system') . ' - ' . $employee->name . ' - ' . $month_name . ' ' . $salary->year);
        $pdf->SetSubject(__('Payslip', 'employee-management-system'));
        
        // Set default header data
        $pdf->SetHeaderData('', 0, $settings['company_name'], __('Payslip', 'employee-management-system') . ' - ' . $month_name . ' ' . $salary->year);
        
        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Add a page
        $pdf->AddPage();
        
        // Write HTML content
        $pdf->writeHTML($payslip_html, true, false, true, false, '');
        
        // Close and output PDF document
        $pdf->Output('payslip_' . $employee->name . '_' . $month_name . '_' . $salary->year . '.pdf', 'D');
        exit;
    }
    
    /**
     * Get employee by user ID.
     */
    private static function get_employee_by_user_id($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_employees';
        
        // Get user email
        $user = get_userdata($user_id);
        if (!$user) {
            return false;
        }
        
        // Find employee by email
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE email = %s",
            $user->user_email
        ));
    }
    
    /**
     * Display login required message.
     */
    private static function login_required_message() {
        return '<div class="ems-login-required">' . 
            sprintf(
                __('You must be <a href="%s">logged in</a> to view this content.', 'employee-management-system'),
                wp_login_url(get_permalink())
            ) . 
            '</div>';
    }
}

// Initialize frontend
add_action('init', array('EMS_Frontend', 'init'));