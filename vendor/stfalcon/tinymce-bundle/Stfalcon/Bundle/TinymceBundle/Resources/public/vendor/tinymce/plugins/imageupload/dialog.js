window.imageUpload = {
    // Taken from https://github.com/vikdiesel/justboil.me/blob/c8746cf3453c16b8a56223c016dccf189f152b1b/js/dialog-v4.js#L61
    getParentWindow: function() {
        return (!window.frameElement && window.dialogArguments) || opener || parent || top;
    },

    getTinyMce: function() {
        return this.getParentWindow().tinymce.activeEditor;
    },

    handleSubmit: function(config) {

        console.log(config);

        document.getElementById('uploadForm').style.display = 'none';
        document.getElementById('uploadProgress').style.display = 'block';

        var formData = new FormData();
        formData.append(config.formFileFieldName,
                        document.getElementById('uploadFormFile').files[0]);

        if (config.enableCsrfToken) {
            var csrfData = config.getCsrfToken(this.getParentWindow());
            formData.append(csrfData.name, csrfData.value);
        }

        var context = this,
            xhr = new XMLHttpRequest();
        xhr.open('POST', config.formAction);
        if (xhr.upload) {
            xhr.upload.addEventListener('progress', context.progressHandler, false);
        }
        xhr.send(formData);

        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) { return; }

            var response = config.responseIsJson ? JSON.parse(xhr.responseText)
                : xhr.responseText;
            if (xhr.status === 200 && config.checkSuccess(response)) {
                context.insertImage(config.getImageUrl(response));
            } else {
                context.displayError(config.evaluateErrorMessage(response));
            }
        };
    },

    insertImage: function(url) {
        var tinymce = this.getTinyMce();
        console.log(url);
        tinymce.insertContent('<img src="' + url + '">');
        tinymce.windowManager.close(window);
    },

    displayError: function(errorMessage) {
        var progressBar = document.getElementById('uploadProgress');
        progressBar.style.display = 'none';
        progressBar.setAttribute('value', '0.0');

        var errorBlock = document.getElementById('uploadError');
        errorBlock.style.display = 'block';
        errorBlock.innerHTML = errorMessage;

        document.getElementById('uploadForm').style.display = 'block';
    },

    progressHandler: function(e) {
        if (e.lengthComputable) {
            var progressBar = document.getElementById('uploadProgress');
            progressBar.setAttribute('value', e.loaded);
            progressBar.setAttribute('max', e.total);
            if (e.loaded == e.total) {
                progressBar.setAttribute('value', '0.0');
            }
        }
    }
};