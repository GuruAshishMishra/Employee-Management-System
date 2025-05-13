<?php
/**
 * Attendance management admin functionality.
 */
class EMS_Attendance_Admin {
    /**
     * Display the attendance page.
     */
    public static function display_attendance() {
        // Process any actions
        self::process_actions();
        
        // Get current date if not specified
        $current_date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : date('Y-m-d');
        
        // Get all employees
        $employees = EMS_Employee::get_all_employees();
        
        // Get attendance for the current date
        $attendance_records = array();
        foreach ($employees as $employee) {
            $attendance = EMS_Attendance::get_employee_attendance($employee->id, $current_date);
            $attendance_records[$employee->id] = $attendance ? $attendance->status : '';
        }
        
        // Include the view
        include EMS_PLUGIN_DIR . 'admin/views/attendance.php';
    }
    
    /**
     * Process attendance actions.
     */
    private static function process_actions() {
        // Check for nonce
        if (isset($_POST['ems_attendance_nonce']) && wp_verify_nonce($_POST['ems_attendance_nonce'], 'ems_attendance_action')) {
            // Mark attendance
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'mark_attendance') {
                $date = sanitize_text_field($_POST['attendance_date']);
                $employee_ids = isset($_POST['employee_id']) ? array_map('intval', $_POST['employee_id']) : array();
                $statuses = isset($_POST['attendance_status']) ? array_map('sanitize_text_field', $_POST['attendance_status']) : array();
                
                $result = EMS_Attendance::mark_bulk_attendance($employee_ids, $date, $statuses);
                
                add_action('admin_notices', function() use ($result) {
                    echo '<div class="notice notice-success is-dismissible"><p>' . 
                        sprintf(__('Attendance marked successfully for %d employees.', 'employee-management-system'), $result['success']) . 
                        '</p></div>';
                    
                    if ($result['failed'] > 0) {
                        echo '<div class="notice notice-error is-dismissible"><p>' . 
                            sprintf(__('Failed to mark attendance for %d employees.', 'employee-management-system'), $result['failed']) . 
                            '</p></div>';
                    }
                });
                
                // Redirect to the same page with the date parameter
                wp_redirect(add_query_arg('date', $date, admin_url('admin.php?page=employee-management-attendance')));
                exit;
            }
            
            // Export attendance
            if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'export_attendance') {
                $start_date = sanitize_text_field($_POST['start_date']);
                $end_date = sanitize_text_field($_POST['end_date']);
                $employee_id = !empty($_POST['employee_id']) ? intval($_POST['employee_id']) : null;

                $result = EMS_Attendance::export_attendance_to_csv($start_date, $end_date, $employee_id);

                if (!is_wp_error($result)) {
                    // Clean (erase) the output buffer and turn off output buffering
                    if (ob_get_length()) ob_end_clean();

                    // Set headers to force download as CSV
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
                    header('Pragma: no-cache');
                    header('Expires: 0');

                    // Open output stream
                    $output = fopen('php://output', 'w');

                    // Add CSV data
                    foreach ($result['data'] as $row) {
                        fputcsv($output, $row);
                    }

                    fclose($output);
                    exit;
                } else {
                    add_action('admin_notices', function () use ($result) {
                        echo '<div class="notice notice-error is-dismissible"><p>' .
                            $result->get_error_message() .
                            '</p></div>';
                    });
                }
            }

        }
    }
}