function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = fieldId === 'password' ? document.getElementById('toggleIconPassword') : document.getElementById('toggleIconConfirm');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function selectRole(role) {
    // Remove selected class from all cards
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    event.currentTarget.classList.add('selected');

    // Check the radio button
    document.getElementById('role_' + role).checked = true;
}

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');

    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;

    switch (strength) {
        case 0:
        case 1:
            strengthBar.style.width = '20%';
            strengthBar.style.backgroundColor = '#ef4444';
            strengthText.textContent = 'Kekuatan password: Sangat Lemah';
            strengthText.style.color = '#ef4444';
            break;
        case 2:
            strengthBar.style.width = '40%';
            strengthBar.style.backgroundColor = '#f97316';
            strengthText.textContent = 'Kekuatan password: Lemah';
            strengthText.style.color = '#f97316';
            break;
        case 3:
            strengthBar.style.width = '60%';
            strengthBar.style.backgroundColor = '#eab308';
            strengthText.textContent = 'Kekuatan password: Cukup';
            strengthText.style.color = '#eab308';
            break;
        case 4:
            strengthBar.style.width = '80%';
            strengthBar.style.backgroundColor = '#84cc16';
            strengthText.textContent = 'Kekuatan password: Kuat';
            strengthText.style.color = '#84cc16';
            break;
        case 5:
            strengthBar.style.width = '100%';
            strengthBar.style.backgroundColor = '#22c55e';
            strengthText.textContent = 'Kekuatan password: Sangat Kuat';
            strengthText.style.color = '#22c55e';
            break;
    }
}

// Set default role if exists
document.addEventListener('DOMContentLoaded', function () {
    const selectedRole = document.querySelector('input[name="role"]:checked');
    if (selectedRole) {
        selectedRole.parentElement.classList.add('selected');
    }
});

// Form validation feedback
document.getElementById('registerForm').addEventListener('submit', function (e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    submitBtn.disabled = true;
});
