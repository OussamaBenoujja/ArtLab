<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-lg">
        <div class="flex flex-col items-center mb-4">
            <img src='pfp.img' alt='Profile Image' class="w-24 h-24 rounded-full shadow-md mb-4">
            <h1 class="text-2xl font-semibold text-gray-800">UserName</h1>
            <p class="text-gray-600 text-center">Bio: Here goes a short biography about the user. This section can include interests and hobbies.</p>
            <p class="text-gray-500 text-sm">Joined on: <span class="font-medium">0000-00-00</span></p>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">About Me</h2>
            <p class="text-gray-600">Expand your biography here. Talk about your interests, preferences, and more.</p>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Social Links</h2>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="text-blue-600 hover:underline">Twitter</a>
                </li>
                <li>
                    <a href="#" class="text-blue-600 hover:underline">LinkedIn</a>
                </li>
                <li>
                    <a href="#" class="text-blue-600 hover:underline">GitHub</a>
                </li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Liked Articles</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border">Title</th>
                        <th class="py-2 px-4 border">Author</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border">Article Title 1</td>
                        <td class="py-2 px-4 border">Author Name 1</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border">Article Title 2</td>
                        <td class="py-2 px-4 border">Author Name 2</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border">Article Title 3</td>
                        <td class="py-2 px-4 border">Author Name 3</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>