<?php
/**
 * Attendance management functionality.
 */
class EMS_Attendance {
    /**
     * Get attendance records for a specific date range.
     */
    public static function get_attendance($start_date, $end_date, $employee_id = null) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_attendance';
        $employee_table = $wpdb->prefix . 'ems_employees';
        
        $query = "SELECT a.*, e.name as employee_name 
                 FROM $table_name a
                 JOIN $employee_table e ON a.employee_id = e.id
                 WHERE a.date BETWEEN %s AND %s";
        
        $params = array($start_date, $end_date);
        
        if ($employee_id) {
            $query .= " AND a.employee_id = %d";
            $params[] = $employee_id;
        }
        
        $query .= " ORDER BY a.date DESC, e.name ASC";
        
        return $wpdb->get_results($wpdb->prepare($query, $params));
    }
    
    /**
     * Get attendance for a specific employee and date.
     */
    public static function get_employee_attendance($employee_id, $date) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_attendance';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE employee_id = %d AND date = %s",
            $employee_id, $date
        ));
    }
    
    /**
     * Mark attendance for an employee.
     */
    public static function mark_attendance($employee_id, $date, $status, $notes = '') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_attendance';
        
        // Check if attendance already exists for this employee and date
        $existing = self::get_employee_attendance($employee_id, $date);
        
        if ($existing) {
            // Update existing attendance
            return $wpdb->update(
                $table_name,
                array(
                    'status' => sanitize_text_field($status),
                    'notes' => sanitize_textarea_field($notes)
                ),
                array(
                    'employee_id' => $employee_id,
                    'date' => $date
                )
            );
        } else {
            // Insert new attendance
            return $wpdb->insert(
                $table_name,
                array(
                    'employee_id' => $employee_id,
                    'date' => $date,
                    'status' => sanitize_text_field($status),
                    'notes' => sanitize_textarea_field($notes)
                )
            );
        }
    }
    
    /**
     * Mark attendance for multiple employees.
     */
    public static function mark_bulk_attendance($employee_ids, $date, $statuses) {
        $results = array(
            'success' => 0,
            'failed' => 0
        );
        
        foreach ($employee_ids as $index => $employee_id) {
            $status = isset($statuses[$index]) ? $statuses[$index] : 'present';
            $result = self::mark_attendance($employee_id, $date, $status);
            
            if ($result) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }
        
        return $results;
    }
    
    /**
     * Delete attendance record.
     */
    public static function delete_attendance($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_attendance';
        
        return $wpdb->delete($table_name, array('id' => $id));
    }
    
    /**
     * Get attendance summary for a specific month.
     */
    public static function get_monthly_summary($employee_id, $month, $year) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_attendance';
        
        $start_date = sprintf('%d-%02d-01', $year, $month);
        $end_date = date('Y-m-t', strtotime($start_date));
        
        $query = "SELECT 
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN status = 'half_day' THEN 1 ELSE 0 END) as half_days,
                    SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as leave_days
                 FROM $table_name 
                 WHERE employee_id = %d AND date BETWEEN %s AND %s";
        
        return $wpdb->get_row($wpdb->prepare($query, $employee_id, $start_date, $end_date));
    }
    
    /**
     * Export attendance data to CSV.
     */
    public static function export_attendance_to_csv($start_date, $end_date, $employee_id = null) {
        $attendance_data = self::get_attendance($start_date, $end_date, $employee_id);
        
        if (empty($attendance_data)) {
            return new WP_Error('no_data', __('No attendance data found for the selected criteria.', 'employee-management-system'));
        }
        
        $filename = 'attendance_' . $start_date . '_to_' . $end_date . '.csv';
        $csv_data = array();
        
        // Add header row
        $csv_data[] = array(
            __('Employee ID', 'employee-management-system'),
            __('Employee Name', 'employee-management-system'),
            __('Date', 'employee-management-system'),
            __('Status', 'employee-management-system'),
            __('Notes', 'employee-management-system')
        );
        
        // Add data rows
        foreach ($attendance_data as $record) {
            $status_labels = array(
                'present' => __('Present', 'employee-management-system'),
                'absent' => __('Absent', 'employee-management-system'),
                'half_day' => __('Half Day', 'employee-management-system'),
                'leave' => __('Leave', 'employee-management-system')
            );
            
            $csv_data[] = array(
                $record->employee_id,
                $record->employee_name,
                $record->date,
                $status_labels[$record->status],
                $record->notes
            );
        }
        
        return array(
            'filename' => $filename,
            'data' => $csv_data
        );
    }
}