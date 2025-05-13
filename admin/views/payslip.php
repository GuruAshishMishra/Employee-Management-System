<div class="ems-payslip-header">
    <?php if (!empty($settings['company_logo'])) : ?>
        <img src="<?php echo esc_url($settings['company_logo']); ?>" alt="<?php echo esc_attr($settings['company_name']); ?>" class="ems-payslip-logo">
    <?php endif; ?>
    <div class="ems-payslip-company"><?php echo esc_html($settings['company_name']); ?></div>
    <div class="ems-payslip-title"><?php _e('SALARY SLIP', 'employee-management-system'); ?></div>
    <div class="ems-payslip-period"><?php echo esc_html($month_name . ' ' . $salary->year); ?></div>
</div>

<div class="ems-payslip-info">
    <div class="ems-payslip-employee">
        <h3><?php _e('Employee Details', 'employee-management-system'); ?></h3>
        <div class="ems-payslip-row">
            <div class="ems-payslip-label"><?php _e('Name:', 'employee-management-system'); ?></div>
            <div><?php echo esc_html($employee->name); ?></div>
        </div>
        <div class="ems-payslip-row">
            <div class="ems-payslip-label"><?php _e('Employee ID:', 'employee-management-system'); ?></div>
            <div><?php echo esc_html($employee->id); ?></div>
        </div>
        <div class="ems-payslip-row">
            <div class="ems-payslip-label"><?php _e('Job Title:', 'employee-management-system'); ?></div>
            <div><?php echo esc_html($employee->job_title); ?></div>
        </div>
        <div class="ems-payslip-row">
            <div class="ems-payslip-label"><?php _e('Joining Date:', 'employee-management-system'); ?></div>
            <div><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($employee->joining_date))); ?></div>
        </div>
    </div>
    
    <div class="ems-payslip-details">
        <h3><?php _e('Payment Details', 'employee-management-system'); ?></h3>
        <div class="ems-payslip-row">
            <div class="ems-payslip-label"><?php _e('Payment Date:', 'employee-management-system'); ?></div>
            <div>
                <?php if (!empty($salary->payment_date)) : ?>
                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($salary->payment_date))); ?>
                <?php else : ?>
                    <?php _e('Pending', 'employee-management-system'); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="ems-payslip-row">
            <div class="ems-payslip-label"><?php _e('Payment Status:', 'employee-management-system'); ?></div>
            <div>
                <?php echo $salary->payment_status === 'paid' ? __('Paid', 'employee-management-system') : __('Pending', 'employee-management-system'); ?>
            </div>
        </div>
        <div class="ems-payslip-row">
            <div class="ems-payslip-label"><?php _e('Payment Method:', 'employee-management-system'); ?></div>
            <div><?php _e('Bank Transfer', 'employee-management-system'); ?></div>
        </div>
    </div>
</div>

<table class="ems-payslip-table">
    <thead>
        <tr>
            <th><?php _e('Description', 'employee-management-system'); ?></th>
            <th><?php _e('Amount', 'employee-management-system'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php _e('Base Salary', 'employee-management-system'); ?></td>
            <td><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->base_salary, 2)); ?></td>
        </tr>
        <?php if ($salary->bonus > 0) : ?>
            <tr>
                <td><?php _e('Bonus', 'employee-management-system'); ?></td>
                <td><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->bonus, 2)); ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($salary->deduction > 0) : ?>
            <tr>
                <td><?php _e('Deduction', 'employee-management-system'); ?></td>
                <td>-<?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->deduction, 2)); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th><?php _e('Total', 'employee-management-system'); ?></th>
            <th><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($salary->total_salary, 2)); ?></th>
        </tr>
    </tfoot>
</table>

<?php if (!empty($salary->notes)) : ?>
    <div class="ems-payslip-notes">
        <h3><?php _e('Notes', 'employee-management-system'); ?></h3>
        <p><?php echo esc_html($salary->notes); ?></p>
    </div>
<?php endif; ?>

<div class="ems-payslip-footer">
    <div class="ems-payslip-signature">
        <div class="ems-payslip-signature-line"><?php _e('Employee Signature', 'employee-management-system'); ?></div>
    </div>
    <div class="ems-payslip-signature">
        <div class="ems-payslip-signature-line"><?php _e('Authorized Signature', 'employee-management-system'); ?></div>
    </div>
</div>