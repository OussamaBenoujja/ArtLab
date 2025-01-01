<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create/Modify Article</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .article-content {
            height: 80vh; /* Set the height of the article content section */
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex flex-col md:flex-row h-screen">
        <div class="left-column w-full md:w-2/3 bg-gray-200 p-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Article Content</h2>
            <textarea class="article-content w-full border rounded p-2 focus:outline-none focus:ring focus:ring-blue-200" placeholder="Write your article here..." required></textarea>
        </div>
        <div class="right-column w-full md:w-1/3 bg-gray-300 p-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Other Details</h2>
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Article Title</label>
                <input type="text" id="title" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" placeholder="Enter the title of your article" required>
            </div>

            <div class="mb-4">
                <label for="bannerImage" class="block text-gray-700 font-medium mb-2">Upload Banner Image</label>
                <input type="file" id="bannerImage" accept="image/*" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Article</button>
        </div>
    </div>

    <footer class="bg-white p-4 shadow-md mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-600">Copyright &copy; 2023 Article Hub. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>