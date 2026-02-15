/**
 * NetFusion Authentication & Security JS
 */

"use strict";

$(document).ready(function () {
    // --- Toggle Password Visibility ---
    // Universal Handler using Event Delegation
    // Works for any input group where the icon has class 'password-toggle-icon' and 'data-target' attribute.
    $(document).on('click', '.password-toggle-icon', function (e) {
        e.preventDefault();
        const icon = $(this).find('i');
        const inputId = $(this).data('target');
        const input = $('#' + inputId);

        if (input.length === 0) return;

        if (input.attr("type") === "text") {
            input.attr('type', 'password');
            icon.addClass("bi-eye-slash-fill").removeClass("bi-eye-fill");
        } else {
            input.attr('type', 'text');
            icon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
        }
    });

    // --- Google Recaptcha v3 Handler ---
    const recaptchaKey = $('meta[name="recaptcha-key"]').attr('content');
    const loginForm = $('form[action*="login"]');

    if (recaptchaKey && typeof grecaptcha !== "undefined") {
        grecaptcha.ready(function () {
            grecaptcha.execute(recaptchaKey, { action: 'login' }).then(function (token) {
                const recaptchaInput = document.getElementById('g-recaptcha-response');
                if (recaptchaInput) {
                    recaptchaInput.value = token;
                }
            });
        });
    }
});
