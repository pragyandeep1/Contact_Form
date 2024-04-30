<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luxury Car Showroom</title>
</head>
<body>
	<div class="container">
		<form method="post" action="submit.php">
			<label for="fullname">Full Name</label>
			<input type="text" name="fullname" id="fullname" required>
			<br>
			<label for="phone">Phone Number</label>
			<input type="tel" name="phone" id="phone" required>
			<br>
			<label for="email">Email</label>
			<input type="text" name="email" id="email" required>
			<br>
			<label for="subject">Subject</label>
			<input type="text" name="subject" id="subject" required>
			<br>
			<label for="message">Message</label>
			<input type="text" name="message" id="message" required>
			<button type="submit">Submit</button>
		</form>
	</div>
</body>
</html>