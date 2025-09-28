<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post News - Daycare Admin</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f0fa;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      background: white;
      max-width: 600px;
      width: 90%;
      margin-top: 50px;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #6a0dad;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      color: #333;
    }

    input[type="text"],
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      resize: none;
    }

    textarea {
      height: 150px;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 12px;
      background-color: #8e6cc6;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background-color: white;
      color: #6a0dad;
      border: 1px solid #6a0dad;
    }

    @media (max-width: 500px) {
      .container {
        padding: 15px;
      }
    }
    .bg-image {
      background-image: url(images\kiddies.jpg);
      filter: blur(4px);
      height: 100%;
      background-position: center;
      background-size: cover;
      position: absolute;
      width: 100%;
      z-index: -1;
    }
  </style>
</head>
<body>
<img src="images/kiddies.jpg" class="bg-image" alt="Background Image">
  <div class="container">
    <h2>Post News</h2>
    <form action="includes/postNews.inc.php" method="POST">
      <label for="newsTitle">News Title:</label>
      <input type="text" id="newsTitle" name="news_title" placeholder="Enter news title" required>

      <label for="newsContent">News Content:</label>
      <textarea id="newsContent" name="news_content" placeholder="Write the news details..." required></textarea>

      <label for="newsImage">Upload Image:</label>
      <input type="file" id="newsImage" name="photo" accept="image/*">

      <button type="submit" class="btn">Post News</button>
    </form>
  </div>

</body>
</html>
