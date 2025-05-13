<?php
/**
 * Employee management admin functionality.
 */
class EMS_Employee_Admin {
    /**
     * Display the employees page.
     */
    public static function display_employees() {
        // Check if we need to process any actions
        self::process_actions();
        
        // Get all employees
        $employees = EMS_Employee::get_all_employees();
        
        // Include the view
        include EMS_PLUGIN_DIR . 'admin/views/employees.php';
    }
    
    /**
     * Process employee actions (add, edit, delete, import).
     */
    private static function process_actions() {
        // Check for nonce
        if (isset($_POST['ems_employee_nonce']) && wp_verify_nonce($_POST['ems_employee_nonce'], 'ems_employee_action')) {
            // Add or update employee
            if (isset($_POST['ems_action']) && ($_POST['ems_action'] === 'add' || $_POST['ems_action'] === 'update')) {
                $employee_data = array(
                    'name' => sanitize_text_field($_POST['name']),
                    'email' => sanitize_email($_POST['email']),
                    'joining_date' => sanitize_text_field($_POST['joining_date']),
                    'job_title' => sanitize_text_field($_POST['job_title']),
                    'company_name' => sanitize_text_field($_POST['company_name']),
                    'address' => sanitize_textarea_field($_POST['address']),
                    'salary_type' => sanitize_text_field($_POST['salary_type']),
                    'salary_amount' => floatval($_POST['salary_amount'])
                );
                
                // Handle profile picture upload
                if (!empty($_FILES['profile_picture']['name'])) {
                    $upload = self::handle_profile_picture_upload();
                    if (!is_wp_error($upload)) {
                        $employee_data['profile_picture'] = $upload['url'];
                    }
                }
                
                if ($_POST['ems_action'] === 'add') {
                    $result = EMS_Employee::add_employee($employee_data);
                    if ($result) {
                        add_action('admin_notices', function() {
                            echo '<div class="notice notice-success is-dismissible"><p>' . __('Employee added successfully.', 'employee-management-system') . '</p></div>';
                        });
                    }
                } else {
                    $employee_id = intval($_POST['employee_id']);
                    $result = EMS_Employee::update_employee($employee_id, $employee_data);
                    if ($result !== false) {
                        add_action('admin_notices', function() {
                            echo '<div class="notice notice-success is-dismissible"><p>' . __('Employee updated successfully.', 'employee-management-system') . '</p></div>';
                        });
                    }
                }
            }
            
            // Delete employee
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'delete' && isset($_POST['employee_id'])) {
                $employee_id = intval($_POST['employee_id']);
                $result = EMS_Employee::delete_employee($employee_id);
                if ($result) {
                    add_action('admin_notices', function() {
                        echo '<div class="notice notice-success is-dismissible"><p>' . __('Employee deleted successfully.', 'employee-management-system') . '</p></div>';
                    });
                }
            }
            
            // Import employees from CSV
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'import' && !empty($_FILES['csv_file']['name'])) {
                $file = $_FILES['csv_file']['tmp_name'];
                $result = EMS_Employee::import_employees_from_csv($file);
                
                if (!is_wp_error($result)) {
                    add_action('admin_notices', function() use ($result) {
                        echo '<div class="notice notice-success is-dismissible"><p>' . 
                            sprintf(__('Successfully imported %d employees.', 'employee-management-system'), $result['imported']) . 
                            '</p></div>';
                    });
                    
                    if (!empty($result['errors'])) {
                        add_action('admin_notices', function() use ($result) {
                            echo '<div class="notice notice-error is-dismissible"><p>' . 
                                __('Some employees could not be imported:', 'employee-management-system') . 
                                '</p><ul><li>' . implode('</li><li>', $result['errors']) . '</li></ul></div>';
                        });
                    }
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
    
    /**
     * Handle profile picture upload.
     */
    private static function handle_profile_picture_upload() {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        $upload_overrides = array('test_form' => false);
        $file = $_FILES['profile_picture'];
        
        $movefile = wp_handle_upload($file, $upload_overrides);
        
        if ($movefile && !isset($movefile['error'])) {
            return $movefile;
        } else {
            return new WP_Error('upload_error', $movefile['error']);
        }
    }
}