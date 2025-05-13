<div class="ems-container ems-admin-attendance-container">
    <div class="ems-page-header">
        <h1><?php _e('Attendance Management', 'employee-management-system'); ?></h1>
        <div class="ems-user-info">
            <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_dashboard_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                <?php _e('Back to Dashboard', 'employee-management-system'); ?>
            </a>
        </div>
    </div>
    
    <div class="ems-admin-nav">
        <ul class="ems-nav">
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_dashboard_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-dashboard"></i>
                    <?php _e('Dashboard', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_profile_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-user"></i>
                    <?php _e('My Profile', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item active">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_attendance_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-calendar"></i>
                    <?php _e('Attendance', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_salary_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-money"></i>
                    <?php _e('Salary', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_employee_registration_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-user-plus"></i>
                    <?php _e('Add Employee', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_employees_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-user-plus"></i>
                    <?php _e('All Employees', 'employee-management-system'); ?>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="ems-admin-content">
        <div class="ems-row">
            <div class="ems-col-md-4">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Attendance Filter', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <form method="get" class="ems-form">
                            <div class="ems-form-group">
                                <label for="date"><?php _e('Date', 'employee-management-system'); ?></label>
                                <input type="date" name="date" id="date" value="<?php echo esc_attr($current_date); ?>">
                            </div>
                            
                            <div class="ems-form-group">
                                <label for="employee_id"><?php _e('Employee', 'employee-management-system'); ?></label>
                                <select name="employee_id" id="employee_id">
                                    <option value=""><?php _e('All Employees', 'employee-management-system'); ?></option>
                                    <?php foreach ($employees as $emp) : ?>
                                        <option value="<?php echo esc_attr($emp->id); ?>" <?php selected($employee_id, $emp->id); ?>>
                                            <?php echo esc_html($emp->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="ems-form-group">
                                <button type="submit" class="ems-btn ems-btn-primary">
                                    <?php _e('Apply Filter', 'employee-management-system'); ?>
                                </button>
                                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_attendance_page_id'))); ?>" class="ems-btn ems-btn-outline">
                                    <?php _e('Reset', 'employee-management-system'); ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="ems-card ems-mt-4">
                    <div class="ems-card-header">
                        <h3><?php _e('Attendance Summary', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-attendance-chart-container">
                            <canvas id="attendanceSummaryChart" width="400" height="300"></canvas>
                        </div>
                        
                        <div class="ems-attendance-summary ems-mt-3">
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value" id="present-count">0</div>
                                <div class="ems-attendance-stat-label"><?php _e('Present', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value" id="absent-count">0</div>
                                <div class="ems-attendance-stat-label"><?php _e('Absent', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value" id="half-day-count">0</div>
                                <div class="ems-attendance-stat-label"><?php _e('Half Day', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value" id="leave-count">0</div>
                                <div class="ems-attendance-stat-label"><?php _e('Leave', 'employee-management-system'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ems-col-md-8">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <div class="ems-card-header-actions">
                            <h3><?php _e('Attendance Records', 'employee-management-system'); ?></h3>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-attendance&action=add')); ?>" class="ems-btn ems-btn-primary">
                                <i class="ems-icon ems-icon-plus"></i> <?php _e('Mark Attendance', 'employee-management-system'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="ems-card-body">
                        <?php if (empty($attendance_records)) : ?>
                            <div class="ems-alert ems-alert-info">
                                <?php _e('No attendance records found for the selected date.', 'employee-management-system'); ?>
                            </div>
                        <?php else : ?>
                            <div class="ems-table-responsive">
                                <table class="ems-table">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Employee', 'employee-management-system'); ?></th>
                                            <th><?php _e('Date', 'employee-management-system'); ?></th>
                                            <th><?php _e('Status', 'employee-management-system'); ?></th>
                                            <th><?php _e('Notes', 'employee-management-system'); ?></th>
                                            <th><?php _e('Actions', 'employee-management-system'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($attendance_records as $record) : 
                                            $employee_data = EMS_Employee::get_employee($record->employee_id);
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="ems-employee-list-item">
                                                        <?php if (!empty($employee_data->profile_picture)) : ?>
                                                            <div class="ems-employee-list-avatar">
                                                                <img src="<?php echo esc_url($employee_data->profile_picture); ?>" alt="<?php echo esc_attr($employee_data->name); ?>">
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="ems-employee-list-avatar ems-employee-list-avatar-placeholder">
                                                                <?php echo esc_html(substr($employee_data->name, 0, 1)); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="ems-employee-list-name">
                                                            <?php echo esc_html($employee_data->name); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($record->date))); ?></td>
                                                <td>
                                                    <span class="ems-badge <?php echo esc_attr('ems-badge-' . $record->status); ?>">
                                                        <?php 
                                                        $status_labels = array(
                                                            'present' => __('Present', 'employee-management-system'),
                                                            'absent' => __('Absent', 'employee-management-system'),
                                                            'half_day' => __('Half Day', 'employee-management-system'),
                                                            'leave' => __('Leave', 'employee-management-system')
                                                        );
                                                        echo esc_html($status_labels[$record->status]);
                                                        ?>
                                                    </span>
                                                </td>
                                                <td><?php echo esc_html($record->notes); ?></td>
                                                <td>
                                                    <div class="ems-action-buttons">
                                                        <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-attendance&action=edit&id=' . $record->id)); ?>" class="ems-btn ems-btn-sm ems-btn-outline ems-btn-icon" title="<?php esc_attr_e('Edit', 'employee-management-system'); ?>">
                                                            <i class="ems-icon ems-icon-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Load attendance data for chart
    $.ajax({
        url: ems_frontend.ajax_url,
        type: 'POST',
        data: {
            action: 'ems_get_admin_attendance_data',
            nonce: ems_frontend.nonce,
            start_date: '<?php echo esc_js(date('Y-m-01', strtotime($current_date))); ?>',
            end_date: '<?php echo esc_js(date('Y-m-t', strtotime($current_date))); ?>',
            employee_id: '<?php echo esc_js($employee_id); ?>'
        },
        success: function(response) {
            if (response.success) {
                updateAttendanceSummary(response.data);
                renderAttendanceChart(response.data);
            }
        }
    });
    
    function updateAttendanceSummary(data) {
        $('#present-count').text(data.present);
        $('#absent-count').text(data.absent);
        $('#half-day-count').text(data.half_day);
        $('#leave-count').text(data.leave);
    }
    
    function renderAttendanceChart(data) {
        var ctx = document.getElementById('attendanceSummaryChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [
                    '<?php _e('Present', 'employee-management-system'); ?>',
                    '<?php _e('Absent', 'employee-management-system'); ?>',
                    '<?php _e('Half Day', 'employee-management-system'); ?>',
                    '<?php _e('Leave', 'employee-management-system'); ?>'
                ],
                datasets: [{
                    data: [data.present, data.absent, data.half_day, data.leave],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>