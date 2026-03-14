<?php include('layouts/header.php');?>
   <!-- Home -->
 
    <section id="hero">
        <div class="hero-flex ">
            <div>
                <h4>Trade-in-offer</h4>
            <h2>Super Value deals</h2>
            <h1>On all products</h1>
            <button>Shop Now</button>
            </div>
            <div>
                <img src="assets/images/heroine.png "  width="800px" height="900px">
            </div>
        </div>
    </section>
    <!-- featured -->
     <section id="featured" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h2>Featured Products</h2>
    <p>Summer Collection With New Modern Design</p>
        </div>
        <div class="row mx-auto container-fluid">
            <?php include('server/get_featured_products.php');?>
            <?php while($row=$featured_products->fetch_assoc()){?>
            <div class="product  col-lg-3 col-md-4 col-sm-12">
                <img src="assets/images/<?php echo $row['product_image'];?>" alt="" class="img-fluid mb-3">
                <span>Ms Luxe</span>
                    <h5 class="p-name"><?php echo $row['product_name'];?> </h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="p-price"><i class="fa fa-rupee" style="color: #d3acb3;"></i> <?php echo $row['product_price'];?></h4>
                    <a href="<?php echo"single_product.php?product_id=" .$row['product_id'];?>"><button class="buy-btn"><i class="fa-solid fa-cart-shopping"></i></button></a>
            </div>
            <?php }?>
           
        </div>
     </section>
<!-- New Arrivals -->
<section id="featured" class="my-5 ">
    <div class="container text-center mt-5 py-5">
        <h2>New Arrivals Products</h2>
    <p>Summer Collection With New Modern Design</p>
    </div>
    <div class="row mx-auto container-fluid">
    <?php include('server/get_new_arrivals.php');?>
    <?php while($row=$new_arrivals->fetch_assoc()){?>
        <div class="product  col-lg-3 col-md-4 col-sm-12">
            <img src="assets/images/<?php echo $row['product_image'];?>" alt="" class="img-fluid mb-3">
            <span>Ms Luxe</span>
                <h5 class="p-name"><?php echo $row['product_name'];?></h5>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h4 class="p-price"><i class="fa fa-rupee" style="color: #d3acb3;"></i> <?php echo $row['product_price'];?></h4>
                <a href="<?php echo"single_product.php?product_id=" .$row['product_id'];?>"><button class="buy-btn"><i class="fa-solid fa-cart-shopping"></i></button></a>
        </div>
        <?php }?>
    </div>
 </section>
   <!-- footer -->
   <?php include('layouts/footer.php');?>