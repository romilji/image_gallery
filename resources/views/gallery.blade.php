<!DOCTYPE html>
<style>
    .gallery-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    justify-items: center;
}

.image-item {
    width: 100%;
    max-width: 200px;
    text-align: center;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.image-item img {
    width: 100%;
    height: auto;
    margin-bottom: 10px;
}
#successMessage {
    display: none;
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    padding: 15px;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
#DeleteMessage {
    display: none;
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    padding: 15px;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.edit-btn {
    background-color: blue;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

.delete-btn {
    background-color: red;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}
.save-btn{
    background-color: green;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer; 
    margin-top: 20px;
}

.edit-btn i, .delete-btn i {
    margin-right: 5px;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/dropzone.min.css">
</head>
<body>
    <h1>Upload Images</h1>
    <form action="{{ route('image.store') }}" style="width:50%" method="POST" class="dropzone" id="image-dropzone" enctype="multipart/form-data">
        @csrf
    </form>
    <button id="save-images" class="btn btn-primary save-btn">Save Images</button>
 <hr>
    <h1>Gallery</h1>
    <div id="gallery" class="gallery-grid">
    @foreach($images as $image)
        <div class="image-item">
            <img src="{{ $image->image_url }}" width="200" height="100" alt="{{ $image->title }}">
            <h3>Title: {{ $image->title }}</h3>
            <p>Tag: {{ $image->tag }}</p>
            <button class="edit-btn" data-id="{{ $image->id }}">
                <i class="fas fa-edit"></i> Edit
            </button>

            <button class="delete-btn" data-id="{{ $image->id }}">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
        </div>
    @endforeach
</div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/dropzone.min.js"></script>
</body>
</html>
<div id="successMessage">Images saved successfully!</div>
<div id="DeleteMessage">Images Deleted successfully!</div>
<script>
Dropzone.options.imageDropzone = {
    autoProcessQueue: false, // Prevent automatic upload
    uploadMultiple: true,    // Allow multiple file uploads
    parallelUploads: 10,     // Handle multiple parallel uploads
    maxFiles: 10,            // Set max files allowed
    acceptedFiles: 'image/*', // Accept images only
    
    init: function () {
        var myDropzone = this;

        // Handle save button click
        document.getElementById("save-images").addEventListener("click", function (e) {
            e.preventDefault();
            
            if (myDropzone.getQueuedFiles().length > 0) {
                myDropzone.processQueue(); // Manually process the file queue
            } else {
                alert("Please upload images before saving.");
            }
        });

        // After successful uploads
        myDropzone.on("successmultiple", function (files, response) {
            var successMessage = document.getElementById('successMessage');
            successMessage.style.display = 'block';
            setTimeout(function() {
                successMessage.style.display = 'none';
                location.reload();
            }, 1000);

           
            // Optionally, you can redirect or update the UI here
        });

        // Handle error on uploads
        myDropzone.on("errormultiple", function (files, response) {
            alert("There was an error uploading the images.");
        });
    }
};




    document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-btn')) {
        let imageId = e.target.dataset.id;
        let newTitle = prompt('Enter new title:');
        let newTag = prompt('Enter new tag:');
        if (newTitle === null) {
        return; 
        }
        if (newTag === null) {
        return; 
        }
        
        fetch(`/update/${imageId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ title: newTitle, tag: newTag })
        }).then(response => response.json()).then(data => {
            // Handle response
            location.reload();
        });
    }

    if (e.target.classList.contains('delete-btn')) {
        let imageId = e.target.dataset.id;
        fetch(`/delete/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => response.json()).then(data => {
            // Handle response
            var successMessage = document.getElementById('DeleteMessage');
            successMessage.style.display = 'block';
            setTimeout(function() {
                successMessage.style.display = 'none';
                location.reload();
            }, 1000);
        });
    }
});

</script>