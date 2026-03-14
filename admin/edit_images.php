<?php 
include('header.php');
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product_name = $_GET['product_name'];
} else {
    header('location:products.php');
    exit;
}
$error_message = "";

if (isset($_POST['update_images'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $error_message = "";

    // Validate each image file
    foreach (['image1', 'image2', 'image3'] as $imageKey) {
        if ($_FILES[$imageKey]['error'] == 0) {
            $fileType = $_FILES[$imageKey]['type'];
            $tmpName = $_FILES[$imageKey]['tmp_name'];

            if (!in_array($fileType, $allowedTypes)) {
                $error_message = "Invalid file type for {$imageKey}. Only JPG, PNG, and GIF are allowed.";
                break;
            }

            // Additional check to ensure files are images
            if (!getimagesize($tmpName)) {
                $error_message = "Invalid file type for {$imageKey}. Only image files are allowed.";
                break;
            }
        }
    }

    if (empty($error_message)) {
        // Process the images and update the database
        // Assuming you have a function to handle image uploads and database updates
        $uploadSuccess = uploadAndSaveImages($product_id, $_FILES);

        if ($uploadSuccess) {
            header('location:products.php?update_success=Images updated successfully');
            exit;
        } else {
            $error_message = "Failed to update images. Please try again.";
        }
    }
}

function uploadAndSaveImages($product_id, $files) {
    // Your logic to handle image uploads and save them to the database
    // This is just a placeholder function. You need to implement the actual logic.
    return true; // Return true if successful, false otherwise
}
?>

<div class="container-fluid">
    <div class="row" style="min-height:1000px;">
        <?php include('sidemenu.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-9 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2"></div>
                </div>
            </div>
            <h2>Update Product Image</h2>
            <div class="table-responsive">
                <div class="mx-auto container">
                    <form id="edit-image-form" enctype="multipart/form-data" method="POST" action="update_image.php" onsubmit="return validateImageForm()">
                        <p id="error-message" style="color: red;"><?php if (isset($error_message)) { echo $error_message; } ?></p>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                        <div class="form-group mt-2">
                            <label>Image 1</label>
                            <input type="file" class="form-control" id="image1" name="image1" required accept="image/jpeg, image/png, image/gif">
                        </div>
                        <div class="form-group mt-2">
                            <label>Image 2</label>
                            <input type="file" class="form-control" id="image2" name="image2" required accept="image/jpeg, image/png, image/gif">
                        </div>
                        <div class="form-group mt-2">
                            <label>Image 3</label>
                            <input type="file" class="form-control" id="image3" name="image3" required accept="image/jpeg, image/png, image/gif">
                        </div>
                        
                        <div class="form-group mt-3">
                            <input type="submit" name="update_images" class="btn btn-primary" value="Edit">
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.2/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
function validateImageForm() {
    let image1 = document.getElementById("image1").files[0];
    let image2 = document.getElementById("image2").files[0];
    let image3 = document.getElementById("image3").files[0];
    let errorMessage = document.getElementById("error-message");
    
    // Clear previous error messages
    errorMessage.innerHTML = "";
    
    // Allowed image types
    let allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    // Validate each image file
    if (image1 && !allowedTypes.includes(image1.type)) {
        errorMessage.innerHTML = "Invalid file type for Image 1. Only JPG, PNG, and GIF are allowed.";
        return false;
    }
    if (image2 && !allowedTypes.includes(image2.type)) {
        errorMessage.innerHTML = "Invalid file type for Image 2. Only JPG, PNG, and GIF are allowed.";
        return false;
    }
    if (image3 && !allowedTypes.includes(image3.type)) {
        errorMessage.innerHTML = "Invalid file type for Image 3. Only JPG, PNG, and GIF are allowed.";
        return false;
    }
    
    // Additional check to ensure files are images
    if (image1 && !isImageFile(image1)) {
        errorMessage.innerHTML = "Invalid file type for Image 1. Only image files are allowed.";
        return false;
    }
    if (image2 && !isImageFile(image2)) {
        errorMessage.innerHTML = "Invalid file type for Image 2. Only image files are allowed.";
        return false;
    }
    if (image3 && !isImageFile(image3)) {
        errorMessage.innerHTML = "Invalid file type for Image 3. Only image files are allowed.";
        return false;
    }
    
    return true; // Allow form submission
}

function isImageFile(file) {
    return file.type.startsWith('image/');
}
</script>