<?php

include('server/connection.php');
if(isset($_GET['page_no']) && $_GET['page_no']!=""){
    //if user has already entered page then page number is the one that they selected
    $page_no=$_GET['page_no'];

}else{
    //if user just entered the page then default page
    $page_no=1;
}
//return number of products
$stmt1=$conn->prepare("SELECT COUNT(*)As total_records FROM products");
$stmt1->execute();
$stmt1->bind_result($total_records);
$stmt1->store_result();
$stmt1->fetch();
//3.products per page
$total_records_per_page=6;
$offset=($page_no-1)*$total_records_per_page;
$previous_page=$page_no-1;
$next_page=$page_no=1;
$adjuacents="2";
$total_no_of_pages=ceil($total_records/$total_records_per_page);
//4.get all products
$stmt2=$conn->prepare("SELECT*FROM products LIMIT $offset,$total_records_per_page");
$stmt2->execute();
$products=$stmt2->get_result();

?>








<?php include('layouts/header.php');?>
<style>
    .pagination {
        text-align: center;
        display: flex;
        justify-content: center;
    }
    .pagination a {
        background-color: #d3acb3;
        color: #fff;
        border: none;
        padding: 10px 15px;
        margin: 0 5px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }
    .pagination a:hover {
        background-color: #d3acb3;
    }
    .page-item.disabled .page-link {
        background-color: #d3acb3;
        color: #fff;
        pointer-events: none;
        cursor: default;
    }
</style>

          <!-- featured -->
          <section id="featured" class="my-5 py-5">
            <div class="container text-center mt-5 py-5">
                <h2 style="color: #d3acb3;">Get Quality Products with New Modern Design</h2>
            </div>
            <div class="row mx-auto container-fluid">


            <?php while($row=$products->fetch_assoc()){?>
                <div onclick="window.location.href='single_product.php'"  class="product col-lg-3 col-md-4 col-sm-12">
                    <img src="assets/images/<?php echo $row['product_image'];?>" alt="" class="img-fluid mb-3">
                    <span>MsLuxe</span>
                    <h5 class="p-name"><?php echo $row['product_name'];?></h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i> 
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="p-price"><i class="fa fa-rupee" style="color: #d3acb3;"></i><?php echo $row['product_price'];?></h4>
                    <button class="buy-btn"><a href="single_product.php?product_id=<?php echo $row['product_id'];?>"><i class="fa-solid fa-cart-shopping"style="color: #d3acb3;"></i></a></button>
                </div>
                <?php }?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination mt-5 center">
                        <li class="page-item <?php if($page_no<=1){echo'disabled';}?>">
                            <a href="<?php if($page_no<=1){echo'#';}else{echo"?pgae_no".$page_no-1;}?>" class="page-link">Previous</a>
                        </li>
                       
                       
                       
                        <li class="page-item">
                            <a href="?page_no=1" class="page-link">1</a>
                        </li>
                        <li class="page-item">
                            <a href="?page_no=2" class="page-link">2</a>
                        </li>
                        <?php if($page_no>=3){?>
                        <li class="page-item">
                            <a href="#" class="page-link">...</a>
                        </li>
                        <li class="page-item">
                            <a href="<?php echo "?page_no=".$page_no;?>" class="page-link"><?php echo $page_no;?></a>
                        </li>
                        <?php } ?>
                        <li class="page-item <?php if($page_no>=$total_no_of_pages){echo'disabled';}?>">
                            <a href="<?php if($page_no>=$total_no_of_pages){echo'#';}else{echo"?page_no".$page_no+1;}?>" class="page-link">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
        


<?php include('layouts/footer.php');?>