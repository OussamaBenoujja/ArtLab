<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <header class="bg-white shadow-md p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Article Hub</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Home</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Profile</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">About</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <label for="category" class="mr-2 font-medium">Category:</label>
                <select id="category" class="border rounded p-2 focus:outline-none focus:ring focus:ring-blue-200">
                    <option value="">All</option>
                    <option value="tech">Technology</option>
                    <option value="health">Health</option>
                    <option value="travel">Travel</option>
                </select>
            </div>
            <div>
                <label for="sort" class="mr-2 font-medium">Sort by:</label>
                <select id="sort" class="border rounded p-2 focus:outline-none focus:ring focus:ring-blue-200">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Article Card 1 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800">Article Title 1</h2>
                    <p class="text-gray-600 mt-2">This is a brief description of the article to give an overview of the content.</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-gray-500 text-sm">By Author Name</span>
                        <span class="text-gray-500 text-sm">2023-01-01</span>
                    </div>
                    <div class="flex items-center mt-4">
                        <button class="text-green-600 mr-2">👍</button>
                        <span class="text-gray-500 text-sm">Upvotes: 12</span>
                    </div>
                </div>
            </div>

            <!-- Article Card 2 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800">Article Title 2</h2>
                    <p class="text-gray-600 mt-2">This is a brief description of the article to give an overview of the content.</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-gray-500 text-sm">By Author Name</span>
                        <span class="text-gray-500 text-sm">2023-01-02</span>
                    </div>
                    <div class="flex items-center mt-4">
                        <button class="text-green-600 mr-2">👍</button>
                        <span class="text-gray-500 text-sm">Upvotes: 7</span>
                    </div>
                </div>
            </div>

            <!-- Article Card 3 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800">Article Title 3</h2>
                    <p class="text-gray-600 mt-2">This is a brief description of the article to give an overview of the content.</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-gray-500 text-sm">By Author Name</span>
                        <span class="text-gray-500 text-sm">2023-01-03</span>
                    </div>
                    <div class="flex items-center mt-4">
                        <button class="text-green-600 mr-2">👍</button>
                        <span class="text-gray-500 text-sm">Upvotes: 5</span>
                    </div>
                </div>
            </div>

            <!-- Add more article cards as needed -->

        </div>
    </main>

    <footer class="bg-white p-4 shadow-md mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-600">© 2023 Article Hub. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>