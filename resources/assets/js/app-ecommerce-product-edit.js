/**
 * App eCommerce Edit Product Script
 * This script handles all JavaScript for the Edit Product page
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
    initQuillEditor();
    initDropzone();
    initSelect2Components();
    initFlatpickr();
    initStateSwitch();
    setupDiscardButton();
});

function initQuillEditor() {
    const commentEditorElement = document.querySelector('.comment-editor');
    if (commentEditorElement) {
        const quill = new Quill(commentEditorElement, {
            modules: {
                toolbar: '.comment-toolbar'
            },
            placeholder: 'Descripción del producto',
            theme: 'snow'
        });

        // Set initial content from hidden input
        const hiddenDesc = document.getElementById('hiddenDescription');
        if (hiddenDesc) {
            quill.root.innerHTML = hiddenDesc.value;
        }

        // Update hidden input on form submit
        const form = commentEditorElement.closest('form');
        form.addEventListener('submit', function () {
            hiddenDesc.value = quill.root.innerHTML;
        });
    }
}

function initDropzone() {
    const dropzoneElement = document.querySelector('#dropzone-basic');
    if (dropzoneElement) {
        const myDropzone = new Dropzone(dropzoneElement, {
            url: '/file/post', // Set the correct server URL or route
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: document.querySelector('#preview-template').innerHTML,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files
        });

        myDropzone.on("addedfile", function (file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);  // Only one file permitted
            }
        });
    }
}

function initSelect2Components() {
  $('#productType').select2().on('change', function() {
      const isConfigurable = $(this).val() === 'configurable';
      $('#flavorsContainer').toggle(isConfigurable);
      $('#flavorsQuantityContainer').toggle(isConfigurable);
  }).trigger('change'); // Trigger to apply logic on page load

  $('#category-org, #flavorsContainer select').select2({
      placeholder: "Seleccione opciones",
      allowClear: true
  }).val(function() {
      return JSON.parse($(this).data('selected'));
  }).trigger('change');
}



function initFlatpickr() {
    const productDateElements = document.querySelectorAll('.product-date');
    productDateElements.forEach(function (productDate) {
        flatpickr(productDate, {
            dateFormat: "Y-m-d"
        });
    });
}

function initStateSwitch() {
    const statusSwitch = document.getElementById('statusSwitch');
    if (statusSwitch) {
        statusSwitch.checked = statusSwitch.value === '1';  // Assumes '1' is active, adjust as necessary
        statusSwitch.addEventListener('change', function () {
            this.value = this.checked ? '1' : '2';  // Toggle between '1' and '2'
        });
    }
}

function setupDiscardButton() {
    const discardButton = document.getElementById('discardButton');
    if (discardButton) {
        discardButton.addEventListener('click', function () {
            if (!confirm('Are you sure you want to discard your changes?')) {
                return false;
            }
            window.history.back();
        });
    }
}
