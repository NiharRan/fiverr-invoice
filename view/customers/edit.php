<?php

$customer = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/bootstrap.css">
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Add Customer</h2>
                    </div>
                    <p>Please fill this form and submit to add Customer record in the database.</p>
                    <form action="<?php echo BASE_URL; ?>customers/update/<?php echo $customer['id']; ?>" method="post">
                        <div class="form-group <?php echo has_error('name') ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $customer['name']; ?>">
                            <span class="help-block"><?php echo show_error('name'); ?></span>
                        </div>
                        <div class="form-group">
                            <label>Contact No.</label>
                            <input name="phone" class="form-control" value="<?php echo $customer['phone']; ?>">
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input name="email" type="email" class="form-control" value="<?php echo $customer['email']; ?>">
                            <span class="help-block"><?php echo show_error('email'); ?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input name="address" class="form-control" value="<?php echo $customer['address']; ?>">
                        </div>
                        <div class="form-group <?php echo has_error('city') ? 'has-error' : ''; ?>">
                            <label>City</label>
                            <input name="city" class="form-control" value="<?php echo $customer['city']; ?>">
                            <span class="help-block"><?php echo show_error('city'); ?></span>
                        </div>
                        <input type="submit" name="updatebtn" class="btn btn-primary" value="Submit">
                        <a href="<?php echo BASE_URL; ?>customers" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
unset($_SESSION['errors']);
?>