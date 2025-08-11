
function validateUserForm() {
    let name = document.getElementById("name").value.trim();
    let mobile = document.getElementById("mobile_number").value.trim();
    let nic = document.getElementById("nic").value.trim();

    // Name validation: only letters and spaces, at least 2 chars
    let namePattern = /^[A-Za-z\s]{2,}$/;
    if (!namePattern.test(name)) {
        alert("Name must contain only letters and spaces, and be at least 2 characters long.");
        return false;
    }

    // Mobile validation: only digits, must be 10 digits
    // let mobilePattern = /^\d{10}$/;
    // if (!mobilePattern.test(mobile)) {
    //     alert("Mobile number must be exactly 10 digits.");
    //     return false;
    // }
    let mobilePattern = /^07\d{8}$/;
if (!mobilePattern.test(mobile)) {
    alert("Mobile number must start with 07 and be exactly 10 digits.");
    return false;
}


    // NIC validation: old (9 digits + V/X) or new (12 digits)
    let nicPattern = /^(\d{9}[VXvx]|\d{12})$/;
    if (!nicPattern.test(nic)) {
        alert("NIC must be 9 digits followed by V/X, or 12 digits.");
        return false;
    }

    return true; // Passes validation
}

