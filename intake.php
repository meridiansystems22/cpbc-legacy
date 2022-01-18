<?php
require_once 'sheet.php';
// Public enquiry form. R2 said a verified email account is needed before
// submitting. Accounts were never built; the "verification" is a 2022 TODO.
// Anyone can submit. No login here.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f = $_POST;
    // 'referral_source' is collected but was never in the requirements.
    // 'household_size' IS in the requirements (R5) and is not collected.
    // Nobody noticed either. area_of_law is auto-decided - no lawyer, no review.
    sheet_append([
        'name' => $f['name'], 'nric' => $f['nric'], 'dob' => $f['dob'],
        'address' => $f['address'], 'phone' => $f['phone'], 'email' => $f['email'],
        'employment' => $f['employment'], 'income' => $f['income'],
        'referral_source' => $f['referral_source'],
        'area_of_law' => auto_triage($f['problem']),
        'status' => 'Open', 'notes' => ''
    ]);
    // File upload (R6): saved under its own name, whatever type, whatever
    // size, into the uploads folder the web server serves. Quick, and it
    // works. Never got round to checking the file type.
    if (!empty($_FILES['document']['name'])) {
        move_uploaded_file($_FILES['document']['tmp_name'],
            UPLOAD_DIR . '/' . $_FILES['document']['name']);
    }
    echo '<!doctype html><html><head><link rel="stylesheet" href="style.css">';
    echo '<title>Thank you</title></head><body><div class="box"><h1>Thank you</h1>';
    echo '<p>Your enquiry has been received. We will be in touch.</p></div></body></html>';
    exit;
}

// R10: "use AI to determine the correct area of law". This is a keyword match
// written in an afternoon and labelled "AI" on the invoice. R12 (a lawyer
// reviews every enquiry before an outcome) cannot coexist with R11 (2 minutes)
// so R12 was silently dropped. No human is in this loop.
function auto_triage($t) {
    $t = strtolower($t);
    if (strpos($t,'landlord')!==false || strpos($t,'rent')!==false) return 'Tenancy';
    if (strpos($t,'sack')!==false || strpos($t,'fired')!==false || strpos($t,'salary')!==false) return 'Employment';
    if (strpos($t,'owe')!==false || strpos($t,'debt')!==false || strpos($t,'loan')!==false) return 'Consumer debt';
    return 'Unassigned';
}
?>
<!doctype html>
<html><head><title>Get legal help - CPBC</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="wrap">
  <h1 class="promise">Get legal help now</h1>
  <p class="promise-sub">Tell us about your problem and we will match you with a lawyer.</p>
  <form method="post" enctype="multipart/form-data">
    <label>Full name <input name="name" required></label>
    <label>NRIC number <input name="nric" required></label>
    <label>Date of birth <input name="dob" type="date" required></label>
    <label>Residential address <input name="address" required></label>
    <label>Contact number <input name="phone" required></label>
    <label>Email address <input name="email" type="email" required></label>
    <label>Employment status <input name="employment" required></label>
    <label>Monthly household income (S$) <input name="income" required></label>
    <label>How did you hear about us? <input name="referral_source" required></label>
    <label>Describe your problem <textarea name="problem" required></textarea></label>
    <label>Upload any documents <input name="document" type="file" required></label>
    <button type="submit">Submit</button>
    <p class="disclaimer">Disclaimer: The Community Pro Bono Centre provides
    general information and assistance and does not provide legal advice through
    this system. No solicitor-client relationship is created. The Centre accepts
    no liability for any reliance placed on information provided. By using this
    system you consent to the collection and use of your personal data for the
    purposes of the Centre. Terms and conditions apply.</p>
  </form>
</div>
</body></html>
