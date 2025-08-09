let accessCode = '';
const maxLength = 16;

function addDigit(digit) {
  if (accessCode.length < maxLength) {
    accessCode += digit;
    updateDisplay();
  }
}

function clearCode() {
  accessCode = '';
  updateDisplay();
}

function backspace() {
  if (accessCode.length > 0) {
    accessCode = accessCode.slice(0, -1);
    updateDisplay();
  }
}

function updateDisplay() {
  const input = document.getElementById('accessCode');
  input.value = '•'.repeat(accessCode.length);
}

function submitCode() {
  if (accessCode.length > 0) {
    // Set the actual code value to the input for form submission
    document.getElementById('accessCode').value = accessCode;
    document.querySelector('form').submit();
  }
}

// Add keyboard support
document.addEventListener('keydown', function(event) {
  if (event.key >= '0' && event.key <= '9') {
    addDigit(event.key);
  } else if (event.key === 'Backspace' || event.key === 'Delete') {
    backspace();
  } else if (event.key === 'Enter') {
    submitCode();
  } else if (event.key === 'Escape') {
    clearCode();
  }
});
