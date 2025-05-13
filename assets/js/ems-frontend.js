/**
 * Frontend JavaScript for Employee Management System
 */
(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Profile form submission
        $('#ems-profile-form').on('submit', function(e) {
            e.preventDefault();
            
            var formData = {
                action: 'ems_update_profile',
                nonce: ems_frontend.nonce,
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                address: $('#address').val()
            };
            
            // Show loading state
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.text();
            submitBtn.prop('disabled', true).text(ems_frontend.i18n.loading);
            
            // Send AJAX request
            $.ajax({
                url: ems_frontend.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        var successMessage = $('<div class="ems-alert ems-alert-success"></div>').text(response.data);
                        $('#ems-profile-form').before(successMessage);
                        
                        // Remove message after 3 seconds
                        setTimeout(function() {
                            successMessage.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 3000);
                    } else {
                        // Show error message
                        var errorMessage = $('<div class="ems-alert ems-alert-danger"></div>').text(response.data);
                        $('#ems-profile-form').before(errorMessage);
                        
                        // Remove message after 3 seconds
                        setTimeout(function() {
                            errorMessage.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                },
                error: function() {
                    // Show error message
                    var errorMessage = $('<div class="ems-alert ems-alert-danger"></div>').text(ems_frontend.i18n.error);
                    $('#ems-profile-form').before(errorMessage);
                    
                    // Remove message after 3 seconds
                    setTimeout(function() {
                        errorMessage.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 3000);
                },
                complete: function() {
                    // Restore button state
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });
        
        // Download payslip button
        $('.download-payslip').on('click', function(e) {
            e.preventDefault();
            
            var salaryId = $(this).data('id');
            
            if (confirm(ems_frontend.i18n.confirm_download)) {
                window.location.href = ems_frontend.ajax_url + '?action=ems_download_payslip&nonce=' + ems_frontend.nonce + '&salary_id=' + salaryId;
            }
        });
        
        // Initialize tooltips if available
        if (typeof $.fn.tooltip === 'function') {
            $('[data-toggle="tooltip"]').tooltip();
        }
        
        // Mobile menu toggle
        $('.ems-mobile-menu-toggle').on('click', function() {
            $('.ems-dashboard-nav').toggleClass('ems-mobile-menu-open');
        });

         // Handle Attendance Form Submission
        $('#ems-attendance-form').on('submit', function(e) {
            e.preventDefault();

            let $form = $(this);
            let $submitBtn = $form.find('button[type="submit"]');
            $submitBtn.prop('disabled', true).text('Submitting...');

            let formData = $form.serializeArray();
            formData.push({ name: 'action', value: 'ems_mark_attendance' });
            formData.push({ name: 'nonce', value: ems_frontend.nonce });

            $.post(ems_frontend.ajax_url, formData, function(response) {
                $submitBtn.prop('disabled', false).text('Submit');

                if (response.success) {
                    alert(response.data.message);
                    // location.reload(); // or update UI instead
                } else {
                    alert('Error: ' + (response.data?.message || 'Failed to save attendance.'));
                }
            }).fail(function() {
                $submitBtn.prop('disabled', false).text('Submit');
                alert('Request failed. Please try again.');
            });
        });

        // Handle Export Attendance Form Submission
        $('#ems-export-form').on('submit', function(e) {
            e.preventDefault();

            let $form = $(this);
            let $submitBtn = $form.find('button[type="submit"]');
            $submitBtn.prop('disabled', true).text('Exporting...');

            let formData = $form.serializeArray();
            formData.push({ name: 'action', value: 'ems_export_attendance' });
            formData.push({ name: 'nonce', value: ems_frontend.nonce });

            $.post(ems_frontend.ajax_url, formData, function(response) {
                $submitBtn.prop('disabled', false).text('Export');

                if (response.success) {
                    window.location.href = response.data.download_url;
                } else {
                    alert('Error: ' + (response.data?.message || 'Export failed.'));
                }
            }).fail(function() {
                $submitBtn.prop('disabled', false).text('Export');
                alert('Export request failed. Please try again.');
            });
        });
    });
    

})(jQuery);