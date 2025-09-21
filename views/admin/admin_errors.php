<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Zoltare/Admin | Error Logs</title>
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
    <table class="w-full-border-collpase mt-4">
        <thead>
            <tr class="bg-gray-50">
                <th class="border p-2 text-left">ID</th>
                <th class="border p-2 text-left">Message</th>
                <th class="border p-2 text-left">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($errors as $err): ?>
                <tr>
                    <td class="border p-2">
                        <?php echo "{$err['error_id']}" ?>
                    </td>
                    <td class="border p-2">
                        <?php echo "{$err['message']}" ?>
                    </td>
                    <td class="border p-2">
                        <?php echo "{$err['timestamp']}" ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </main>

  <footer class="bg-white py-6 mt-16">
    <div class="container mx-auto px-6 text-center text-gray-500">&copy; 2025 Zoltare Wallpaper.</div>
  </footer>
</body>
</html>
