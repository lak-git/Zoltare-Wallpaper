<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoltare/Admin | Add Wallapaper</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="styles.css">
  <script src="./scripts/upload.js" defer></script>
</head>
<body class="bg-gray-50">
  <!-- Navigation -->
  <nav class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <a href="/Zoltare/admin" class="text-2xl font-bold text-indigo-600">Zoltare Admin</a>
      <div class="space-x-4">
        <a href="/Zoltare/gallery" class="text-gray-600 hover:text-indigo-600">Gallery</a>
        <?php
        if (isset($_SESSION['user_id'])) {
          echo "<a href='/Zoltare/api/logout' class='text-gray-600 hover:text-indigo-600'>Logout</a>";
        } else {
          echo "<a href='/Zoltare/login' class='text-gray-600 hover:text-indigo-600'>Login</a>";
        }
        ?>
      </div>
    </div>
  </nav>
  <main class="container mx-auto px-6 py-10">
    <h2 class="text-3xl font-semibold mb-6">Add Wallpaper</h2>
    <form action="/Zoltare/api/admin/addwallpaper" method="post" enctype="multipart/form-data" name="uploadForm" id="uploadForm" class="bg-white p-6 rounded-lg shadow-md">
      <label class="block mb-2">Title</label>
        <input type="text" name="title" id="title" class="w-full p-2 border rounded mb-4" required>
      <label class="block mb-2">Category</label>
        <select id="category" name="category" class="w-full p-2 border rounded mb-4" required>
          <option value="" disabled selected>Select a category</option>
          <option value="scifi">Sci-Fi</option>
          <option value="nature">Nature</option>
          <option value="architecture">Architecture</option>
          <option value="sky">Sky</option>
          <option value="space">Space</option>
        </select>
      <label class="block mb-2">File</label>
        <input type="file" name="file" id="file" accept="image/*" class="w-full mb-4" required>
      <div id="paidDetails" class="bg-gray-50 border border-gray-200 p-4 rounded mb-4">
        <label class="block mb-2">Sale Price (USD)</label>
        <input type="number" id="price" name="price" step="0.01" min="0.01" placeholder="e.g. 1.99" class="w-full p-2 border rounded mb-4">
      </div>
      <!-- Submit / Reset buttons -->
      <div class="flex items-center space-x-2">
        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Upload</button>
        <button type="reset" id="resetBtn" class="py-2 px-4 border rounded">Reset</button>
      </div>
    </form>
  </main>
</body>
</html>
