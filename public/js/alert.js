function showAlert(message, type = 'success') {
    // Create alert div
    var alertDiv = document.getElementById('alertDiv');
    alertDiv.textContent = message;
    alertDiv.classList.add('show');
    alertDiv.classList.add('d-flex');
    alertDiv.classList.remove('fade')
    alertDiv.classList.remove('d-none')

    alertDiv.classList.add(`${type}`);
    
    // Append alert to body or a specific container
    document.body.appendChild(alertDiv);
    
    // Remove the alert after the specified duration
    setTimeout(function() {
        alertDiv.classList.add('fade')
        alertDiv.classList.remove('show')
        setTimeout(function() {
            alertDiv.classList.remove('d-flex')
            alertDiv.classList.add('d-none')
            alertDiv.classList.remove(`${type}`)
        }, 1000);
    }, 2000);
}
