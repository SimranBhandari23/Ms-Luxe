<?php include('header.php'); ?>

<?php 

    if(isset($_GET['product_id'])){
        $product_id=$_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param('i',$product_id);

    $stmt->execute();
    $products = $stmt->get_result();

    }else if(isset($_POST['edit_btn'])){

        $product_id = $_POST['product_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $offer = $_POST['offer'];
        $color = $_POST['color'];
        $category = $_POST['category'];

        // Validate product name
        if (empty($title) || !preg_match('/^[a-zA-Z][a-zA-Z0-9\s]/', $title) || preg_match('/^[0-9]+$/', $title) || preg_match('/^[^a-zA-Z]+$/', $title)) {
            header('location:edit_product.php?error=Title is invalid, Use Proper Title&product_id='.$product_id);
            exit;
        }

        // Validate description
        if (empty($description) || !preg_match('/^[a-zA-Z][a-zA-Z0-9\s]/', $description) || preg_match('/^[0-9]+$/', $description) || preg_match('/^[^a-zA-Z]+$/', $description)) {
            header('location:edit_product.php?error=Description is invalid, Use Proper Description&product_id='.$product_id);
            exit;
        }

        // Validate price
        if (!is_numeric($price) || $price <= 0) {
            header('location:edit_product.php?error=Price must be a positive number&product_id='.$product_id);
            exit;
        }

        // Validate offer
        if (!is_numeric($offer) || $offer < 0 || $offer > 100) {
            header('location:edit_product.php?error=Offer must be a number between 0 and 100&product_id='.$product_id);
            exit;
        }

        // Validate category
        $allowed_categories = ['featured', 'newarrival', 'arrival'];
        if (!in_array($category, $allowed_categories)) {
            header('location:edit_product.php?error=Invalid category selected&product_id='.$product_id);
            exit;
        }

        // Validate color
        $allowed_colors = ['aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure',
        'beige', 'bisque', 'black', 'blanchedalmond', 'blue',
        'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse',
        'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson',
        'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray',
        'darkgreen', 'darkgrey', 'darkkhaki', 'darkmagenta', 'darkolivegreen',
        'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen',
        'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise',
        'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey',
        'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia',
        'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray',
        'green', 'greenyellow', 'grey', 'honeydew', 'hotpink',
        'indianred', 'indigo', 'ivory', 'khaki', 'lavender',
        'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue',
        'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray',
        'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon',
        'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey',
        'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen',
        'magenta', 'maroon', 'mediumaquamarine', 'mediumblue',
        'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue',
        'mediumspringgreen', 'mediumturquoise', 'mediumvioletred',
        'midnightblue', 'mintcream', 'mistyrose', 'moccasin',
        'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab',
        'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen',
        'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff',
        'peru', 'pink', 'plum', 'powderblue', 'purple', 'rebeccapurple',
        'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon',
        'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver',
        'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow',
        'springgreen', 'steelblue', 'tan', 'teal', 'thistle',
        'tomato', 'turquoise', 'violet', 'wheat', 'white',
        'whitesmoke', 'yellow', 'yellowgreen'];
        if (empty($color) || !in_array(strtolower($color), $allowed_colors)) {
            header('location:edit_product.php?error=Color is invalid&product_id='.$product_id);
            exit;
        }

        $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_description = ?, product_price = ?, product_special_offer = ?, product_color = ?, product_category = ? WHERE product_id = ?");
        $stmt->bind_param('ssssssi', $title, $description, $price, $offer, $color, $category, $product_id);
        if($stmt->execute()){
            header('location:products.php?edit_success_message=Product has been updated successfully');
        }else{
            header('location:products.php?edit_failure_message=Error occurred, Try again');
        }

    }else{
        header('location:products.php');
        exit;
    }

?>

<div class="container-fluid">
    <div class="row" style="min-height:1000px">
        <?php include('sidemenu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                    </div>
                </div>
            </div>

            <h2>Edit Products</h2>
            <div class="table-responsive">
                <div class="mx-auto container">
                    <form id="edit-form" method="POST" action="edit_product.php" onsubmit="return validateForm()">
                        <p style="color: red;"><?php if (isset($_GET['error'])) { echo $_GET['error']; } ?></p>

                        <div class="form-group mt-2">
                        <?php foreach($products as $product) { ?>
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <label>Title</label>
                            <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name'] ?>" name="title" placeholder="Title" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Description</label>
                            <input type="text" class="form-control" id="product-desc" value="<?php echo $product['product_description'] ?>" name="description" placeholder="Description" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Price</label>
                            <input type="text" class="form-control" id="product-price" value="<?php echo $product['product_price'] ?>" name="price" placeholder="Price" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Category</label>
                            <select class="form-select" required name="category">
                                <option value="featured" <?php if($product['product_category'] == 'featured') echo 'selected'; ?>>Featured</option>
                                <option value="newarrival" <?php if($product['product_category'] == 'newarrival') echo 'selected'; ?>>New Arrival</option>
                                <option value="arrival" <?php if($product['product_category'] == 'arrival') echo 'selected'; ?>>Arrival</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Color</label>
                            <input type="text" class="form-control" id="product-color" value="<?php echo $product['product_color'] ?>" name="color" placeholder="Color" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Special Offer/Sale</label>
                            <input type="text" class="form-control" id="product_offer" value="<?php echo $product['product_special_offer'] ?>" name="offer" placeholder="Sale %" required>
                        </div>
                        
                        <div class="form-group mt-3">
                            <input type="submit" name="edit_btn" class="btn btn-primary" value="Edit">
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
    </div>
    <script>
    function validateForm() {
        var productName = document.getElementById("product-name").value.trim();
        var description = document.getElementById("product-desc").value.trim();
        var price = document.getElementById("product-price").value;
        var offer = document.getElementById("product_offer").value;
        var color = document.getElementById("product-color").value.trim();
        var errorMessage = '';

        // Validate product name
        var namePattern = /^[a-zA-Z][a-zA-Z0-9\s]*$/;
        if (!namePattern.test(productName) || /^[0-9]+$/.test(productName) || /^[^a-zA-Z]+$/.test(productName)) {
            errorMessage = "Invalid product name. It should start with an alphabet and not contain only numbers or special characters.";
        }

        // Validate description
        var descPattern = /^[a-zA-Z][a-zA-Z0-9\s]*$/;
        if (!descPattern.test(description) || /^[0-9]+$/.test(description) || /^[^a-zA-Z]+$/.test(description)) {
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

        // Display the error message and prevent form submission if any field is invalid
        if (errorMessage) {
            alert(errorMessage); // Display an alert with the error
            return false; // Prevent form submission
        }

        return true; // Allow form submission if all fields are valid
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.2/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>