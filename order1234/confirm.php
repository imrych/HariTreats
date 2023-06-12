<!DOCTYPE html>

<!---from kier-->


<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Logout Confirmation</title>
    <link rel="icon" href="icon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
      body {
        background-color: #51050f;
        color: white;
        font-family: "Poppins", sans-serif;
      }

      .wrapper {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        padding: 50px;
        max-width: 400px;
        width: 90%;
        border-radius: 0.8rem;
        background-color: rgba(0, 0, 0, 0.4);
      }

      .wrapper img {
        width: 100px;
      }

      .wrapper h1 {
        font-size: 30px;
      }

      .wrapper h3 {
        font-size: 15px;
        font-weight: 400;
      }

      .wrapper .field {
        height: 60px;
      }

      .wrapper input[type="text"] {
        width: 250px;
        height: 30px;
        outline: none;
        border: none;
        border-radius: 0.3rem;
        padding-left: 35px;
      }

      .wrapper a.button {
        display: inline-block;
        width: 100%;
        max-width: 290px;
        height: 35px;
        line-height: 35px;
        border: none;
        border-radius: 0.3rem;
        color: white;
        background-color: #51050f;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
      }

      .wrapper a.button:hover {
        background-color: #6e101c;
      }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <h1>Logout Confirmation</h1>
      <h3>Are you sure you want to log out?</h3>
      <div class="inputs">
        <a class="button" href="admin/ajax.php?action=logout2">Confirm</a>
        <a class="button" href="index.php">Cancel</a>
      </div>
    </div>

    <script>
      // You can keep the rest of the JavaScript code as it is
      const closeButton = document.querySelector(".close-button");
      closeButton.addEventListener("click", () => {
        document.querySelector(".wrapper").style.display = "none";
      });
    </script>
  </body>
</html>