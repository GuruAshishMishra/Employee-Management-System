<?php
/**
 * Salary management admin functionality.
 */
class EMS_Salary_Admin {
    /**
     * Display the salary page.
     */
    public static function display_salary() {
        // Process any actions
        self::process_actions();
        
        // Get current month and year if not specified
        $current_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $current_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        // Get salary records for the current month and year
        $salary_records = EMS_Salary::get_salary_records($current_month, $current_year);
        
        // Get all employees
        $employees = EMS_Employee::get_all_employees();
        
        // Include the view
        include EMS_PLUGIN_DIR . 'admin/views/salary.php';
    }
    
    /**
     * Process salary actions.
     */
    private static function process_actions() {
        // Check for nonce
        if (isset($_POST['ems_salary_nonce']) && wp_verify_nonce($_POST['ems_salary_nonce'], 'ems_salary_action')) {
            // Generate salary
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'generate_salary') {
                $month = intval($_POST['month']);
                $year = intval($_POST['year']);
                $employee_id = !empty($_POST['employee_id']) ? intval($_POST['employee_id']) : null;
                
                if ($employee_id) {
                    // Generate for a specific employee
                    $result = EMS_Salary::generate_salary($employee_id, $month, $year);
                    
                    if (!is_wp_error($result)) {
                        add_action('admin_notices', function() {
                            echo '<div class="notice notice-success is-dismissible"><p>' . 
                                __('Salary generated successfully.', 'employee-management-system') . 
                                '</p></div>';
                        });
                    } else {
                        add_action('admin_notices', function() use ($result) {
                            echo '<div class="notice notice-error is-dismissible"><p>' . 
                                $result->get_error_message() . 
                                '</p></div>';
                        });
                    }
                } else {
                    // Generate for all employees
                    $result = EMS_Salary::generate_all_salaries($month, $year);
                    
                    add_action('admin_notices', function() use ($result) {
                        echo '<div class="notice notice-success is-dismissible"><p>' . 
                            sprintf(__('Salary generated successfully for %d employees.', 'employee-management-system'), $result['success']) . 
                            '</p></div>';
                        
                        if ($result['failed'] > 0) {
                            echo '<div class="notice notice-error is-dismissible"><p>' . 
                                sprintf(__('Failed to generate salary for %d employees.', 'employee-management-system'), $result['failed']) . 
                                '</p></div>';
                        }
                    });
                }
                
                // Redirect to the same page with the month and year parameters
                wp_redirect(add_query_arg(array('month' => $month, 'year' => $year), admin_url('admin.php?page=employee-management-salary')));
                exit;
            }
            
            // Update salary
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'update_salary') {
                $salary_id = intval($_POST['salary_id']);
                
                $salary_data = array(
                    'bonus' => floatval($_POST['bonus']),
                    'deduction' => floatval($_POST['deduction']),
                    'payment_status' => sanitize_text_field($_POST['payment_status']),
                    'notes' => sanitize_textarea_field($_POST['notes'])
                );
                
                if ($_POST['payment_status'] === 'paid' && !empty($_POST['payment_date'])) {
                    $salary_data['payment_date'] = sanitize_text_field($_POST['payment_date']);
                }
                
                $result = EMS_Salary::update_salary($salary_id, $salary_data);
                
                if ($result !== false) {
                    add_action('admin_notices', function() {
                        echo '<div class="notice notice-success is-dismissible"><p>' . 
                            __('Salary updated successfully.', 'employee-management-system') . 
                            '</p></div>';
                    });
                }
                
                // Get the month and year from the form
                $month = intval($_POST['month']);
                $year = intval($_POST['year']);
                
                // Redirect to the same page with the month and year parameters
                wp_redirect(add_query_arg(array('month' => $month, 'year' => $year), admin_url('admin.php?page=employee-management-salary')));
                exit;
            }
            
            // Export salary
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'export_salary') {
                $month = intval($_POST['month']);
                $year = intval($_POST['year']);
                $employee_id = !empty($_POST['employee_id']) ? intval($_POST['employee_id']) : null;
                
                $result = EMS_Salary::export_salary_to_csv($month, $year, $employee_id);
                
                if (!is_wp_error($result)) {
                    // Output CSV file
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
                    header('Pragma: no-cache');
                    header('Expires: 0');
                    
                    $output = fopen('php://output', 'w');
                    foreach ($result['data'] as $row) {
                        fputcsv($output, $row);
                    }
                    fclose($output);
                    exit;
                } else {
                    add_action('admin_notices', function() use ($result) {
                        echo '<div class="notice notice-error is-dismissible"><p>' . 
                            $result->get_error_message() . 
                            '</p></div>';
                    });
                }
            }
        }
    }
}