<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script src="https://cdn.tailwindcss.com/3.4.16"></script>
        <script>tailwind.config={theme:{extend:{colors:{primary:'#6C47FF',secondary:'#57B5E7'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :where([class^="ri-"])::before { content: "\f3c2"; }
            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .gear-animation {
                animation: rotate 10s linear infinite;
            }
            .slide-in {
                animation: slideIn 0.5s ease forwards;
            }
            @keyframes slideIn {
                from { opacity: 0; transform: translateX(30px); }
                to { opacity: 1; transform: translateX(0); }
            }
            .form-container {
                transition: all 0.5s ease;
            }
            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            .password-strength-meter {
                height: 5px;
                border-radius: 3px;
                transition: width 0.3s ease;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle password visibility
                const togglePasswordButtons = document.querySelectorAll('.toggle-password');
                togglePasswordButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const passwordField = this.parentElement.querySelector('input');
                        const icon = this.querySelector('i');
                        if (passwordField.type === 'password') {
                            passwordField.type = 'text';
                            icon.classList.remove('ri-eye-off-line');
                            icon.classList.add('ri-eye-line');
                        } else {
                            passwordField.type = 'password';
                            icon.classList.remove('ri-eye-line');
                            icon.classList.add('ri-eye-off-line');
                        }
                    });
                });

                // Custom checkbox functionality
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkIcon = this.parentElement.querySelector('.custom-checkbox i');
                        const checkboxBg = this.parentElement.querySelector('.custom-checkbox');
                        if (this.checked) {
                            checkIcon.classList.remove('hidden');
                            checkboxBg.classList.add('bg-primary');
                            checkboxBg.classList.remove('bg-white');
                        } else {
                            checkIcon.classList.add('hidden');
                            checkboxBg.classList.remove('bg-primary');
                            checkboxBg.classList.add('bg-white');
                        }
                    });

                    // Set initial state for checkboxes
                    checkbox.parentElement.querySelector('.custom-checkbox').addEventListener('click', function() {
                        checkbox.checked = !checkbox.checked;
                        const event = new Event('change');
                        checkbox.dispatchEvent(event);
                    });
                });

                // Reset password form handling
                const resetButton = document.getElementById('reset-button');
                if (resetButton) {
                    resetButton.addEventListener('click', function() {
                        const resetForm = document.querySelector('#forgot-form form');
                        const successMessage = document.getElementById('reset-success');

                        // Show loading state
                        resetButton.innerHTML = '<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div> Sending...';
                        resetButton.disabled = true;

                        // Simulate API call
                        setTimeout(() => {
                            resetForm.classList.add('hidden');
                            successMessage.classList.remove('hidden');
                            successMessage.classList.add('slide-in');
                        }, 1500);
                    });
                }
            });

            // Form switching functionality
            function showForm(formId) {
                const forms = document.querySelectorAll('.form-container');
                forms.forEach(form => {
                    form.classList.add('hidden');
                    form.classList.remove('slide-in');
                });

                const targetForm = document.getElementById(formId);
                targetForm.classList.remove('hidden');

                // Trigger reflow for animation
                void targetForm.offsetWidth;
                targetForm.classList.add('slide-in');

                // Reset password success message if switching away from forgot form
                if (formId !== 'forgot-form') {
                    const resetForm = document.querySelector('#forgot-form form');
                    const successMessage = document.getElementById('reset-success');
                    const resetButton = document.getElementById('reset-button');

                    if (resetForm && successMessage && resetButton) {
                        resetForm.classList.remove('hidden');
                        successMessage.classList.add('hidden');
                        resetButton.innerHTML = 'Send Reset Link';
                        resetButton.disabled = false;
                    }
                }
            }

            // Password strength checker
            function checkPasswordStrength() {
                const password = document.getElementById('register-password').value;
                const strengthBar = document.getElementById('password-strength');
                const strengthText = document.getElementById('password-strength-text');

                // Calculate strength
                let strength = 0;
                if (password.length >= 8) strength += 25;
                if (password.match(/[a-z]+/)) strength += 25;
                if (password.match(/[A-Z]+/)) strength += 25;
                if (password.match(/[0-9]+/) || password.match(/[^a-zA-Z0-9]+/)) strength += 25;

                // Update UI
                strengthBar.style.width = strength + '%';

                if (strength <= 25) {
                    strengthBar.className = 'password-strength-meter bg-red-500';
                    strengthText.textContent = 'Password strength: Too weak';
                    strengthText.className = 'text-xs text-red-500 mt-1';
                } else if (strength <= 50) {
                    strengthBar.className = 'password-strength-meter bg-orange-500';
                    strengthText.textContent = 'Password strength: Weak';
                    strengthText.className = 'text-xs text-orange-500 mt-1';
                } else if (strength <= 75) {
                    strengthBar.className = 'password-strength-meter bg-yellow-500';
                    strengthText.textContent = 'Password strength: Good';
                    strengthText.className = 'text-xs text-yellow-600 mt-1';
                } else {
                    strengthBar.className = 'password-strength-meter bg-green-500';
                    strengthText.textContent = 'Password strength: Strong';
                    strengthText.className = 'text-xs text-green-600 mt-1';
                }
            }
        </script>
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            @yield('content')
        </div>
    </body>
</html>
