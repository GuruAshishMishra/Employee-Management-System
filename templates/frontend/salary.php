<div class="ems-container ems-salary-container">
    <div class="ems-page-header">
        <h1><?php _e('My Salary', 'employee-management-system'); ?></h1>
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
    
    <div class="ems-salary-content">
        <div class="ems-row">
            <div class="ems-col-md-4">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Salary Overview', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-year-selector">
                            <form method="get" class="ems-form">
                                <div class="ems-form-group">
                                    <label for="year"><?php _e('Select Year', 'employee-management-system'); ?></label>
                                    <select name="year" id="year" onchange="this.form.submit()">
                                        <?php 
                                        $selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
                                        for ($y = date('Y'); $y >= date('Y') - 5; $y--) : 
                                        ?>
                                            <option value="<?php echo esc_attr($y); ?>" <?php selected($selected_year, $y); ?>>
                                                <?php echo esc_html($y); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        
                        <div class="ems-salary-chart-container">
                            <canvas id="salaryChart" width="400" height="300"></canvas>
                        </div>
                        
                        <div class="ems-salary-info">
                            <div class="ems-salary-info-item">
                                <span class="ems-salary-info-label"><?php _e('Salary Type:', 'employee-management-system'); ?></span>
                                <span class="ems-salary-info-value">
                                    <?php 
                                    $salary_types = array(
                                        'fixed' => __('Fixed', 'employee-management-system'),
                                        'hourly' => __('Hourly', 'employee-management-system'),
                                        'monthly' => __('Monthly', 'employee-management-system')
                                    );
                                    echo esc_html($salary_types[$employee->salary_type]);
                                    ?>
                                </span>
                            </div>
                            <div class="ems-salary-info-item">
                                <span class="ems-salary-info-label"><?php _e('Base Amount:', 'employee-management-system'); ?></span>
                                <span class="ems-salary-info-value">
                                    <?php 
                                    $settings = get_option('ems_settings', array('currency_symbol' => '$'));
                                    echo esc_html($settings['currency_symbol'] . number_format_i18n($employee->salary_amount, 2)); 
                                    ?>
                                </span>
                            </div>
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
                                <?php _e('No salary records found.', 'employee-management-system'); ?>
                            </div>
                        <?php else : ?>
                            <div class="ems-table-responsive">
                                <table class="ems-table">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Month', 'employee-management-system'); ?></th>
                                            <th><?php _e('Year', 'employee-management-system'); ?></th>
                                            <th><?php _e('Base Salary', 'employee-management-system'); ?></th>
                                            <th><?php _e('Bonus', 'employee-management-system'); ?></th>
                                            <th><?php _e('Deduction', 'employee-management-system'); ?></th>
                                            <th><?php _e('Total', 'employee-management-system'); ?></th>
                                            <th><?php _e('Status', 'employee-management-system'); ?></th>
                                            <th><?php _e('Actions', 'employee-management-system'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($salary_records as $salary) : ?>
                                            <tr>
                                                <td><?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $salary->month, 1))); ?></td>
                                                <td><?php echo esc_html($salary->year); ?></td>
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
                                                    <?php if ($salary->payment_status === 'paid') : ?>
                                                        <button class="ems-btn ems-btn-sm ems-btn-primary download-payslip" data-id="<?php echo esc_attr($salary->id); ?>">
                                                            <i class="ems-icon ems-icon-download"></i> <?php _e('Payslip', 'employee-management-system'); ?>
                                                        </button>
                                                    <?php else : ?>
                                                        <span class="ems-text-muted"><?php _e('Pending', 'employee-management-system'); ?></span>
                                                    <?php endif; ?>
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
            action: 'ems_get_salary_data',
            nonce: ems_frontend.nonce,
            year: <?php echo esc_js(isset($_GET['year']) ? intval($_GET['year']) : date('Y')); ?>
        },
        success: function(response) {
            if (response.success) {
                renderSalaryChart(response.data);
            }
        }
    });
    
    function renderSalaryChart(data) {
        var labels = [];
        var salaryData = [];
        
        data.forEach(function(item) {
            labels.push(item.month);
            salaryData.push(item.total_salary);
        });
        
        var ctx = document.getElementById('salaryChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '<?php _e('Total Salary', 'employee-management-system'); ?>',
                    data: salaryData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
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
    
    // Download payslip
    $('.download-payslip').on('click', function() {
        var salaryId = $(this).data('id');
        
        if (confirm(ems_frontend.i18n.confirm_download)) {
            window.location.href = ems_frontend.ajax_url + '?action=ems_download_payslip&nonce=' + ems_frontend.nonce + '&salary_id=' + salaryId;
        }
    });
});
</script>