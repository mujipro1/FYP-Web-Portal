
document.addEventListener('DOMContentLoaded', function() {
    var errorMessage = document.querySelector('.alert');
    if(errorMessage != null){
        errorMessage.classList.add('show');
        var duration = 9000; 
        setTimeout(function() {
            errorMessage.classList.remove('show');
        }, duration);
    }
  });