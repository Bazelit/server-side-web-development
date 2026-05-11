// Form submission handler
document.addEventListener('DOMContentLoaded', function() {
    const feedbackForm = document.getElementById('feedbackForm');
    
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            // The form will submit to httpbin.org/post
            // This is allowed by the assignment
            console.log('Form submitted to:', this.action);
        });
    }
});

// Utility function to format form data
function getFormData(form) {
    const formData = new FormData(form);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        feedbackType: formData.get('feedbackType'),
        message: formData.get('message'),
        responseMethod: formData.getAll('response_method')
    };
    return data;
}
