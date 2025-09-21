<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Zoltare/Admin | Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
  <!-- Navigation -->
  <nav class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <a href="/Zoltare/admin" class="text-2xl font-bold text-indigo-600">Zoltare Admin</a>
      <div class="space-x-4">
        <a href="/Zoltare/gallery" class="text-gray-600 hover:text-indigo-600">Gallery</a>
        <a href="/Zoltare/api/logout" class="text-gray-600 hover:text-indigo-600">Logout</a>
      </div>
    </div>
  </nav>

  <main class="container mx-auto px-6 py-10">
    <h1 class="text-3xl font-semibold mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="card p-6 text-center">
        <h2 class="text-xl font-semibold">Total Users</h2>
        <p class="mt-4 text-3xl font-bold"><?php echo (int)$totalUsers;?></p>
      </div>

      <div class="card p-6 text-center">
        <h2 class="text-xl font-semibold">Total Wallpapers</h2>
        <p class="mt-4 text-3xl font-bold"><?php echo (int)$totalWallpapers; ?></p>
      </div>

      <div class="card p-6 text-center">
        <h2 class="text-xl font-semibold">Total Purchases</h2>
        <p class="mt-4 text-3xl font-bold"><?php echo (int)$totalPurchases; ?></p>
      </div>
    </div>

    <section class="mt-10">
      <h3 class="text-2xl font-semibold mb-4">Quick Actions</h3>
      <div class="flex space-x-4">
        <a href="/Zoltare/admin/upload" class="py-2 px-4 border rounded">Add Wallpaper</a>
        <a href="/Zoltare/admin/edit" class="py-2 px-4 border rounded">Edit Wallpaper</a>
        <a href="/Zoltare/admin/delete" class="py-2 px-4 border rounded">Remove Wallpapers</a>
        <a href="/Zoltare/admin/errors" class="py-2 px-4 border rounded">View Error Log</a>
      </div>
    </section>
  </main>

  <footer class="bg-white py-6 mt-16">
    <div class="container mx-auto px-6 text-center text-gray-500">&copy; 2025 Zoltare Wallpaper.</div>
  </footer>
</body>
</html>
