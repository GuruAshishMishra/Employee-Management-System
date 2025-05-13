<?php
/**
 * Employee management functionality.
 */
class EMS_Employee {
    /**
     * Get all employees.
     */
    public static function get_all_employees() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_employees';
        
        return $wpdb->get_results("SELECT * FROM $table_name ORDER BY name ASC");
    }
    
    /**
     * Get a single employee by ID.
     */
    public static function get_employee($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_employees';
        
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    }
    
    /**
     * Add a new employee.
     */
    public static function add_employee($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_employees';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => sanitize_text_field($data['name']),
                'email' => sanitize_email($data['email']),
                'profile_picture' => isset($data['profile_picture']) ? esc_url_raw($data['profile_picture']) : '',
                'joining_date' => sanitize_text_field($data['joining_date']),
                'job_title' => sanitize_text_field($data['job_title']),
                'company_name' => sanitize_text_field($data['company_name']),
                'address' => sanitize_textarea_field($data['address']),
                'salary_type' => sanitize_text_field($data['salary_type']),
                'salary_amount' => floatval($data['salary_amount'])
            )
        );
        
        return $result ? $wpdb->insert_id : false;
    }
    
    /**
     * Update an existing employee.
     */
    public static function update_employee($id, $data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_employees';
        
        return $wpdb->update(
            $table_name,
            array(
                'name' => sanitize_text_field($data['name']),
                'email' => sanitize_email($data['email']),
                'profile_picture' => isset($data['profile_picture']) ? esc_url_raw($data['profile_picture']) : '',
                'joining_date' => sanitize_text_field($data['joining_date']),
                'job_title' => sanitize_text_field($data['job_title']),
                'company_name' => sanitize_text_field($data['company_name']),
                'address' => sanitize_textarea_field($data['address']),
                'salary_type' => sanitize_text_field($data['salary_type']),
                'salary_amount' => floatval($data['salary_amount'])
            ),
            array('id' => $id)
        );
    }
    
    /**
     * Delete an employee.
     */
    public static function delete_employee($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_employees';
        
        return $wpdb->delete($table_name, array('id' => $id));
    }
    
    /**
     * Import employees from CSV.
     */
    public static function import_employees_from_csv($file) {
        if (!file_exists($file)) {
            return new WP_Error('file_not_found', __('CSV file not found.', 'employee-management-system'));
        }
        
        $handle = fopen($file, 'r');
        if (!$handle) {
            return new WP_Error('file_open_error', __('Could not open CSV file.', 'employee-management-system'));
        }
        
        // Skip header row
        $header = fgetcsv($handle);
        
        $imported = 0;
        $errors = array();
        
        while (($data = fgetcsv($handle)) !== false) {
            // Map CSV columns to employee data
            $employee_data = array(
                'name' => $data[0],
                'email' => $data[1],
                'profile_picture' => $data[2],
                'joining_date' => $data[3],
                'job_title' => $data[4],
                'company_name' => $data[5],
                'address' => $data[6],
                'salary_type' => $data[7],
                'salary_amount' => $data[8]
            );
            
            $result = self::add_employee($employee_data);
            
            if ($result) {
                $imported++;
            } else {
                $errors[] = sprintf(__('Failed to import employee: %s', 'employee-management-system'), $employee_data['name']);
            }
        }
        
        fclose($handle);
        
        return array(
            'imported' => $imported,
            'errors' => $errors
        );
    }
}