<div class="ems-container ems-admin-salary-container">
    <div class="ems-page-header">
        <h1><?php _e('Salary Management', 'employee-management-system'); ?></h1>
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
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_attendance_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-calendar"></i>
                    <?php _e('Attendance', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item active">
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
                        <h3><?php _e('Salary Filter', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <form method="get" class="ems-form">
                            <div class="ems-form-group">
                                <label for="month"><?php _e('Month', 'employee-management-system'); ?></label>
                                <select name="month" id="month">
                                    <?php for ($m = 1; $m <= 12; $m++) : ?>
                                        <option value="<?php echo esc_attr($m); ?>" <?php selected($current_month, $m); ?>>
                                            <?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $m, 1))); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            
                            <div class="ems-form-group">
                                <label for="year"><?php _e('Year', 'employee-management-system'); ?></label>
                                <select name="year" id="year">
                                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) : ?>
                                        <option value="<?php echo esc_attr($y); ?>" <?php selected($current_year, $y); ?>>
                                            <?php echo esc_html($y); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
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
                                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_salary_page_id'))); ?>" class="ems-btn ems-btn-outline">
                                    <?php _e('Reset', 'employee-management-system'); ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="ems-card ems-mt-4">
                    <div class="ems-card-header">
                        <h3><?php _e('Salary Summary', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-salary-chart-container">
                            <canvas id="salarySummaryChart" width="400" height="300"></canvas>
                        </div>
                        
                        <div class="ems-salary-actions ems-mt-3">
                            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-salary&action=generate&month=' . $current_month . '&year=' . $current_year)); ?>" class="ems-btn ems-btn-primary ems-btn-block">
                                <i class="ems-icon ems-icon-refresh-cw"></i> <?php _e('Generate Salary', 'employee-management-system'); ?>
                            </a>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-salary&action=export&month=' . $current_month . '&year=' . $current_year)); ?>" class="ems-btn ems-btn-outline ems-btn-block ems-mt-2">
                                <i class="ems-icon ems-icon-download"></i> <?php _e('Export to CSV', 'employee-management-system'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ems-col-md-8">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Salary Records', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <?php if (empty($salary_records)) : ?>
                            <div class="ems-alert ems-alert-info">
                                <?php _e('No salary records found for the selected month and year.', 'employee-management-system'); ?>
                            </div>
                        <?php else : ?>
                            <div class="ems-table-responsive">
                                <table class="ems-table">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Employee', 'employee-management-system'); ?></th>
                                            <th><?php _e('Base Salary', 'employee-management-system'); ?></th>
                                            <th><?php _e('Bonus', 'employee-management-system'); ?></th>
                                            <th><?php _e('Deduction', 'employee-management-system'); ?></th>
                                            <th><?php _e('Total', 'employee-management-system'); ?></th>
                                            <th><?php _e('Status', 'employee-management-system'); ?></th>
                                            <th><?php _e('Actions', 'employee-management-system'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $settings = get_option('ems_settings', array('currency_symbol' => '$'));
                                        foreach ($salary_records as $salary) : 
                                            $employee_data = EMS_Employee::get_employee($salary->employee_id);
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
                                                <td><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->base_salary, 2)); ?></td>
                                                <td><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->bonus, 2)); ?></td>
                                                <td><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->deduction, 2)); ?></td>
                                                <td><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->total_salary, 2)); ?></td>
                                                <td>
                                                    <span class="ems-badge <?php echo $salary->payment_status === 'paid' ? 'ems-badge-success' : 'ems-badge-warning'; ?>">
                                                        <?php echo $salary->payment_status === 'paid' ? __('Paid', 'employee-management-system') : __('Pending', 'employee-management-system'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="ems-action-buttons">
                                                        <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-salary&action=edit&id=' . $salary->id)); ?>" class="ems-btn ems-btn-sm ems-btn-outline ems-btn-icon" title="<?php esc_attr_e('Edit', 'employee-management-system'); ?>">
                                                            <i class="ems-icon ems-icon-edit"></i>
                                                        </a>
                                                        <?php if ($salary->payment_status === 'pending') : ?>
                                                            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-salary&action=pay&id=' . $salary->id)); ?>" class="ems-btn ems-btn-sm ems-btn-outline ems-btn-icon" title="<?php esc_attr_e('Mark as Paid', 'employee-management-system'); ?>">
                                                                <i class="ems-icon ems-icon-check"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-salary&action=payslip&id=' . $salary->id)); ?>" class="ems-btn ems-btn-sm ems-btn-outline ems-btn-icon" title="<?php esc_attr_e('Payslip', 'employee-management-system'); ?>">
                                                            <i class="ems-icon ems-icon-file-text"></i>
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
    // Load salary data for chart
    $.ajax({
        url: ems_frontend.ajax_url,
        type: 'POST',
        data: {
            action: 'ems_get_admin_salary_data',
            nonce: ems_frontend.nonce,
            year: <?php echo esc_js($current_year); ?>,
            employee_id: '<?php echo esc_js($employee_id); ?>'
        },
        success: function(response) {
            if (response.success) {
                renderSalaryChart(response.data);
            }
        }
    });
    
    function renderSalaryChart(data) {
        var months = [];
        var totalData = [];
        var paidData = [];
        var pendingData = [];
        
        data.forEach(function(item) {
            months.push(item.month);
            totalData.push(item.total);
            paidData.push(item.paid);
            pendingData.push(item.pending);
        });
        
        var ctx = document.getElementById('salarySummaryChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: '<?php _e('Total', 'employee-management-system'); ?>',
                        data: totalData,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '<?php _e('Paid', 'employee-management-system'); ?>',
                        data: paidData,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '<?php _e('Pending', 'employee-management-system'); ?>',
                        data: pendingData,
                        backgroundColor: 'rgba(255, 205, 86, 0.5)',
                        borderColor: 'rgba(255, 205, 86, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '<?php _e('Amount', 'employee-management-system'); ?>'
                        }
                    }
                }
            }
        });
    }
});
</script>