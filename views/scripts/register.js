// Client-side validation: confirm password and email pattern
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
    const pwd = document.getElementById('regPassword').value.trim();
    const cpwd = document.getElementById('confirmPassword').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    if (pwd !== cpwd) {
        e.preventDefault();
        alert('Passwords do not match.');
        return;
    }
    // Basic email pattern check
    const re = /\S+@\S+\.\S+/;
    if (!re.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
    });
}