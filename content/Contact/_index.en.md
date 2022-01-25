---
title: "Questions?"
lastmod: 2022-1-25T11:02:05+06:00

# search related keywords
keywords: ["Message", "Contact"]

id: "contact"
---
If you have any questions, or would like to reach out to us about something, please consult the form below.


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}
input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}
input[type=submit] {
  background-color: #00008B;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
input[type=submit]:hover {
  background-color: #45a049;
}
.stuff {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
</head>
<body>

<h3>Contact Form</h3>

<div class="stuff">
  <form id="fs-frm" name="simple-contact-form" accept-charset="utf-8" action="https://formspree.io/f/xnqlyokv" method="post">
    <fieldset id="fs-frm-inputs">
      <label for="full-name">Full Name</label>
      <input type="text" name="name" id="full-name" placeholder="First and Last" required="">
      <label for="email-address">Email Address</label>
      <input type="text" name="_replyto" id="email-address" placeholder="email@gmail.com" required="">
      <label for="message">Message</label>
      <textarea rows="5" name="message" id="message" placeholder="Please enter your message here." required=""></textarea>
      <input type="hidden" name="_subject" id="email-subject" value="Contact Form Submission">
    </fieldset>
    <input type="submit" value="Submit">
  </form>
</div>

</body>
</html>

#### Having issues?

Please use our [Github issue tracker](https://github.com/OpenACCUserGroup/OpenACCV-V/issues) to report any issues that you are having with out test suite

#### Found a bug or have a comment?

Please use the issue tracker above or any bugs or comments. **Please make sure that you are logged into github with your username**. We encourage people's participation. The success of this test suite comes from the effort of the OpenACC community.

#### Note

Thanks for contributing to our project. By contributing to our project you agree to our [license agreement](/license) and allow us to use and distribute your code.
