jQuery(document).ready(function($) {
    // Modal functionality
    function openModal(modalId) {
        $('#' + modalId).show();
    }
    
    function closeModal(modalId) {
        $('#' + modalId).hide();
    }
    
    // Close modal when clicking the X or Cancel button
    $('.ems-close, .ems-cancel').on('click', function() {
        $(this).closest('.ems-modal').hide();
    });
    
    // Close modal when clicking outside the modal content
    $('.ems-modal').on('click', function(e) {
        if ($(e.target).hasClass('ems-modal')) {
            $(this).hide();
        }
    });
    
    // Employee Management
    // Open add employee modal
    $('.add-employee').on('click', function(e) {
        e.preventDefault();
        
        // Reset form
        $('#ems-employee-form')[0].reset();
        $('#ems_action').val('add');
        $('#employee_id').val('');
        $('#profile_preview').html('');
        $('#ems-modal-title').text(ems_ajax.i18n.add_employee);
        openModal('ems-employee-modal');
    });
    
    // Open edit employee modal
    $('.edit-employee').on('click', function(e) {
        e.preventDefault();
        
        var employeeId = $(this).data('id');
        
        // Load employee data via AJAX
        $.ajax({
            url: ems_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ems_get_employee',
                employee_id: employeeId,
                nonce: ems_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    var employee = response.data;
                    
                    // Fill form with employee data
                    $('#ems_action').val('update');
                    $('#employee_id').val(employee.id);
                    $('#name').val(employee.name);
                    $('#email').val(employee.email);
                    $('#joining_date').val(employee.joining_date);
                    $('#job_title').val(employee.job_title);
                    $('#company_name').val(employee.company_name);
                    $('#address').val(employee.address);
                    $('#salary_type').val(employee.salary_type);
                    $('#salary_amount').val(employee.salary_amount);
                    
                    // Show profile picture preview if available
                    if (employee.profile_picture) {
                        $('#profile_preview').html('<img src="' + employee.profile_picture + '" width="100" height="100" />');
                    } else {
                        $('#profile_preview').html('');
                    }
                    
                    $('#ems-modal-title').text(ems_ajax.i18n.edit_employee);
                    openModal('ems-employee-modal');
                } else {
                    alert(response.data);
                }
            }
        });
    });
    
    // Open delete employee modal
    $('.delete-employee').on('click', function(e) {
        e.preventDefault();
        
        var employeeId = $(this).data('id');
        $('#delete_employee_id').val(employeeId);
        
        openModal('ems-delete-modal');
    });
    
    // Open import employees modal
    $('.import-employees').on('click', function(e) {
        e.preventDefault();
        openModal('ems-import-modal');
    });
    
    // Profile picture preview
    $('#profile_picture').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profile_preview').html('<img src="' + e.target.result + '" width="100" height="100" />');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Attendance Management
    // Open export attendance modal
    $('.export-attendance').on('click', function(e) {
        e.preventDefault();
        openModal('ems-export-modal');
    });
    
    // Salary Management
    // Open generate salary modal
    $('.generate-salary').on('click', function(e) {
        e.preventDefault();
        openModal('ems-generate-modal');
    });
    
    // Open edit salary modal
    $('.edit-salary').on('click', function(e) {
        e.preventDefault();
        
        var salaryId = $(this).data('id');
        
        // Load salary data via AJAX
        $.ajax({
            url: ems_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ems_get_salary',
                salary_id: salaryId,
                nonce: ems_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    var salary = response.data;
                    
                    // Fill form with salary data
                    $('#salary_id').val(salary.id);
                    $('#employee_name').val(salary.employee_name);
                    $('#base_salary').val(salary.base_salary);
                    $('#bonus').val(salary.bonus);
                    $('#deduction').val(salary.deduction);
                    $('#payment_status').val(salary.payment_status);
                    $('#payment_date').val(salary.payment_date);
                    $('#notes').val(salary.notes);
                    
                    // Show/hide payment date field based on payment status
                    togglePaymentDateField();
                    
                    openModal('ems-edit-salary-modal');
                } else {
                    alert(response.data);
                }
            }
        });
    });
    
    // Toggle payment date field based on payment status
    $('#payment_status').on('change', function() {
        togglePaymentDateField();
    });
    
    function togglePaymentDateField() {
        if ($('#payment_status').val() === 'paid') {
            $('.payment-date-group').show();
            if (!$('#payment_date').val()) {
                $('#payment_date').val(getCurrentDate());
            }
        } else {
            $('.payment-date-group').hide();
        }
    }
    
    function getCurrentDate() {
        var now = new Date();
        var year = now.getFullYear();
        var month = (now.getMonth() + 1).toString().padStart(2, '0');
        var day = now.getDate().toString().padStart(2, '0');
        return year + '-' + month + '-' + day;
    }
    
    // Open export salary modal
    $('.export-salary').on('click', function(e) {
        e.preventDefault();
        openModal('ems-export-salary-modal');
    });
    
    // Open payslip modal
    $('.view-payslip').on('click', function(e) {
        e.preventDefault();
        
        var salaryId = $(this).data('id');
        
        // Load payslip data via AJAX
        $.ajax({
            url: ems_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ems_get_payslip',
                salary_id: salaryId,
                nonce: ems_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#ems-payslip').html(response.data);
                    openModal('ems-payslip-modal');
                } else {
                    alert(response.data);
                }
            }
        });
    });
    
    // Print payslip
    $('#print-payslip').on('click', function() {
        var printContents = document.getElementById('ems-payslip').innerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = '<div id="print-area">' + printContents + '</div>';
        window.print();
        document.body.innerHTML = originalContents;
        
        // Reattach event handlers after restoring content
        $(document).ready(function() {
            $('.ems-close, .ems-cancel').on('click', function() {
                $(this).closest('.ems-modal').hide();
            });
        });
    });
});