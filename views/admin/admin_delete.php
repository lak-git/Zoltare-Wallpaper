<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoltare/Admin | Delete Wallpaper</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
  <!-- Navigation (reuse) -->
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

  <!-- Gallery Grid -->
  <main class="container mx-auto px-6 py-10">
    <div class="grid gap-6 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
      <!-- repeat this card -->
      <?php foreach ($wallpapers as $wp): ?>
      <div class="bg-white rounded-lg overflow-hidden shadow">
        <img src="../<?php echo $wp['image_url'];?>" alt="Wallpaper" class="w-full h-40 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg"><?php echo $wp['title'];?></h3>
          <p class='text-gray-700 mt-1 text-sm'><?php echo $wp['category'];?></p>
          <form class="inline" method="post" action="/Zoltare/api/admin/deletewallpaper" onsubmit="return confirm('Delete this wallpaper permanently?');">
            <input type="hidden" name="wallpaper_id" value="<?php echo $wp['wallpaper_id'];?>"/>
            <input type="hidden" name="filepath" value="<?php echo $wp['image_url'];?>"/>
            <button type="submit" class="mt-2 block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 favorite-btn">
            Delete
          </button>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
      <!-- end card -->
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-white py-6 mt-16">
    <div class="container mx-auto px-6 text-center text-gray-500">
      &copy; 2025 Zoltare Wallpaper.
    </div>
  </footer>
</body>
</html>
