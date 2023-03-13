<!-- PHP script to create a row in address table -->
<?php
    ob_start();
    // Include config file
    require_once "../config.php";
    
    // Define variables and initialize with empty values
    $fullname = $atype = $alineone = $alinetwo = "";
    $fullname_err = $atype_err = $alineone_err = $alinetwo_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate Full Name
        $input_fullname = trim($_POST["fullname"]);
        if(empty($input_fullname)) {
            $fullname_err = "Please enter a full name.";
        } else {
            $fullname = $input_fullname;
        }
        
        // Validate Address Type
        $input_atype = trim($_POST["atype"]);
        if(empty($input_atype)) {
            $atype_err = "Please enter the country code.";     
        } else {
            $atype = $input_atype;
        }

        // Validate Address Line One
        $input_alineone = trim($_POST["alineone"]);
        if(empty($input_alineone)) {
            $alineone_err = "Please enter an address line one.";     
        } else {
            $alineone = $input_alineone;
        }
        
        // Validate Address Line Two
        $input_alinetwo = trim($_POST["alinetwo"]);
        if(empty($input_alinetwo)) {
            $alinetwo_err = "Please enter an address line two.";     
        } else {
            $alinetwo = $input_alinetwo;
        }
        
        // Check input errors before inserting in database
        if(empty($fullname_err) && empty($atype_err) && empty($alineone_err) && empty($alinetwo_err)) {
            // Prepare an insert statement
            $sql = "INSERT INTO address (name, atype, alineone, alinetwo) VALUES (?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_atype, $param_alineone, $param_alinetwo);
                
                // Set parameters
                $param_name = $fullname;
                $param_atype = $atype; 
                $param_alineone = $alineone;
                $param_alinetwo = $alinetwo;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records created successfully. Redirect to landing page
                    header("Location: ../interface.php?x=" . rand() . "#address");
                    exit;
                } else {
                    $error = mysqli_stmt_error($stmt);
                    echo "Error : ".$error;
                }
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add an address record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fullname; ?>">
                            <span class="invalid-feedback"><?php echo $fullname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address Type</label>
                            <input type="text" name="atype" class="form-control <?php echo (!empty($atype_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $atype; ?>">
                            <span class="invalid-feedback"><?php echo $atype_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address Line One</label>
                            <input type="text" name="alineone" class="form-control <?php echo (!empty($alineone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $alineone; ?>">
                            <span class="invalid-feedback"><?php echo $alineone_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address Line Two</label>
                            <input type="text" name="alinetwo" class="form-control <?php echo (!empty($alinetwo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $alinetwo; ?>">
                            <span class="invalid-feedback"><?php echo $alinetwo_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php
                            echo '<a href="../interface.php?x=' . rand() . '#address" class="btn btn-secondary ml-2">Cancel</a>';
                            mysqli_close($conn);
                        ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>