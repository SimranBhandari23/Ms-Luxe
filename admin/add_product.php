<?php include('header.php');?>


<body>

    <div class="container-fluid">
        <div class="row" style="min-height: 1000px">

            <?php include('sidemenu.php'); ?>
            <main class="cil-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2">Add Product</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2"></div>
                    </div>
                </div>

                <h2>Create Product</h2>
                <div class="table-responsive">



                    <div class="mx-auto container">
                        <form id="create-form" enctype="multipart/form-data" method="POST" action="create_product.php">
                            <p style="color: red;"><?php if (isset($_GET['error'])) {
                                echo $_GET['error'];
                            } ?></p>
                            <div class="form-group mt-2">
                                <label>Title</label>
                                <input type="text" class="form-control" id="product-name" name="name"
                                    placeholder="Title" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Description</label>
                                <input type="text" class="form-control" id="product-desc" name="description"
                                    placeholder="Description" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Price</label>
                                <input type="text" class="form-control" id="product-price" name="price"
                                    placeholder="Price" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Special Offer/Sale</label>
                                <input type="number" class="form-control" id="product-offer" name="offer"
                                    placeholder="Sale%" required>
                            </div>

                            <div class="form-group mt-2">
                                <label>Category</label>
                                <select class="form-select" required name="category">
                                <option value="featured">Featured</option>
                                    <option value="newarrival">New Arrival</option>
                                    <option value="arrival">Arrival</option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label>Color</label>
                                <input type="text" class="form-control" id="product-color" name="color"
                                    placeholder="Color" required>
                            </div>
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
                                <input type="submit" name="create_product" class="btn btn-primary" value="Create">
                            </div>

                        </form>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.2/dist/feather.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                    crossorigin="anonymous"></script>
                    <script>
    function validateForm() {
        var productName = document.getElementById("product-name").value.trim();
        var description = document.getElementById("product-desc").value.trim();
        var price = document.getElementById("product-price").value;
        var offer = document.getElementById("product-offer").value;
        var color = document.getElementById("product-color").value.trim();
        var image1 = document.getElementById("image1").files[0];
        var image2 = document.getElementById("image2").files[0];
        var image3 = document.getElementById("image3").files[0];
        var errorMessage = '';

        // Validate product name
        var namePattern = /^[a-zA-Z][a-zA-Z0-9\s]*$/;
        if (!namePattern.test(productName)) {
            errorMessage = "Invalid product name. It should start with an alphabet and not contain only numbers or special characters.";
        }

        // Validate description
        var descPattern = /^[a-zA-Z][a-zA-Z0-9\s]*$/;
        if (!descPattern.test(description)) {
            errorMessage = "Invalid description. It should start with an alphabet and not contain only numbers or special characters.";
        }

        // Validate price
        var pricePattern = /^[0-9]+(\.[0-9]{1,2})?$/;
        if (!pricePattern.test(price) || price <= 0) {
            errorMessage = "Invalid price. Only positive numbers are allowed.";
        }

        // Validate special offer
        if (!pricePattern.test(offer) || offer < 0 || offer > 100) {
            errorMessage = "Invalid special offer. Only non-negative numbers between 0 and 100 are allowed.";
        }

        // Validate color
        var colorPattern = /^[a-zA-Z]+$/;
        if (!colorPattern.test(color)) {
            errorMessage = "Invalid color. Only alphabets are allowed.";
        }

        // Allowed image types
        var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Validate each image file
        if (image1 && !allowedTypes.includes(image1.type)) {
            errorMessage = "Invalid file type for Image 1. Only JPG, PNG, and GIF are allowed.";
        }
        if (image2 && !allowedTypes.includes(image2.type)) {
            errorMessage = "Invalid file type for Image 2. Only JPG, PNG, and GIF are allowed.";
        }
        if (image3 && !allowedTypes.includes(image3.type)) {
            errorMessage = "Invalid file type for Image 3. Only JPG, PNG, and GIF are allowed.";
        }

        // Display the error message and prevent form submission if any field is invalid
        if (errorMessage) {
            alert(errorMessage); // Display an alert with the error
            return false; // Prevent form submission
        }

        return true; // Allow form submission if all fields are valid
    }
</script>
</body>