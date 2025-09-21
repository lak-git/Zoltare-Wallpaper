<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoltare | Login</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
  <nav class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <a href="/Zoltare" class="text-2xl font-bold text-indigo-600">Zoltare Wallpaper</a>
      <div class="space-x-4">
        <a href="/Zoltare/gallery" class="text-gray-600 hover:text-indigo-600">Gallery</a>
        <a href="/Zoltare/upload" class="text-gray-600 hover:text-indigo-600">Upload</a>
      </div>
    </div>
  </nav>
  <div class="flex items-center justify-center min-h-screen">
    <form method="post" action="/Zoltare/api/login" class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6">Login</h2>
      <!-- Email -->
      <label class="block mb-2">Email</label>
        <input type="email" name="email" id="email" class="w-full p-2 border rounded mb-4" required>
      <!-- Passowrd -->
      <label class="block mb-2">Password</label>
        <input type="password" name="password" id="password" class="w-full p-2 border rounded mb-4" required>
      
      <!-- Submit -->
      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Login</button>
      <p class="mt-4 text-sm text-center">Don't have an account? <a href="./register" class="text-indigo-600">Sign up</a></p>
    </form>
  </div>
</body>
</html>
