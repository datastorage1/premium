document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');
    const verifyIdentityForm = document.getElementById('verifyIdentityForm');
    const newPasswordForm = document.getElementById('newPasswordForm');
    const resetPasswordSection = document.getElementById('resetPasswordSection');

    // Set server connected status on all pages
    const serverStatus = document.getElementById('serverStatus');
    if (serverStatus) {
        serverStatus.className = 'server-status connected';
        serverStatus.innerHTML = '✓ Local storage mode active';
    }

    // Enable all buttons
    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) loginBtn.disabled = false;
    
    const signupBtn = document.getElementById('signupBtn');
    if (signupBtn) signupBtn.disabled = false;

    // Initialize users array in localStorage if it doesn't exist
    if (!localStorage.getItem('users')) {
        localStorage.setItem('users', JSON.stringify([]));
    }

    // Initialize files array in localStorage if it doesn't exist
    if (!localStorage.getItem('files')) {
        localStorage.setItem('files', JSON.stringify([]));
    }

    if (signupForm) {
        signupForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const fullName = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const dob = document.getElementById('dob').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (!fullName || !email || !dob || !password || !confirmPassword) {
                alert('Please fill in all fields.');
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            // Get existing users
            const users = JSON.parse(localStorage.getItem('users'));
            
            // Check if user already exists
            if (users.some(user => user.email === email)) {
                alert('User with this email already exists. Please use a different email.');
                return;
            }

            // Create new user with ID
            const newUser = {
                id: Date.now().toString(),
                fullName,
                email,
                dob,
                password,
                createdAt: new Date().toISOString()
            };
            
            // Add user to array and save
            users.push(newUser);
            localStorage.setItem('users', JSON.stringify(users));

            alert('Signup successful! Redirecting to login page.');
            window.location.href = 'index.html';
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                alert('Please fill in both email and password.');
                return;
            }

            // Get users from localStorage
            const users = JSON.parse(localStorage.getItem('users'));
            
            // Find user with matching email and password
            const user = users.find(user => user.email === email && user.password === password);
            
            if (user) {
                // Store user data in session storage (excluding password)
                const userData = {
                    id: user.id,
                    fullName: user.fullName,
                    email: user.email,
                    dob: user.dob
                };
                
                sessionStorage.setItem('user', JSON.stringify(userData));
                alert('Login success!');
                window.location.href = 'main.html';
            } else {
                alert('Invalid email or password. Please try again.');
            }
        });
    }

    if (verifyIdentityForm) {
        verifyIdentityForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const email = verifyIdentityForm.querySelector('#email').value;
            const dob = verifyIdentityForm.querySelector('#dob').value;

            if (!email || !dob) {
                alert('Please enter both email and date of birth.');
                return;
            }

            // Get users from localStorage
            const users = JSON.parse(localStorage.getItem('users'));
            
            // Find user with matching email and date of birth
            const user = users.find(user => user.email === email && user.dob === dob);
            
            if (user) {
                alert('Identity verified. Please set your new password.');
                document.getElementById('userEmailToReset').value = email;
                resetPasswordSection.style.display = 'block';
                verifyIdentityForm.style.display = 'none';
            } else {
                alert('Email or Date of Birth does not match our records.');
            }
        });
    }

    if (newPasswordForm) {
        newPasswordForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const emailToReset = document.getElementById('userEmailToReset').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

            if (!newPassword || !confirmNewPassword) {
                alert('Please fill in both new password fields.');
                return;
            }

            if (newPassword !== confirmNewPassword) {
                alert('New passwords do not match!');
                return;
            }

            // Get users from localStorage
            const users = JSON.parse(localStorage.getItem('users'));
            
            // Find index of user with matching email
            const userIndex = users.findIndex(user => user.email === emailToReset);
            
            if (userIndex !== -1) {
                // Update password
                users[userIndex].password = newPassword;
                localStorage.setItem('users', JSON.stringify(users));
                
                alert('Password has been reset successfully! Please login.');
                window.location.href = 'index.html';
            } else {
                alert('Error: Could not find user account for password reset.');
            }
        });
    }
}); 