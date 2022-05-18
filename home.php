<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <title>Super Market</title> 
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
        <style>
            body,html {
            height: 100%;
            overflow: hidden; 
            }
        </style>
    </head> 
    <body> 
        <div class="col-md-12 d-flex bg-dark ">
            <div class="col-md-9">
                <nav class="navbar navbar-expand-sm bg-dark navbar-dark pl-0">
                    <a class="navbar-brand" href="index.php">
                    Super Market
                    </a>
                </nav>
            </div>
        </div>
        <div class="container-fluid h-100 text-dark bg-light"> 
            <div class="row justify-content-center ">
                <div class="col-3 align-center pt-3 offset-md-1">
                    <h3>Checkout Here</h3>
                </div>
                <div class="col-12">
                    <form action="" id="myForm" name="myForm" method="post" >
                        <div class=" col-9 p-3 offset-md-4">
                            <table class="table table-striped w-50 "> 
                                <thead> 
                                    <tr> 
                                        <th>Item</th> 
                                        <th>Quntity</th> 
                                        <th>Action</th>
                                    </tr> 
                                </thead> 
                                <tbody id="item">
                                    <tr>
                                        <td>
                                            <select name="item[]" class="form-control">
                                                <?php
                                                    $all_items = $item->getItems();
                                                    while($data = mysqli_fetch_array($all_items)) 
                                                    { 
                                                        ?>
                                                        <option value="<?php echo $data['name']; ?>"> <?php echo $data['name']; ?> </option>
                                                        <?php
                                                    }
                                                        ?> 
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="qty[]" value="1" class="form-control col-6" min="1">
                                        </td>
                                        <td>
                                            <button type="button" class="addItem btn btn-sm btn-primary" value="+ Add Item">+ Add Item</button>
                                        </td>
                                    </tr>
                                </tbody>  
                            </table>
                        </div>
                        <div class="col-3 align-center offset-md-5 pl-0">
                            <input type="submit" name="CheckoutButton" class="btn btn-success btn-lg btn-block" value="Checkout">
                        </div>
                    </form>
                </div>
                <?php
                if(isset($checkoutTotal) && $checkoutTotal > 0)
                {
                    ?>
                    <div class="col-12 checkout-tbl">
                        <div class=" col-9 p-3 offset-md-4">
                            <table class="table table-bordered w-50 "> 
                                <thead> 
                                    <tr> 
                                        <th>Item</th> 
                                        <th>Quntity</th>  
                                    </tr> 
                                </thead> 
                                <tbody>
                                    <?php
                                        for($i=0;$i<$numOfItem;$i++)
                                        {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $_POST['item'][$i]; ?>
                                                </td>
                                                <td>
                                                <?php echo $_POST['qty'][$i]; ?>
                                                </td>
                                            </tr>
                                            <?php

                                        }
                                    ?>
                                    <tr><td colspan="2" align="center"><h3>Total : <?php echo @$checkoutTotal; ?></h3></span></tr>
                                </tbody>  
                            </table>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div> 
    </body> 
</html> 
<script type="text/javascript">
    // add item selection dynamically on click of "Add Item"
    $(document).on('click','.addItem',function(){
        $('.checkout-tbl').hide();
        var html = '';
        html += '<tr><td><select name="item[]" class="form-control">';
        html += ' <?php
            $all_items = $item->getItems();
            while($data = mysqli_fetch_array($all_items)) 
            { 
                ?>';
        html += '<option value="<?php echo $data['name']; ?>">';
        html += '<?php echo $data['name']; ?>'; 
        html += '</option>';
        html += '<?php
            }
                ?>';
        html += '</select></td><td><input type="number" value="1" min="1" name="qty[]" class="form-control col-6"></td><td><button type="button"  class="removeRow btn btn-sm btn-danger" value="- Remove Item">- Remove Item</button></td> </tr>';
        $('#item').append(html);
    });
    // remove item row when clicked remove button
    $(document).on('click', '.removeRow', function() {
          $(this).parent().parent().remove();
    })
</script>