tinymce.init({
    selector: '.mytextarea',
    plugins: 'image code',
    height: 400,
    image_title: true,
    images_upload_base_path: ($('#baseurl').val())+'uploads/images/',
    images_upload_url: ($('#baseurl').val())+'tinymcimageupload',
    file_picker_types: 'image',
    relative_urls: false,
    remove_script_host: false,
    convert_urls: true,
    file_picker_callback: function(cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        // Note: In modern browsers input[type="file"] is functional without 
        // even adding it to the DOM, but that might not be the case in some older
        // or quirky browsers like IE, so you might want to add it to the DOM
        // just in case, and visually hide it. And do not forget do remove it
        // once you do not need it anymore.

        input.onchange = function() {
            var file = this.files[0];

            // Note: Now we need to register the blob in TinyMCEs image blob
            // registry. In the next release this part hopefully won't be
            // necessary, as we are looking to handle it internally.
            var id = 'blobid' + (new Date()).getTime();
            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
            var blobInfo = blobCache.create(id, file);
            blobCache.add(blobInfo);

            // call the callback and populate the Title field with the file name
            cb(blobInfo.blobUri(), { title: file.name });
        };

        input.click();
    },
    autoresize_min_height: 400,
    autoresize_max_height: 800
});


function timyLoad() {
    tinymce.init({
        selector: '.mytextarea',
        plugins: 'image code',
        height: 400,
        image_title: true,
        images_upload_base_path: 'http://vtdesignz.co/dev/ci/online_education/uploads/images/',
        images_upload_url: 'http://vtdesignz.co/dev/ci/online_education/tinymcimageupload',
        file_picker_types: 'image',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            // Note: In modern browsers input[type="file"] is functional without 
            // even adding it to the DOM, but that might not be the case in some older
            // or quirky browsers like IE, so you might want to add it to the DOM
            // just in case, and visually hide it. And do not forget do remove it
            // once you do not need it anymore.

            input.onchange = function() {
                var file = this.files[0];

                // Note: Now we need to register the blob in TinyMCEs image blob
                // registry. In the next release this part hopefully won't be
                // necessary, as we are looking to handle it internally.
                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var blobInfo = blobCache.create(id, file);
                blobCache.add(blobInfo);

                // call the callback and populate the Title field with the file name
                cb(blobInfo.blobUri(), { title: file.name });
            };

            input.click();
        },
        autoresize_min_height: 400,
        autoresize_max_height: 800
    });

}