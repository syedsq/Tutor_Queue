<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Landing Page</title>
    <style>
        /* Basic page setup with UTSA colors */
        body {
            font-family: Arial, sans-serif;
            background-color: #00274d; /* UTSA Navy Blue */
            color: white;
            margin: 0;
            padding: 0;
        }

        /* Center the form, add a nice shadow and border-radius */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: black;
        }

        /* Orange heading to match UTSA theme */
        h2 {
            text-align: center;
            color: #ff8200; /* UTSA Orange */
        }

        /* Label and input styling to keep things clean */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* Set inputs and dropdowns to the same width as the button */
        input, select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Style for the submit button */
        input[type="submit"] {
            background-color: #ff8200; /* UTSA Orange */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            padding: 10px;
        }

        /* Hover effect for the submit button */
        input[type="submit"]:hover {
            background-color: #e67300;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>CS LAB Tutoring</h2>
        
        <!-- Form to capture student info -->
        <form action="submit_form.php" method="POST">

            <!-- Full name -->
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" required>

            <!-- Student ID -->
            <label for="studentID">Student ID</label>
            <input type="text" id="studentID" name="studentID" required>

            <!-- Email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <!-- Class dropdown -->
            <label for="class">Select Class</label>
            <select id="class" name="class">
                <option value="">-- Select Class --</option>
                <!-- Populate class options dynamically later -->
            </select>

            <!-- Online or In-Person dropdown -->
            <label for="sessionType">Session Type</label>
            <select id="sessionType" name="sessionType">
                <option value="">-- Select Session Type --</option>
                <option value="online">Online</option>
                <option value="inperson">In-Person</option>
            </select>

            <!-- Subject -->
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required>

            <!-- Submit button -->
            <input type="submit" value="Submit">
        </form>
    </div>

</body>
</html>
