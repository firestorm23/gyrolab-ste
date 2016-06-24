window.imageUploadConfig = {
    // The URL to submit the form to (POST)
    formAction: '/image/upload_raw',

    // The name of the file field your handler checks for
    formFileFieldName: 'image',

    /*
     * If your application uses CSRF protection, use this to set up sending your 
     * token.
     */
    enableCsrfToken: false,
    /**
     * @param parentWindow The window object of the page with TinyMCE.
     * @return object Return your CSRF field name and token value in a hash.
     */
    getCsrfToken: function(parentWindow) {
        return {
            name: 'myToken',
            value: parentWindow.csrfToken
        };
    },

    /*
     * Whether or not to treat the response as JSON. If this is true, your
     * callbacks will receive a JSON object. Otherwise, they will receive the
     * raw text from the server.
     */
    responseIsJson: true,

    /**
     * @param response The return from your form handler. 
     * @return boolean Whether or not the upload succeeded.
     */
    checkSuccess: function(response) {
        // evaluate if upload was successful according to your application
        return !response['error']
    },

    /**
     * @param response The return from your form handler. 
     * @return string Error message to display to the user.
     */
    evaluateErrorMessage: function(response) {
        // return an error message to be displayed
            return response['message'];
    },

    /**
     * @param response The return from your form handler. 
     * @return string Return the image URL so we can insert it into TinyMCE.
     */
    getImageUrl: function(response) {
        return response['path']
    }
}