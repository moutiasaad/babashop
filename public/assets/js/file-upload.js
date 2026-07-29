// #fileInput function ----------------------

$(document).ready(function () {
    $("#fileInput").on("change", function (e) { 
    handleFileSelect(e.target.files);
    });

    $("#image-container").on("click", ".delete-btn", function () {
    $(this).closest(".uploaded-imagen").remove();
    $("#fileInput").val(""); // Clear the file input
    });

    function handleFileSelect(files) {
    if (files.length > 0) {
        const file = files[0]; // Only consider the first file if multiple files are selected
        if (file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const imageUrl = e.target.result;
            const imageElement = $('<div class="uploaded-imagen"><img src="' + imageUrl + '" alt="Uploaded Image"><span class="image-close delete-btn"><i class="fa-solid fa-circle-xmark"></i></span></div>');

            // Clear existing images and append the new one
            $("#image-container").empty().append(imageElement);
        };

        reader.readAsDataURL(file);
        } else {
        alert("Please select a valid image file.");
        $("#fileInput").val(""); // Clear the file input if an invalid file is selected
        }
    }
    }
});