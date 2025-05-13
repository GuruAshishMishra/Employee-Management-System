<div class="wrap ems-admin">
    <h1 class="wp-heading-inline"><?php _e('Attendance Management', 'employee-management-system'); ?></h1>
    <a href="#" class="page-title-action export-attendance"><?php _e('Export Attendance', 'employee-management-system'); ?></a>
    
    <!-- Date Navigation -->
    <div class="ems-date-navigation">
        <a href="<?php echo esc_url(add_query_arg('date', date('Y-m-d', strtotime($current_date . ' -1 day')))); ?>" class="button">
            &laquo; <?php _e('Previous Day', 'employee-management-system'); ?>
        </a>
        
        <form method="get" class="ems-date-form">
            <input type="hidden" name="page" value="employee-management-attendance">
            <input type="date" name="date" value="<?php echo esc_attr($current_date); ?>" onchange="this.form.submit()">
        </form>
        
        <a href="<?php echo esc_url(add_query_arg('date', date('Y-m-d', strtotime($current_date . ' +1 day')))); ?>" class="button">
            <?php _e('Next Day', 'employee-management-system'); ?> &raquo;
        </a>
    </div>
    
    <!-- Attendance Form -->
    <form method="post" id="ems-attendance-form">
        <?php wp_nonce_field('ems_attendance_action', 'ems_attendance_nonce'); ?>
        <input type="hidden" name="ems_action" value="mark_attendance">
        <input type="hidden" name="attendance_date" value="<?php echo esc_attr($current_date); ?>">
        
        <div class="ems-table-container">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Employee', 'employee-management-system'); ?></th>
                        <th><?php _e('Status', 'employee-management-system'); ?></th>
                        <th><?php _e('Notes', 'employee-management-system'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)) : ?>
                        <tr>
                            <td colspan="3"><?php _e('No employees found.', 'employee-management-system'); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($employees as $employee) : ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="employee_id[]" value="<?php echo esc_attr($employee->id); ?>">
                                    <?php echo esc_html($employee->name); ?>
                                </td>
                                <td>
                                    <select name="attendance_status[]" class="ems-attendance-status">
                                        <option value="present" <?php selected($attendance_records[$employee->id], 'present'); ?>><?php _e('Present', 'employee-management-system'); ?></option>
                                        <option value="absent" <?php selected($attendance_records[$employee->id], 'absent'); ?>><?php _e('Absent', 'employee-management-system'); ?></option>
                                        <option value="half_day" <?php selected($attendance_records[$employee->id], 'half_day'); ?>><?php _e('Half Day', 'employee-management-system'); ?></option>
                                        <option value="leave" <?php selected($attendance_records[$employee->id], 'leave'); ?>><?php _e('Leave', 'employee-management-system'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="attendance_notes[]" class="ems-attendance-notes" placeholder="<?php _e('Add notes (optional)', 'employee-management-system'); ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="ems-form-actions">
            <button type="submit" class="button button-primary"><?php _e('Save Attendance', 'employee-management-system'); ?></button>
        </div>
    </form>
    
    <!-- Export Attendance Modal -->
    <div id="ems-export-modal" class="ems-modal">
        <div class="ems-modal-content">
            <span class="ems-close">&times;</span>
            <h2><?php _e('Export Attendance', 'employee-management-system'); ?></h2>
            
            <form id="ems-export-form" method="post">
                <?php wp_nonce_field('ems_attendance_action', 'ems_attendance_nonce'); ?>
                <input type="hidden" name="ems_action" value="export_attendance">
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="start_date"><?php _e('Start Date', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="date" name="start_date" id="start_date" required>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="end_date"><?php _e('End Date', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="date" name="end_date" id="end_date" required>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group ems-full-width">
                        <label for="employee_id"><?php _e('Employee (Optional)', 'employee-management-system'); ?></label>
                        <select name="employee_id" id="employee_id">
                            <option value=""><?php _e('All Employees', 'employee-management-system'); ?></option>
                            <?php foreach ($employees as $employee) : ?>
                                <option value="<?php echo esc_attr($employee->id); ?>"><?php echo esc_html($employee->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="ems-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Export', 'employee-management-system'); ?></button>
                    <button type="button" class="button ems-cancel"><?php _e('Cancel', 'employee-management-system'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>