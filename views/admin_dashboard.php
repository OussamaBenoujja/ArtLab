<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-1/4 p-5">
            <h2 class="text-xl font-bold mb-5">Admin Dashboard</h2>
            <ul>
                <li><a href="#members" class="side-link block py-2 hover:bg-gray-700 rounded">Members</a></li>
                <li><a href="#authors" class="side-link block py-2 hover:bg-gray-700 rounded">Authors</a></li>
                <li><a href="#articles" class="side-link block py-2 hover:bg-gray-700 rounded">Articles</a></li>
                <li><a href="#categories" class="side-link block py-2 hover:bg-gray-700 rounded">Categories</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">

            <!-- Search Bar -->
            <div class="mb-4">
                <input type="text" class="border rounded p-2 w-full focus:outline-none focus:ring focus:ring-blue-200" placeholder="Search for a member or author...">
            </div>

            <!-- Members Section -->
            <div id="members" class="dashboard-section mb-10 hidden">
                <h2 class="text-2xl font-bold mb-4">Members</h2>
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="w-full bg-blue-600 text-white">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">John Doe</td>
                            <td class="border px-4 py-2">john@example.com</td>
                            <td class="border px-4 py-2">
                                <button class="text-red-500">Delete</button>
                                <button class="text-yellow-500">Ban</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Authors Section -->
            <div id="authors" class="dashboard-section mb-10 hidden">
                <h2 class="text-2xl font-bold mb-4">Authors</h2>
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="w-full bg-blue-600 text-white">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">Jane Smith</td>
                            <td class="border px-4 py-2">jane@example.com</td>
                            <td class="border px-4 py-2">
                                <button class="text-red-500">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Articles Section -->
            <div id="articles" class="dashboard-section mb-10 hidden">
                <h2 class="text-2xl font-bold mb-4">Articles</h2>
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="w-full bg-blue-600 text-white">
                            <th class="py-2">Title</th>
                            <th class="py-2">Author</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">Sample Article Title</td>
                            <td class="border px-4 py-2">Jane Smith</td>
                            <td class="border px-4 py-2">
                                <button class="text-red-500">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Categories Section -->
            <div id="categories" class="dashboard-section hidden">
                <h2 class="text-2xl font-bold mb-4">Categories</h2>
                <table class="min-w-full bg-white rounded shadow mb-4">
                    <thead>
                        <tr class="w-full bg-blue-600 text-white">
                            <th class="py-2">Category Name</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">Technology</td>
                            <td class="border px-4 py-2">
                                <button class="text-red-500">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button id="add-category" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Add Category</button>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="category-modal" class="modal hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded p-5 w-1/3">
            <h2 class="text-lg font-bold mb-4">Add New Category</h2>
            <input type="text" id="new-category-name" class="border rounded p-2 w-full mb-4" placeholder="Category Name">
            <div class="flex justify-end mt-4">
                <button id="save-category" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Save</button>
                <button class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 modal-close ml-2">Close</button>
            </div>
        </div>
    </div>

    <script>
        
        const links = document.querySelectorAll('.side-link');
        const sections = document.querySelectorAll('.dashboard-section');
        const modal = document.getElementById('category-modal');
        const addCategoryButton = document.getElementById('add-category');
        const modalCloseButtons = document.querySelectorAll('.modal-close');

        
        sections.forEach(section => section.classList.add('hidden'));

        
        function showSection(event) {
            sections.forEach(section => section.classList.add('hidden')); // Hide all
            const targetId = event.currentTarget.getAttribute('href').substring(1); // Get the ID from the href
            const targetSection = document.getElementById(targetId); // Find the target section by ID
            if (targetSection) {
                targetSection.classList.remove('hidden'); // Show the target section
            }
        }

       
        links.forEach(link => {
            link.addEventListener('click', showSection);
        });

    
        addCategoryButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        
        modalCloseButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });

        
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        
        document.getElementById('save-category').addEventListener('click', () => {
            const newCategoryName = document.getElementById('new-category-name').value;
            if (newCategoryName) {
                
                console.log(`New category added: ${newCategoryName}`);
                modal.classList.add('hidden'); 
                document.getElementById('new-category-name').value = ''; 
            } else {
                alert("Please enter a category name.");
            }
        });
    </script>

</body>

</html>