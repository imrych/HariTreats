 <!-- Masthead-->
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end mb-4 page-title">
                    	<h1 class="text-white">Welcome to HariTreats </h1>
                        <h2 class="text-white"><i>Treat Yourself Like a King</i></h2>
                        <hr class="divider my-4" />
                        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#menu">Order Now</a>

                    </div>
                    
                </div>
            </div>
        </header>
	<section class="page-section" id="menu">
    <h2 class="sticky-header">MENU</h2>

<div id="menu-field" class="card-deck scrollable-menu">
                <?php 
                    include'admin/db_connect.php';
                    $qry = $conn->query("SELECT * FROM  product_list order by rand() ");
                    while($row = $qry->fetch_assoc()):
                    ?>
                    <div class="col-lg-3">
                     <div class="card menu-item ">
                        <img src="assets/img/<?php echo $row['img_path'] ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                          <h5 class="card-title"><?php echo $row['name'] ?></h5>
                          <p class="card-text truncate"><?php echo $row['description'] ?></p>
                          <div class="text-center">
                              <button class="btn btn-sm btn-outline-primary view_prod btn-block" data-id=<?php echo $row['id'] ?>><i class="fa fa-eye"></i> View</button>
                              
                          </div>
                        </div>
                        
                      </div>
                      </div>
                    <?php endwhile; ?>
        </div>
    </section>
    <script>
        
        $('.view_prod').click(function(){
            uni_modal_right('Product','view_prod.php?id='+$(this).attr('data-id'))
        })
        
    </script>
	
    <style>
                .sticky-header {
                    background: #51050F;
                    position: sticky;
                    top: 0;
                    z-index: 999;
                    padding: 20px;
                    margin: 0;
                }
    
            .scrollable-menu {
                max-height: 400px; /* Adjust the maximum height as needed */
                overflow-y: auto;
            }

            </style>