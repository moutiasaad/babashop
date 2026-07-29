$(document).ready(function () {
    $("#fileInput").on("change", function (e) {
        handleFileSelect(e.target.files);
    });

    $("#image-container").on("click", ".delete-btn", function () {
        $(this).closest(".uploaded-imageN").remove();
    });

    function handleFileSelect(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const imageUrl = e.target.result;
                    const fileName = file.name;

                    const imageElement = $(`<li class="uploaded-imageN"><div class="upload-files-single"><img src="${imageUrl}" alt="Uploaded Image"></img> <p> ${fileName} </p> <span>التوجه للموقع <i class="fa-solid fa-arrow-left-long"></i></span></div></li>`);

                    $("#image-container").empty();
                    $("#image-container").append(imageElement);
                };

                reader.readAsDataURL(file);
            }
        }
    }
});



$(document).ready(function () {
    $("#fileInput2").on("change", function (e) {
        handleFileSelect2(e.target.files);
    });

    $("#image-container2").on("click", ".delete-btn", function () {
        $(this).closest(".uploaded-imageN").remove();
    });

    function handleFileSelect2(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const imageUrl2 = e.target.result;
                    const fileName2 = file.name;

                    const imageElement2 = $(`<li class="uploaded-imageN"> <div class="upload-files-single"><i class="icon-icon-13"></i> <span> ${fileName2} </span> <i class="icon-icon-17 error delete-btn"></i></div></li>`);
                    // <img src="' + imageUrl + '" alt="Uploaded Image"></img>
                    $("#image-container2").append(imageElement2);
                };

                reader.readAsDataURL(file);
            }
        }
    }
});
