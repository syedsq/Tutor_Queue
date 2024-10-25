<?php
// end_session.php - Ends the session and redirects to the survey

// You can add any session clean-up logic here (logging session times, etc.)

// Redirect to the survey page (new tab logic is handled in the HTML of the next page)
header("Location: survey.php?student_id={$_POST['student_id']}");
exit();
