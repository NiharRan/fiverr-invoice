<?php session_unset(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="<?php echo BASE_URL; ?>libs/fontawesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/bootstrap.css">
    <script src="<?php echo BASE_URL; ?>libs/jquery.min.js"></script>
    <script src="<?php echo BASE_URL; ?>libs/bootstrap.js"></script>
    <style type="text/css">
        .wrapper {
            width: 650px;
            margin: 0 auto;
        }

        .page-header h2 {
            margin-top: 0;
        }

        table tr td:last-child a {
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header clearfix">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-success pull-left">Home</a>
                    <h2 class="pull-left">Customers</h2>
                    <a href="<?php echo BASE_URL; ?>customers/create" class="btn btn-success pull-right">Add New Customer</a>
                </div>
                <?php
                if ($result->num_rows > 0) {
                    echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>#</th>";
                    echo "<th>Name</th>";
                    echo "<th>Phone</th>";
                    echo "<th>Email</th>";
                    echo "<th>Address</th>";
                    echo "<th>City</th>";
                    echo "<th>Date</th>";
                    echo "<th>Action</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>" . $row['city'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>";
                        echo "<a href='" . BASE_URL . 'customers/edit/' . $row['id'] . "' title='Update Customer' data-toggle='tooltip'><i class='fa fa-edit'></i></a>";
                        echo "<a href='" . BASE_URL . 'customers/delete/' . $row['id'] . "' title='Delete Customer' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    // Free result set
                    mysqli_free_result($result);
                } else {
                    echo "<p class='lead'><em>No records were found.</em></p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>