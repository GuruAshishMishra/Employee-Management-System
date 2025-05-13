<div class="ems-container ems-attendance-container">
    <div class="ems-page-header">
        <h1><?php _e('My Attendance', 'employee-management-system'); ?></h1>
        <div class="ems-user-info">
            <a href="<?php echo esc_url(get_permalink(get_option('ems_dashboard_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                <?php _e('Back to Dashboard', 'employee-management-system'); ?>
            </a>
        </div>
    </div>
    
    <div class="ems-dashboard-nav">
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
    
    <div class="ems-attendance-content">
        <div class="ems-row">
            <div class="ems-col-md-4">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Monthly Summary', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-month-selector">
                            <form method="get" class="ems-form">
                                <div class="ems-row">
                                    <div class="ems-col-md-6">
                                        <div class="ems-form-group">
                                            <label for="month"><?php _e('Month', 'employee-management-system'); ?></label>
                                            <select name="month" id="month" onchange="this.form.submit()">
                                                <?php for ($m = 1; $m <= 12; $m++) : ?>
                                                    <option value="<?php echo esc_attr($m); ?>" <?php selected($current_month, $m); ?>>
                                                        <?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $m, 1))); ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ems-col-md-6">
                                        <div class="ems-form-group">
                                            <label for="year"><?php _e('Year', 'employee-management-system'); ?></label>
                                            <select name="year" id="year" onchange="this.form.submit()">
                                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) : ?>
                                                    <option value="<?php echo esc_attr($y); ?>" <?php selected($current_year, $y); ?>>
                                                        <?php echo esc_html($y); ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="ems-attendance-summary">
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->present_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Present', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->absent_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Absent', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->half_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Half Day', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->leave_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Leave', 'employee-management-system'); ?></div>
                            </div>
                        </div>
                        
                        <div class="ems-attendance-chart-container">
                            <canvas id="attendanceChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ems-col-md-8">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Attendance Records', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <?php if (empty($attendance_records)) : ?>
                            <div class="ems-alert ems-alert-info">
                                <?php _e('No attendance records found for the selected month.', 'employee-management-system'); ?>
                            </div>
                        <?php else : ?>
                            <div class="ems-table-responsive">
                                <table class="ems-table">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Date', 'employee-management-system'); ?></th>
                                            <th><?php _e('Status', 'employee-management-system'); ?></th>
                                            <th><?php _e('Notes', 'employee-management-system'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($attendance_records as $record) : ?>
                                            <tr>
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
            action: 'ems_get_attendance_data',
            nonce: ems_frontend.nonce,
            month: <?php echo esc_js($current_month); ?>,
            year: <?php echo esc_js($current_year); ?>
        },
        success: function(response) {
            if (response.success) {
                renderAttendanceChart(response.data);
            }
        }
    });
    
    function renderAttendanceChart(data) {
        var labels = [];
        var presentData = [];
        var absentData = [];
        var halfDayData = [];
        var leaveData = [];
        
        data.forEach(function(item) {
            var date = new Date(item.date);
            labels.push(date.getDate());
            
            presentData.push(item.status === 'present' ? 1 : 0);
            absentData.push(item.status === 'absent' ? 1 : 0);
            halfDayData.push(item.status === 'half_day' ? 1 : 0);
            leaveData.push(item.status === 'leave' ? 1 : 0);
        });
        
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: '<?php _e('Present', 'employee-management-system'); ?>',
                        data: presentData,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '<?php _e('Absent', 'employee-management-system'); ?>',
                        data: absentData,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '<?php _e('Half Day', 'employee-management-system'); ?>',
                        data: halfDayData,
                        backgroundColor: 'rgba(255, 205, 86, 0.5)',
                        borderColor: 'rgba(255, 205, 86, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '<?php _e('Leave', 'employee-management-system'); ?>',
                        data: leaveData,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            text: '<?php _e('Day of Month', 'employee-management-system'); ?>'
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        max: 1,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>