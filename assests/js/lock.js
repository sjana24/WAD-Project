let accessCode = '';
const maxLength = 4; // Changed to 4 digits as per your validation

function addDigit(digit) {
  if (accessCode.length < maxLength) {
    accessCode += digit;
    updateDisplay();
    clearError(); // Clear any existing error when user starts typing
  } else {
    // Show error when trying to add more than 4 digits
    showError('Access code must be exactly 4 digits');
  }
}

function clearCode() {
  accessCode = '';
  updateDisplay();
  clearError();
}

function backspace() {
  if (accessCode.length > 0) {
    accessCode = accessCode.slice(0, -1);
    updateDisplay();
    clearError();
  }
}

function updateDisplay() {
  const input = document.getElementById('accessCode');
  input.value = '•'.repeat(accessCode.length);
}

function submitCode() {
  // Clear any existing errors
  clearError();
  
  // Validate code length
  if (accessCode.length === 0) {
    showError('Please enter an access code');
    return;
  }
  
  if (accessCode.length < 4) {
    showError('Access code must be exactly 4 digits');
    return;
  }
  
  if (accessCode.length > 4) {
    showError('Access code cannot be more than 4 digits');
    return;
  }
  
  // Validate that all characters are digits
  if (!/^\d{4}$/.test(accessCode)) {
    showError('Access code must contain only numbers');
    return;
  }
  
  // Set the actual code value to the input for form submission
  document.getElementById('accessCode').value = accessCode;
  document.querySelector('form').submit();
}

// Show error message
function showError(message) {
  clearError(); // Remove any existing error first
  
  const errorDiv = document.createElement('div');
  errorDiv.className = 'error-message';
  errorDiv.style.cssText = `
    color: #e74c3c;
    background-color: #ffeaa7;
    border: 1px solid #e17055;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    animation: fadeIn 0.3s ease-in;
  `;
  errorDiv.textContent = message;
  
  // Insert error message after the form group
  const formGroup = document.querySelector('.form-group');
  formGroup.insertAdjacentElement('afterend', errorDiv);
  
  // Auto-hide error after 3 seconds
  setTimeout(clearError, 3000);
}

// Clear error message
function clearError() {
  const errorDiv = document.querySelector('.error-message');
  if (errorDiv) {
    errorDiv.remove();
  }
}

// Add keyboard support with validation
document.addEventListener('keydown', function(event) {
  if (event.key >= '0' && event.key <= '9') {
    event.preventDefault(); // Prevent default to avoid double input
    addDigit(event.key);
  } else if (event.key === 'Backspace' || event.key === 'Delete') {
    event.preventDefault();
    backspace();
  } else if (event.key === 'Enter') {
    event.preventDefault();
    submitCode();
  } else if (event.key === 'Escape') {
    event.preventDefault();
    clearCode();
  } else if (event.key.length === 1) {
    // Show error for invalid characters
    showError('Only numbers (0-9) are allowed');
  }
});

// Add CSS for fade-in animation
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
`;
document.head.appendChild(style);
