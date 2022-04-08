	<?php
		if (!isset($_SESSION)){
			session_start();
		}

	$nameErr = $emailErr = $contBackErr = "";
	$name = $email = $contBack = $comment = "";
	$formErr = false;

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (empty($_POST["name"])) {
		$nameErr = "Your name is required.";
		$formErr = true;
	} else {
		$name = cleanInput($_POST["name"]);
		if(preg_match("/^[a-zA-Z ] *$/", $name)) {
			$nameErr = "Sorry, only letters and standard Spaces are allowed here.";
			$formErr = true;
		}
	}
	
	if (empty($_POST["email"])) {
		$emailErr = "Email is required.";
		$formErr = true;
	} else {
		$email = cleanInput($_POST["email"]);
		if (!filter_var($email, FILTER-VALIDATE-EMAIL)) {
			$emailErr = "Please enter a valid email.";
			$formErr= true;
		}
	}
	
	if (empty($_POST["contact-back"])) {
		$contBackErr = "Please let us know if we can contact you back.";
		$formErr = true;
	} else {
		$contBack = cleanInput($_POST["contact-back"]);
	}
	
	$comment = cleanInput($_POST["comments"]);
	}

	function cleanInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
	}

	if (($_SERVER["REQUEST_METHOD"] == "POST") && (!($formErr))) {
		$hostname = "php-mysql-exercisedb.slccwebdev.com";
		 $username = "phpmysqlexercise";
    	$password = "mysqlexercise";
    	$databasename = "php_mysql_exercisedb";

    
    	try {
        //Create new PDO Object with connection parameters
        	$conn = new PDO("mysql:host=$hostname;dbname=$databasename",$username, $password);
        
        //Set PDO error mode to exception
        	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
        
        //Variable containing SQL command
        	$sql = "INSERT INTO sb_winter21_Contacts (name, email, contactBack, comments)
            	VALUES (:name, :email, :contactBack, :comments);";

        	$stmt = $conn->prepare($sql);

        	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
        	$stmt->bindParam(':email', $email, PDO::PARAM_STR);
        	$stmt->bindParam(':contactBack', $contBack, PDO::PARAM_STR);
        	$stmt->bindParam(':comments', $comments, PDO::PARAM_STR);

        	$stmt->execute();

			$_SESSION['message']= '<p class="font-weight-bold>Thank you for pressing submit!</p><p class ="font-weight-light">Your information has been sent</p>';
			$_SESSION['complete']= true;

			header('Location: ' . $_SERVER['REQUEST_URI']);
			exit;


    } catch (PDOException $error) {

        //Return error code if one is created
        $_SESSION['message']='<p>Sorry, the form was not submitted successfully. Please try again later</p>';

		$_SESSION['complete']= true;

		header('Location: ' . $_SERVER['REQUEST_URI']);
		exit;
    }	

	$conn = null;
	}
	?>
<!DOCTYPE html>
    <html>
        <div class="header">
            <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-5"> 
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, intial-scale=1">
              <title>Contact Me</title>
              <link rel="stylesheet" type="text/css" href="contactme.css">
            </div>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a href="contactme.html" class="navbar-brand">Contact Me</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="home.php">Home</a</li>
                        <li class="nav-item"><a class="nav-link" href="portfolio.php">Portfolio</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    </ul>
                    </div>
                  </nav>
                <br>
        <body class="bg-light">   

	<!-- Contact Form Section -->
	<section id="contact">
		<div class="container py-5">
			<!-- Section Title -->
			<div class="row justify-content-center text-center">
				<div class="col-md-6">
					<h2 class="display-4 font-weight-bold">Contact Me</h2>
					<hr />
				</div>
			</div>
			<!-- Contact Form Row -->
			<div class="row justify-content-center">
				<div class="col-6">
				
					<!-- Contact Form Start -->
					<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="POST" novalidate>
						
						<!-- Name Field -->
						<div class="form-group">
							<label for="name">Name:</label>
							<span class="text-danger">*<?php echo $nameErr; ?></span>
							<input type="text" class="form-control" id="name" placeholder="Name" name="name" value= "<?php if (isset($name)) {echo $name;} ?>" />
							
						</div>
						
						<!-- Email Field -->
						<div class="form-group">
							<label for="email">Email:</label>
							<span class="text-danger">*<?php echo $emailErr; ?></span>
							<input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" value= "<?php if (isset($email)) {echo $email;} ?>" />
						</div>
						
						<!-- Radio Button Field -->
						<div class="form-group">
							<label class="control-label">Do you want me to get back to you?</label>
							<span class="text-danger">*<?php echo $contBackErr; ?></span>
							<div class="form-check">
								<input type="radio" class="form-check-input" name="contact-back" id="yes" value="Yes" <?php if ((isset($contBack)) && ($contBack == "Yes")) {echo "checked";} ?> />
								<label class="form-check-label" for="yes">Yes</label>
							</div>
							<div class="form-check">
								<input type="radio" class="form-check-input" name="contact-back" id="no" value="No" <?php if ((isset($contBack)) && ($contBack == "No")) {echo "checked";} ?> />
								<label class="form-check-label" for="no">No</label>
							</div>
						</div>
						
						<!-- Comments Field -->
						<div class="form-group">
							<label for="comments">Comments:</label>
							<textarea id="comments" class="form-control" rows="3" name="comments"><?php if (isset($comment)) {echo $comment;} ?></textarea>
						</div>

						<!-- Required Fields Note-->
						<div class="text-danger text-right">* Indicates required fields</div>
						
						<!-- Submit Button -->
						<button class="btn btn-primary mb-2" type="submit" role="button" name="submit">Submit</button>
					</form>
					
				</div>
			</div>
		</div>

		<!-- Modal -->
	<div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ThankYouModalLabel">Thank You</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo $_SESSION['message']; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  	</div>
	</div>
		</section>
	
		<?php
			if(isset($_SESSION['complete']) && $_SESSION['complete']) {
				echo "<script>$('#thankYouModal').modal('show');</script>";
				session_unset();
			}
		?>
	
        </body>

        <div class="footer">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
              <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
              <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
              <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        </div>
    </html>