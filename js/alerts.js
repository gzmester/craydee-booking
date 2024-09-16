// Function to show success alerts
function showSuccessAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        confirmButtonText: 'OK'
    });
}

// Function to show error alerts
function showErrorAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        confirmButtonText: 'OK'
    });
}

// Function to show information alerts
function showInfoAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

// Function to show a success toast in the top-right corner
function showSuccessToast(message) {
    Swal.fire({
        toast: true,
        position: 'top-right',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}

// Function to show an error toast in the bottom-left corner
function showErrorToast(message) {
    Swal.fire({
        toast: true,
        position: 'bottom-left',
        icon: 'error',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}

// Function to show an information toast in the bottom-right corner
function showInfoToast(message) {
    Swal.fire({
        toast: true,
        position: 'bottom-right',
        icon: 'info',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}

// Function to show a warning toast in the top-left corner
function showWarningToast(message) {
    Swal.fire({
        toast: true,
        position: 'top-left',
        icon: 'warning',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}
