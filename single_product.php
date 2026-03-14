<?php
include('server/connection.php');
if(isset($_GET['product_id'])){
    $product_id=$_GET['product_id'];
    $stmt=$conn->prepare("SELECT * FROM  products WHERE product_id=?");
    $stmt->bind_param("i",$product_id);
    $stmt->execute();
    $product=$stmt->get_result();
// no product id was given
}else{
    header('location:index.php');
}
?>
<?php include('layouts/header.php');?>

      <section class=" container single-product my-5 pt-5">
        <div class="row mt-5 ">
            <?php while($row=$product->fetch_assoc()) {?>



                



            <div class="col-lg-5 col-md-6 col-sm-12">
                <img class="img-fluid w-100 pb-1" src="assets/images/<?php echo $row['product_image'];?>" alt="" id="mainImg">
                <div class="small-img-group">
                    <div class="small-img-col">
                        <img src="assets/images/<?php echo $row['product_image'];?>" alt="" width="100%" class="small-img">
                    </div>
                    <div class="small-img-col">
                        <img src="assets/images/<?php echo $row['product_image2'];?>" alt="" width="100%" class="small-img">
                    </div>
                    <div class="small-img-col">
                        <img src="assets/images/<?php echo $row['product_image3'];?>" alt="" width="100%" class="small-img">
                    </div>
                </div>
            </div>
          

            <div class="col-lg-6 col-md-12 col-12">
                <h6>Home / T-shirt </h6>
            <h4 class="py-4"><?php echo $row['product_name'];?></h4>
            <h2><i class="fa fa-rupee"></i> <?php echo $row['product_price'];?></h2>
            <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id'];?>">
                        <input type="hidden" name="product_image" value="<?php echo $row['product_image'];?>">
                        <input type="hidden" name="product_name" value="<?php echo $row['product_name'];?>">
                        <input type="hidden" name="product_price" value="<?php echo $row['product_price'];?>">
                <input type="number" name="product_quantity" id="" value="1">
                <select name="product_size" id="" >
                    <option value="">Select Size</option>
                    <option value="">Medium</option>
                    <option value="">Large</option>
                    <option value="">XL</option>
                </select>
                <button class="buy-btn" type="submit" name="add_to_cart">Add To Cart</button>
            </form>
            <h4 class="mt-5 mb-5">Product Details </h4>
            <span><?php echo $row['product_description'];?>
            </span>
            </div>
           
            <?php }?>
        </div>
      </section>
      <?php include('layouts/footer.php');?>
        <script>
           var mainImg= document.getElementById("mainImg");
            var smallImg=document.getElementsByClassName("small-img");
            
            smallImg[0].onclick=function(){
                mainImg.src=smallImg[0].src
            }
            smallImg[1].onclick=function(){
                mainImg.src=smallImg[1].src
            }
            smallImg[2].onclick=function(){
                mainImg.src=smallImg[2].src
            }
        </script>
    