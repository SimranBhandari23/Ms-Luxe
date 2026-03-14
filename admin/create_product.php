<?php

include 'header.php';
include '../server/connection.php';

if (isset($_POST['create_product'])) {

    $product_name          = $_POST['name'];
    $product_description   = $_POST['description'];
    $product_price         = $_POST['price'];
    $product_special_offer = $_POST['offer'];
    $product_category      = $_POST['category'];
    $product_color         = $_POST['color'];

    // Validate product name
    if (empty($product_name) || !preg_match('/^[a-zA-Z][a-zA-Z0-9\s]*$/', $product_name)) {
        header('location:add_product.php?error=Title is invalid, Use Proper Title');
        exit;
    }

    // Validate description
    if (empty($product_description) || !preg_match('/^[a-zA-Z][a-zA-Z0-9\s]/', $product_description) || preg_match('/^[0-9]+$/', $product_description)) {
        header('location:add_product.php?error=Description is invalid, Use Proper Description');
        exit;
    }


    // Validate price
    if (!is_numeric($product_price) || $product_price <= 0) {
        header('location:add_product.php?error=Price must be a positive number');
        exit;
    }

    // Validate offer
    if (!is_numeric($product_special_offer) || $product_special_offer < 0 || $product_special_offer > 100) {
        header('location:add_product.php?error=Offer must be a number between 0 and 100');
        exit;
    }

    // Validate category
    $allowed_categories = ['featured', 'newarrival', 'arrival'];
    if (!in_array($product_category, $allowed_categories)) {
        header('location:add_product.php?error=Invalid category selected');
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
if (empty($product_color) || ! in_array(strtolower($product_color), $allowed_colors)) {
    header('location:add_product.php?error=Color is invalid');
    exit;
}

    // Validate images
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    foreach (['image1', 'image2', 'image3'] as $imageKey) {
        if ($_FILES[$imageKey]['error'] == 0) {
            $fileType = $_FILES[$imageKey]['type'];
            $tmpName = $_FILES[$imageKey]['tmp_name'];

            if (!in_array($fileType, $allowedTypes)) {
                header("location:add_product.php?error=Invalid file type for {$imageKey}. Only JPG, PNG, and GIF are allowed.");
                exit;
            }

            // Additional check to ensure files are images
            if (!getimagesize($tmpName)) {
                header("location:add_product.php?error=Invalid file type for {$imageKey}. Only image files are allowed.");
                exit;
            }
        }
    }

    // Image files
    $image1 = $_FILES['image1']['tmp_name'];
    $image2 = $_FILES['image2']['tmp_name'];
    $image3 = $_FILES['image3']['tmp_name'];

    // Image names
    $image_name1 = $product_name . "1.jpeg";
    $image_name2 = $product_name . "2.jpeg";
    $image_name3 = $product_name . "3.jpeg";

    // Upload images
    move_uploaded_file($image1, "../assets/imgs/" . $image_name1);
    move_uploaded_file($image2, "../assets/imgs/" . $image_name2);
    move_uploaded_file($image3, "../assets/imgs/" . $image_name3);

    $stmt = $conn->prepare("INSERT INTO products (product_name, product_description, product_price, product_special_offer,
                            product_image, product_image2, product_image3, product_category, product_color)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param('sssssssss', $product_name, $product_description, $product_price, $product_special_offer,
        $image_name1, $image_name2, $image_name3, $product_category, $product_color);

    if ($stmt->execute()) {
        header('location: products.php?product_created=Product added Successfully');
    } else {
        header('location: products.php?product_failed=Error occurred, try again');
    }
}
