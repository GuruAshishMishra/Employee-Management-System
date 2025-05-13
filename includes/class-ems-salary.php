<?php
/**
 * Salary management functionality.
 */
class EMS_Salary {
    /**
     * Get salary records.
     */
    public static function get_salary_records($month = null, $year = null, $employee_id = null) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_salary';
        $employee_table = $wpdb->prefix . 'ems_employees';
        
        $query = "SELECT s.*, e.name as employee_name 
                 FROM $table_name s
                 JOIN $employee_table e ON s.employee_id = e.id
                 WHERE 1=1";
        
        $params = array();
        
        if ($month) {
            $query .= " AND s.month = %d";
            $params[] = $month;
        }
        
        if ($year) {
            $query .= " AND s.year = %d";
            $params[] = $year;
        }
        
        if ($employee_id) {
            $query .= " AND s.employee_id = %d";
            $params[] = $employee_id;
        }
        
        $query .= " ORDER BY s.year DESC, s.month DESC, e.name ASC";
        
        if (empty($params)) {
            return $wpdb->get_results($query);
        } else {
            return $wpdb->get_results($wpdb->prepare($query, $params));
        }
    }
    
    /**
     * Get a specific salary record.
     */
    public static function get_salary_record($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_salary';
        
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    }
    
    /**
     * Get salary record for a specific employee and month.
     */
    public static function get_employee_salary($employee_id, $month, $year) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_salary';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE employee_id = %d AND month = %d AND year = %d",
            $employee_id, $month, $year
        ));
    }
    
    /**
     * Generate salary for an employee.
     */
    public static function generate_salary($employee_id, $month, $year) {
        // Get employee details
        $employee = EMS_Employee::get_employee($employee_id);
        if (!$employee) {
            return new WP_Error('invalid_employee', __('Invalid employee.', 'employee-management-system'));
        }
        
        // Get attendance summary for the month
        $attendance = EMS_Attendance::get_monthly_summary($employee_id, $month, $year);
        
        // Calculate working days in the month
        $start_date = sprintf('%d-%02d-01', $year, $month);
        $end_date = date('Y-m-t', strtotime($start_date));
        $working_days = self::get_working_days($start_date, $end_date);
        
        // Calculate base salary based on attendance
        $base_salary = $employee->salary_amount;
        
        if ($employee->salary_type === 'monthly') {
            // Adjust for absences and half days
            $present_days = $attendance ? $attendance->present_days : 0;
            $half_days = $attendance ? $attendance->half_days : 0;
            $leave_days = $attendance ? $attendance->leave_days : 0;
            
            $attendance_factor = ($present_days + ($half_days * 0.5) + $leave_days) / $working_days;
            $base_salary = $base_salary * $attendance_factor;
        } elseif ($employee->salary_type === 'hourly') {
            // Assume 8 hours per working day
            $present_days = $attendance ? $attendance->present_days : 0;
            $half_days = $attendance ? $attendance->half_days : 0;
            
            $hours_worked = ($present_days * 8) + ($half_days * 4);
            $base_salary = $hours_worked * $base_salary;
        }
        
        // Round to 2 decimal places
        $base_salary = round($base_salary, 2);
        
        // Check if salary record already exists
        $existing = self::get_employee_salary($employee_id, $month, $year);
        
        if ($existing) {
            // Update existing record
            return self::update_salary($existing->id, array(
                'base_salary' => $base_salary,
                'total_salary' => $base_salary // Will be adjusted with bonus/deduction
            ));
        } else {
            // Create new record
            return self::add_salary(array(
                'employee_id' => $employee_id,
                'month' => $month,
                'year' => $year,
                'base_salary' => $base_salary,
                'bonus' => 0,
                'deduction' => 0,
                'total_salary' => $base_salary,
                'payment_status' => 'pending'
            ));
        }
    }
    
    /**
     * Generate salary for all employees.
     */
    public static function generate_all_salaries($month, $year) {
        $employees = EMS_Employee::get_all_employees();
        $results = array(
            'success' => 0,
            'failed' => 0
        );
        
        foreach ($employees as $employee) {
            $result = self::generate_salary($employee->id, $month, $year);
            
            if (!is_wp_error($result)) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }
        
        return $results;
    }
    
    /**
     * Add a new salary record.
     */
    public static function add_salary($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_salary';
        
        // Calculate total salary
        $total_salary = $data['base_salary'] + $data['bonus'] - $data['deduction'];
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'employee_id' => $data['employee_id'],
                'month' => $data['month'],
                'year' => $data['year'],
                'base_salary' => $data['base_salary'],
                'bonus' => $data['bonus'],
                'deduction' => $data['deduction'],
                'total_salary' => $total_salary,
                'payment_status' => $data['payment_status'],
                'payment_date' => isset($data['payment_date']) ? $data['payment_date'] : null,
                'notes' => isset($data['notes']) ? $data['notes'] : ''
            )
        );
        
        return $result ? $wpdb->insert_id : false;
    }
    
    /**
     * Update a salary record.
     */
    public static function update_salary($id, $data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_salary';
        
        // Get existing record
        $existing = self::get_salary_record($id);
        if (!$existing) {
            return false;
        }
        
        // Prepare update data
        $update_data = array();
        
        if (isset($data['base_salary'])) {
            $update_data['base_salary'] = $data['base_salary'];
        } else {
            $update_data['base_salary'] = $existing->base_salary;
        }
        
        if (isset($data['bonus'])) {
            $update_data['bonus'] = $data['bonus'];
        } else {
            $update_data['bonus'] = $existing->bonus;
        }
        
        if (isset($data['deduction'])) {
            $update_data['deduction'] = $data['deduction'];
        } else {
            $update_data['deduction'] = $existing->deduction;
        }
        
        // Calculate total salary
        $update_data['total_salary'] = $update_data['base_salary'] + $update_data['bonus'] - $update_data['deduction'];
        
        if (isset($data['payment_status'])) {
            $update_data['payment_status'] = $data['payment_status'];
        }
        
        if (isset($data['payment_date'])) {
            $update_data['payment_date'] = $data['payment_date'];
        }
        
        if (isset($data['notes'])) {
            $update_data['notes'] = $data['notes'];
        }
        
        return $wpdb->update($table_name, $update_data, array('id' => $id));
    }
    
    /**
     * Delete a salary record.
     */
    public static function delete_salary($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_salary';
        
        return $wpdb->delete($table_name, array('id' => $id));
    }
    
    /**
     * Get working days in a date range (excluding weekends).
     */
    private static function get_working_days($start_date, $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);
        
        $working_days = 0;
        
        foreach ($period as $day) {
            $day_of_week = $day->format('N');
            
            // Skip weekends (6 = Saturday, 7 = Sunday)
            if ($day_of_week < 6) {
                $working_days++;
            }
        }
        
        // Add one more day for the end date if it's a weekday
        $end_day_of_week = $end->format('N');
        if ($end_day_of_week < 6) {
            $working_days++;
        }
        
        return $working_days;
    }
    
    /**
     * Export salary data to CSV.
     */
    public static function export_salary_to_csv($month, $year, $employee_id = null) {
        $salary_data = self::get_salary_records($month, $year, $employee_id);
        
        if (empty($salary_data)) {
            return new WP_Error('no_data', __('No salary data found for the selected criteria.', 'employee-management-system'));
        }
        
        $filename = 'salary_' . $year . '-' . sprintf('%02d', $month) . '.csv';
        $csv_data = array();
        
        // Add header row
        $csv_data[] = array(
            __('Employee ID', 'employee-management-system'),
            __('Employee Name', 'employee-management-system'),
            __('Month', 'employee-management-system'),
            __('Year', 'employee-management-system'),
            __('Base Salary', 'employee-management-system'),
            __('Bonus', 'employee-management-system'),
            __('Deduction', 'employee-management-system'),
            __('Total Salary', 'employee-management-system'),
            __('Payment Status', 'employee-management-system'),
            __('Payment Date', 'employee-management-system'),
            __('Notes', 'employee-management-system')
        );
        
        // Add data rows
        foreach ($salary_data as $record) {
            $month_name = date('F', mktime(0, 0, 0, $record->month, 1));
            
            $csv_data[] = array(
                $record->employee_id,
                $record->employee_name,
                $month_name,
                $record->year,
                $record->base_salary,
                $record->bonus,
                $record->deduction,
                $record->total_salary,
                $record->payment_status === 'paid' ? __('Paid', 'employee-management-system') : __('Pending', 'employee-management-system'),
                $record->payment_date,
                $record->notes
            );
        }
        
        return array(
            'filename' => $filename,
            'data' => $csv_data
        );
    }
}