// File: upload.js
// This script waits for DOMContentLoaded before attaching listeners and running logic.

document.addEventListener('DOMContentLoaded', function () {
  const isPaidCheckbox = document.getElementById('isPaid');
  const paidDetails = document.getElementById('paidDetails');
  const uploadForm = document.getElementById('uploadForm');
  const resetBtn = document.getElementById('resetBtn');

  if (!uploadForm) return; // safety

  // Show/hide paid details when checkbox toggles
  isPaidCheckbox.addEventListener('change', () => {
    if (isPaidCheckbox.checked) {
      paidDetails.classList.remove('hidden');
      // make inputs required when Paid is selected
      const price = document.getElementById('price');
      const bankName = document.getElementById('bank_name');
      const accountName = document.getElementById('account_name');
      const accountNumber = document.getElementById('account_number');

      if (price) price.setAttribute('required', 'required');
      if (bankName) bankName.setAttribute('required', 'required');
      if (accountName) accountName.setAttribute('required', 'required');
      if (accountNumber) accountNumber.setAttribute('required', 'required');
    } else {
      paidDetails.classList.add('hidden');
      // remove required attributes
      ['price','bank_name','account_name','account_number'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.removeAttribute('required');
      });
    }
  });

  // Form validation before submit (extra checks)
  uploadForm.addEventListener('submit', function (e) {
    // basic title/category/file validation are handled by HTML required attributes
    if (isPaidCheckbox.checked) {
      const priceEl = document.getElementById('price');
      const bankNameEl = document.getElementById('bank_name');
      const accountNameEl = document.getElementById('account_name');
      const accountNumberEl = document.getElementById('account_number');

      const price = parseFloat(priceEl && priceEl.value);
      if (!priceEl || isNaN(price) || price <= 0) {
        e.preventDefault();
        alert('Please enter a valid sale price greater than 0.');
        if (priceEl) priceEl.focus();
        return false;
      }

      if (!bankNameEl || !bankNameEl.value.trim() || !accountNameEl || !accountNameEl.value.trim() || !accountNumberEl || !accountNumberEl.value.trim()) {
        e.preventDefault();
        alert('Please complete all payout/bank details so we can process your seller payments.');
        return false;
      }
    }

    // Optionally disable submit button to avoid duplicate uploads
    const submitBtn = uploadForm.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
    }
  });

  // Reset handler: hide paid fields and remove required attributes
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      paidDetails.classList.add('hidden');
      if (isPaidCheckbox) isPaidCheckbox.checked = false;
      ['price','bank_name','account_name','account_number'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          el.removeAttribute('required');
          el.value = '';
        }
      });
    });
  }
});
