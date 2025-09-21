<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoltare Wallpaper</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
  <!-- Navigation -->
  <nav class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <a href="/Zoltare/" class="text-2xl font-bold text-indigo-600">Zoltare Wallpaper</a>
      <div class="space-x-4">
        <a href="/Zoltare/gallery" class="text-gray-600 hover:text-indigo-600">Gallery</a>
        <a href="/Zoltare/upload" class="text-gray-600 hover:text-indigo-600">Upload</a>
        <?php
        session_start();
        if (isset($_SESSION['user_id'])) {
          echo "<a href='/Zoltare/api/logout' class='text-gray-600 hover:text-indigo-600'>Logout</a>";
        } else {
          echo "<a href='/Zoltare/login' class='text-gray-600 hover:text-indigo-600'>Login</a>";
        }
        ?>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <header class="container mx-auto px-6 py-16 text-center">
    <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Discover & Collect Stunning Wallpapers</h1>
    <p class="text-gray-700 mb-8">High-resolution digital art and photography for your desktop. Free & Premium.</p>
    <a href="/Zoltare/gallery" class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Browse Gallery</a>
  </header>

  <!-- Footer -->
  <footer class="bg-white py-6 mt-16">
    <div class="container mx-auto px-6 text-center text-gray-500">
      &copy; 2025 Zoltare Wallpaper.
    </div>
  </footer>

  <script src="scripts.js"></script>
</body>
</html>
