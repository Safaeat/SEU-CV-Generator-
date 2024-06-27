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

// Fetch CVs for the logged-in user
$sql = "SELECT id, firstname, lastname, designation FROM cv_data WHERE user_id=?";
//$stmt = $conn->prepare($sql);
//$stmt->bind_param("i", $_SESSION['user_id']); // Assuming 'user_id' is stored in the session
//$stmt->execute();
//$result = $stmt->get_result();
//$cvs = $result->fetch_all(MYSQLI_ASSOC);
//$stmt->close();
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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="assets/images/seu_logo.png">
    <link rel="stylesheet" href="assets/css/welcome.css">
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
      .navbar-links a:hover {
        color: blue;
      }

      /* Popup container */
      /* Popup container */
      .popup {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
        justify-content: center;
        align-items: center;
      }

      .popup-content {
        background-color: #fff;
         margin: 15% auto;
         padding: 20px;
         border: 1px solid #888;
         width: 80%;
         max-width: 600px;
         text-align: center;
         position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
             right: 20px;
             font-size: 30px;
             cursor: pointer;
        }

        .templates {
             display: flex;
             justify-content: space-around;
             margin-top: 20px;
        }

        .template {
           text-align: center;
        }

        .template img {
            width: 100px;
            height: 140px;
            object-fit: cover;
        }

        .template-btn {
            display: block;
           margin-top: 10px;
           padding: 10px;
           background-color: #007BFF;
           color: white;
           text-decoration: none;
           border-radius: 5px;
        }

        .template-btn:hover {
             background-color: #0056b3;
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
      .background {
        background-color: #001c3354;;
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
          <a href="logout.php">Log Out</a>
          <a href="#" id="generateCVLink">Generate CV</a>
        </div>
      </div>
    </nav>
    <!-- The popup container -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <h2>Select a CV Template</h2>
            <div class="templates">
                <div class="template">
                    <img src="assets/images/cv1.png" alt="CV Template 1">
                    <a href="resume.php" class="template-btn">Choose Template 1</a>
                </div>
                <div class="template">
                    <img src="assets/images/cv2.png" alt="CV Template 2">
                    <a href="template2_link" class="template-btn">Choose Template 2</a>
                </div>
                <div class="template">
                    <img src="assets/images/cv6.jpg" alt="CV Template 3">
                    <a href="resume3.php" class="template-btn">Choose Template 3</a>
                </div>
            </div>
        </div>
    </div>
  
    <div class="flex bg-red">
      <!-- sidebar -->
      <div class="w-84 h-screen background overflow-hidden items-center">
        <div class="flex flex-col justify-between h-full px-14 py-10">
          <div class="mx-auto">
            <!-- profile -->
            <div>
              <div class="border p-1.5 rounded-full w-fit mx-auto">
                <div class="h-20 w-20 rounded-full overflow-hidden relative">
                  <img
                    class="absolute top-0 left-0 w-full h-full object-cover"
                    src="#"
                  />
                </div>
              </div>
              <div class="text-center">
                <h3 class="text-xl" style="font-family: 'Itim', 'cursive'"><br>
                  <?php echo $_SESSION['username']; ?>
                </h3>
                <div class="flex items-center justify-center gap-1">
                </div>
              </div>
            </div>
            <!-- menu items -->
            <div class="mx-auto mt-12">
              <a
                href="#"
                class="flex items-center gap-2 text-gray-500 hover:text-gray-800 mb-3"
              >
                <i class="ri-book-read-fill"></i>
                Dashboard
              </a>
              <a
                href="#"
                class="flex items-center gap-2 text-gray-500 hover:text-gray-800 mb-3"
              >
                <i class="ri-heart-fill"></i>
                Profile
              </a>
              <a
                href="#"
                class="flex items-center gap-2 text-gray-500 hover:text-gray-800 mb-3"
              >
                <i class="ri-award-fill"></i>
                Templates
              </a>
              <a
                href="#"
                class="flex items-center gap-2 text-gray-500 hover:text-gray-800 mb-3"
              >
                <i class="ri-global-fill"></i>
                Your CV
              </a>
              <a
                href="#"
                class="flex items-center gap-2 text-gray-500 hover:text-gray-800 mb-3"
              >
                <i class="ri-global-fill"></i>
                Setting
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- main -->
      <div class="bg-white-50 w-full h-screen overflow-y-scroll p-8">
       
        <!-- content -->
        <div>
          <!-- title -->
          <div class="flex justify-between items-center mt-10">
            <div class="flex items-center gap-5">
              <div>
                <div
                  class="text-5xl font-bold mb-1"
                  style="font-family: 'Itim', 'cursive'"
                >
                  Welcome back!
                </div>
                <div class="text-gray-500">Make your own resume.</div>
              </div>
            </div>
            <div class="flex items-center">
              <div class="border-r-2 border-gray-300 pr-5">
                <div class="text-4xl font-bold">03</div>
                <div class="text-gray-500">Total Template</div>
              </div>
              <div class="pl-5">
                <div class="text-4xl font-bold">00</div>
                <div class="text-gray-500">CV Created</div>
              </div>
            </div>
          </div>
          <!-- cards -->
          <div class="grid grid-cols-4 gap-8 mt-10">
            <div
              class="border rounded-xl bg-white overflow-hidden shadow-md hover:shadow-xl"
            >
              <div class="p-5 pb-0">
                <img
                  class="h-200 w-140 mx-auto"
                  src="./assets/images/cv1.png"
                />
                <div class="text-lg font-semibold text-center w-full mt-2">
                  Template One
                </div>
              </div> 
            </div>
          
            <div
              class="border rounded-xl bg-white overflow-hidden shadow-md hover:shadow-xl"
            >
              <div class="p-5 pb-0">
                <img
                  class="h-200 w-140 mx-auto"
                  src="./assets/images/cv6.jpg"
                />
                <div class="text-lg font-semibold text-center w-full mt-2">
                  Template Two
                </div>
              </div> 
            </div>

            <div
              class="border rounded-xl bg-white overflow-hidden shadow-md hover:shadow-xl"
            >
              <div class="p-5 pb-0">
                <img
                  class="h-200 w-140 mx-auto"
                  src="./assets/images/cv2.png"
                />
                <div class="text-lg font-semibold text-center w-full mt-2">
                  Template Three
                </div>
              </div> 
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ##### jQuery (Necessary for All JavaScript Plugins) ##### -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>
    <script src="/assets/js/script.js"></script>
     <script>
    //popup
    document.getElementById('generateCVLink').addEventListener('click', function(event) {
    event.preventDefault();  // Prevent the default link behavior
    document.getElementById('popup').style.display = 'flex';
});

document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'none';
});

// Close the popup when clicking outside of it
window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('popup')) {
        document.getElementById('popup').style.display = 'none';
    }
});


      const toggler = document.querySelector(".navbar-toggler");
      const links = document.querySelector(".navbar-links");

      toggler.addEventListener("click", () => {
        links.classList.toggle("open");
      });
     </script>
</body>
</html>
