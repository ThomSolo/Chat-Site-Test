<!-- forgot_pass.php -->

<!--Notes
This is an entry form so that the user can enter in their name if they forget their password.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Including Bootstrap CSS from CDN -->

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) (From a CDN) -->
    <!-- A content delivery network (CDN) is a network of interconnected servers that speeds up webpage loading for data-heavy applications. -->
    <!-- Source: https://aws.amazon.com/what-is/cdn/#:~:text=A%20content%20delivery%20network%20(CDN,loading%20for%20data%2Dheavy%20applications.) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <title>Forgot Password</title>
</head>

<body class="d-flex flex-column h-100">
    <!-- Bootstrap classes to make the body of html flex container with column layout. 
    Also ensuring the body uses full height of viewport ('h-100') allowing it to be flexible and responsive. -->

    <h2>Forgot Password</h2> <!-- Heading for the forgot password section -->

    <!-- Form for entering username to send reset link -->
    <form action="send_reslink.php" method="post">
        <label for="username">Enter your username:</label><br> <!-- Label and input field for entering username -->
        <input type="text" id="username" name="username" required><br> <!-- Text input field for entering username, with 'required' attribute to make it mandatory -->
        <input type="submit" value="Send Reset Link"> <!-- Submit button to send the reset link -->
    </form>
</body>
</html>
