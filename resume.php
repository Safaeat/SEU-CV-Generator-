<?php
session_start(); // Start the session

// Redirect to login page if username is not set in session
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    $conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SEU CV Generator</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/images/seu_logo.png">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
      .navbar {
        background-color: #042749;
        padding: 10px 20px;
        height: 55px;
      }
      .fixed-nav {
        position: fixed;
        top: 0;
        width: 100%;
        color: var(--clr-blue-mid);
        padding: 10px 0;
        z-index: 1000; /* Ensures the nav stays above other content */
      }
      .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
      }
      .navbar-brand {
        display: flex;
        align-items: center;
        color: white;
        text-decoration: none;
      }
      .navbar-brand-icon {
        width: 35px;
        height: 35px;
        margin-right: 10px;
      }
      .navbar-brand-text {
        font-size: 1.2em;
        font-weight: bold;
      }
      .navbar-brand-text span {
        color: #3498db;
      }
      .navbar-links {
        display: flex;
        gap: 20px;
      }
      .navbar-links a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s;
      }
      .navbar-links a:hover {
        color: #3498db;
      }
      @media (max-width: 768px) {
        .navbar-links {
          flex-direction: column;
          align-items: center;
          background-color: #042749;
          position: absolute;
          top: 55px;
          left: 0;
          width: 100%;
          display: none;
        }
        .navbar-links.open {
          display: flex;
        }
        .navbar-links a {
          padding: 10px;
          width: 100%;
          text-align: center;
        }
        .navbar-toggler {
          display: block;
          background-color: white;
          padding: 5px 10px;
          cursor: pointer;
        }
      }
      @media (min-width: 769px) {
        .navbar-toggler {
          display: none;
        }
      }
    </style>
</head>
<body>
<nav class="navbar">
      <div class="container">
        <a href="index.html" class="navbar-brand">
          <img src="assets/images/seu_logo.png" alt="SEU Logo" class="navbar-brand-icon" />
          <span class="navbar-brand-text">SEU <span>CV Generator</span></span>
        </a>
        <div class="navbar-toggler">Menu</div>
        <div class="navbar-links">
          <a href="welcome.php">Back</a>
          <a href="logout.php">Log Out</a>
          
        </div>
      </div>
    </nav>

    <div class="wrapper">
        <section class="left-section">
            <section id="text-white about-sc left-side" class="">
                <div class="container">
                    <div class="text-white about-cnt">
                        <form action="save_cv.php" method="POST" enctype="multipart/form-data" class="cv-form" id="cv-form">
                            <div class="cv-form-blk">
                                <div class="cv-form-row-title">
                                    <h3>about section</h3>
                                </div>
                                <div class="text cv-form-row cv-form-row-about">
                                    <div class="cols-3">
                                        <div class="form-elem">
                                            <label for="" class="form-label">First Name</label>
                                            <input name="firstname" type="text" class="form-control firstname" id="" onkeyup="generateCV()" placeholder="e.g. Safaeat">
                                            <span class="form-text"></span>
                                        </div>
                                        <div class="form-elem">
                                            <label for="" class="form-label">Middle Name <span class="opt-text">(optional)</span></label>
                                            <input name="middlename" type="text" class="form-control middlename" id="" onkeyup="generateCV()" placeholder="e.g. Ahmed">
                                            <span class="form-text"></span>
                                        </div>
                                        <div class="form-elem">
                                            <label for="" class="form-label">Last Name</label>
                                            <input name="lastname" type="text" class="form-control lastname" id="" onkeyup="generateCV()" placeholder="e.g. Rafi">
                                            <span class="form-text"></span>
                                        </div>
                                    </div>

                                    <div class="cols-3">
                                        <div class="form-elem">
                                            <label for="" class="form-label">Your Image</label>
                                            <input name="image" type="file" class="form-control image" id="" accept="image/*" onchange="previewImage()">
                                        </div>
                                        <div class="form-elem">
                                            <label for="" class="form-label">Designation</label>
                                            <input name="designation" type="text" class="form-control designation" id="" onkeyup="generateCV()" placeholder="e.g. Software Developer">
                                            <span class="form-text"></span>
                                        </div>
                                        <div class="form-elem">
                                            <label for="" class="form-label">Address</label>
                                            <input name="address" type="text" class="form-control address" id="" onkeyup="generateCV()" placeholder="e.g. Niketon Bazar">
                                            <span class="form-text"></span>
                                        </div>
                                    </div>

                                    <div class="cols-3">
                                        <div class="form-elem">
                                            <label for="" class="form-label">Email</label>
                                            <input name="email" type="text" class="form-control email" id="" onkeyup="generateCV()" placeholder="e.g. safaeat@gmail.com">
                                            <span class="form-text"></span>
                                        </div>
                                        <div class="form-elem">
                                            <label for="" class="form-label">Phone No:</label>
                                            <input name="phoneno" type="text" class="form-control phoneno" id="" onkeyup="generateCV()" placeholder="e.g. 01865216805">
                                            <span class="form-text"></span>
                                        </div>
                                        <div class="form-elem">
                                            <label for="" class="form-label">Summary</label>
                                            <input name="summary" type="text" class="form-control summary" id="" onkeyup="generateCV()" placeholder="e.g. Write about yourself in 50 words.">
                                            <span class="form-text"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cv-form-blk">
                                <div class="cv-form-row-title">
                                    <h3>achievements</h3>
                                </div>

                                <div class="row-separator repeater">
                                    <div class="repeater" data-repeater-list="group-a">
                                        <div data-repeater-item>
                                            <div class="cv-form-row cv-form-row-achievement">
                                                <div class="cols-2">
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Title</label>
                                                        <input name="achieve_title" type="text" class="form-control achieve_title" id="" onkeyup="generateCV()" placeholder="e.g. SEU Programming Contest 2024">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Description</label>
                                                        <input name="achieve_description" type="text" class="form-control achieve_description" id="" onkeyup="generateCV()" placeholder="e.g. Write about your achivement on that ocation.">
                                                        <span class="form-text"></span>
                                                    </div>
                                                </div>
                                                <button data-repeater-delete type="button" class="repeater-remove-btn">-</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create value="Add" class="repeater-add-btn">+</button>
                                </div>
                            </div>

                            <div class="cv-form-blk">
                                <div class="cv-form-row-title">
                                    <h3>experience</h3>
                                </div>

                                <div class="row-separator repeater">
                                    <div class="repeater" data-repeater-list="group-b">
                                        <div data-repeater-item>
                                            <div class="cv-form-row cv-form-row-experience">
                                                <div class="cols-3">
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Title</label>
                                                        <input name="exp_title" type="text" class="form-control exp_title" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Company / Organization</label>
                                                        <input name="exp_organization" type="text" class="form-control exp_organization" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Location</label>
                                                        <input name="exp_location" type="text" class="form-control exp_location" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                </div>

                                                <div class="cols-3">
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Start Date</label>
                                                        <input name="exp_start_date" type="date" class="form-control exp_start_date" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">End Date</label>
                                                        <input name="exp_end_date" type="date" class="form-control exp_end_date" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Description</label>
                                                        <input name="exp_description" type="text" class="form-control exp_description" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                </div>

                                                <button data-repeater-delete type="button" class="repeater-remove-btn">-</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create value="Add" class="repeater-add-btn">+</button>
                                </div>
                            </div>

                            <div class="cv-form-blk">
                                <div class="cv-form-row-title">
                                    <h3>education</h3>
                                </div>

                                <div class="row-separator repeater">
                                    <div class="repeater" data-repeater-list="group-c">
                                        <div data-repeater-item>
                                            <div class="cv-form-row cv-form-row-education">
                                                <div class="cols-3">
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Degree Title</label>
                                                        <input name="edu_title" type="text" class="form-control edu_title" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Institution</label>
                                                        <input name="edu_institution" type="text" class="form-control edu_institution" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Location</label>
                                                        <input name="edu_location" type="text" class="form-control edu_location" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                </div>

                                                <div class="cols-3">
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Start Date</label>
                                                        <input name="edu_start_date" type="date" class="form-control edu_start_date" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">End Date</label>
                                                        <input name="edu_end_date" type="date" class="form-control edu_end_date" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Description</label>
                                                        <input name="edu_description" type="text" class="form-control edu_description" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                </div>

                                                <button data-repeater-delete type="button" class="repeater-remove-btn">-</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create value="Add" class="repeater-add-btn">+</button>
                                </div>
                            </div>

                            <div class="cv-form-blk">
                                <div class="cv-form-row-title">
                                    <h3>skills</h3>
                                </div>

                                <div class="row-separator repeater">
                                    <div class="repeater" data-repeater-list="group-d">
                                        <div data-repeater-item>
                                            <div class="cv-form-row cv-form-row-skills">
                                                <div class="cols-2">
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Skill</label>
                                                        <input name="skill_title" type="text" class="form-control skill_title" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                </div>
                                                <button data-repeater-delete type="button" class="repeater-remove-btn">-</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create value="Add" class="repeater-add-btn">+</button>
                                </div>
                            </div>

                            <div class="cv-form-blk">
                                <div class="cv-form-row-title">
                                    <h3>Projects</h3>
                                </div>

                                <div class="row-separator repeater">
                                    <div class="repeater" data-repeater-list="group-e">
                                        <div data-repeater-item>
                                            <div class="cv-form-row cv-form-row-social">
                                                <div class="cols-2">
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">Social</label>
                                                        <input name="social_title" type="text" class="form-control social_title" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                    <div class="form-elem">
                                                        <label for="" class="form-label">URL</label>
                                                        <input name="social_url" type="text" class="form-control social_url" id="" onkeyup="generateCV()">
                                                        <span class="form-text"></span>
                                                    </div>
                                                </div>
                                                <button data-repeater-delete type="button" class="repeater-remove-btn">-</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create value="Add" class="repeater-add-btn">+</button>
                                </div>
                            </div>

                            <div class="form-submit-blk print-btn-sc right-side">
                                <input type="submit" class="btn-submit btn-primary text-uppercase print-btn btn btn-primary" value="Save">
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </section>

        <section class="right-section">
            <div class="container">
                <div id="cv-template"> 
                <!-- Your existing right section content here -->
                <section id = "preview-sc right-side" class = "print_area">
                    <div class = "container">
                        <div class = "preview-cnt">
                            <div class = "preview-cnt-l bg-dark text-white">
                                <div class = "preview-blk">
                                    <div class = "preview-image">
                                        <img src = "" alt = "" id = "image_dsp"> 
                                    </div>
                                    <div class = "preview-item preview-item-name">
                                        <span class = "preview-blk-title position fw-6" id = "fullname_dsp"></span>
                                    </div>
                                    <div class = "preview-item">
                                        <span class = "position text-uppercase fw-6 ls-1" id = "designation_dsp"></span>
                                    </div>
                                </div>
        
                                <div class = "preview-blk">
                                    <div class = "preview-blk-title">
                                        <h3>about</h3>
                                    </div>
                                    <div class = "preview-blk-list">
                                        <div class = "preview-item">
                                            <span class = "preview-blk-title" id = "phoneno_dsp"></span>
                                        </div>
                                        <div class = "preview-item">
                                            <span class = "preview-blk-title" id = "email_dsp"></span>
                                        </div>
                                        <div class = "preview-item">
                                            <span class = "preview-blk-title" id = "address_dsp"></span><br>
                                        </div>
                                        <div class = "preview-item">
                                            <span class = "preview-blk-title" id = "summary_dsp"></span>
                                        </div>
                                    </div>
                                </div>
        
                                <div class = "preview-blk">
                                    <div class = "preview-blk-title">
                                        <h3>skills</h3>
                                    </div>
                                    <div class = "skills-items preview-blk-title" id = "skills_dsp">
                                        <!-- skills list here -->
                                    </div>
                                </div>
                            </div>
        
                            <div class = "preview-cnt-r bg-white">
                                <div class = "preview-blk">
                                    <div class = "preview-blk-title">
                                        <h3>Achievements</h3>
                                    </div>
                                    <div class = "achievements-items preview-blk-list" id = "achievements_dsp"></div>
                                </div>
        
                                <div class = "preview-blk">
                                    <div class = "preview-blk-title">
                                        <h3>educations</h3>
                                    </div>
                                    <div class = "educations-items preview-blk-list" id = "educations_dsp"></div>
                                </div>
        
                                <div class = "preview-blk">
                                    <div class = "preview-blk-title">
                                        <h3>experiences</h3>
                                    </div>
                                    <div class = "experiences-items preview-blk-list" id = "experiences_dsp"></div>
                                </div>
        
                                <div class = "preview-blk">
                                    <div class = "preview-blk-title">
                                        <h3>projects</h3>
                                    </div>
                                    <div class = "projects-items preview-blk-list" id = "projects_dsp"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
        
                <section class = "print-btn-sc right-side">
                    <div class = "container">
                        <button type = "button" class = "print-btn btn btn-primary" onclick="printCV()">Print CV</button>
                        <button type = "button" class = "print-btn btn btn-primary">Download PDF</a></button>
                    </div>
                </section>
            </section>
        </div>

        <!-- jquery cdn -->
        <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
        <!-- jquery repeater cdn -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.js" integrity="sha512-bZAXvpVfp1+9AUHQzekEZaXclsgSlAeEnMJ6LfFAvjqYUVZfcuVXeQoN5LhD7Uw0Jy4NCY9q3kbdEXbwhZUmUQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- custom js -->
        <script src = "assets/js/script.js"></script>
        <!-- app js -->
        <script src="assets/js/app.js"></script>
        <script>
        
        const toggler = document.querySelector(".navbar-toggler");
        const links = document.querySelector(".navbar-links");

        toggler.addEventListener("click", () => {
        links.classList.toggle("open");
        });
     

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
        if (!event.target.matches('.btn')) {
            var dropdowns = document.getElementsByClassName("drop_list");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
            var buttons = document.getElementsByClassName("btn");
            for (var j = 0; j < buttons.length; j++) {
                var openButton = buttons[j];
                if (openButton.classList.contains('show')) {
                    openButton.classList.remove('show');
                }
            }
        }
    }
    </script>
    </body>
</html>