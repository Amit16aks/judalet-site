<?php
ob_start();
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Products</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-panel {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            background-color: #e0f7fa;
        }
        .form-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            align-items: flex-start;
        }
        .form-row .first-line {
            display: flex;
            gap: 10px;
            width: 100%;
        }
        .form-row .second-line {
            display: flex;
            gap: 10px;
            width: 100%;
            align-items: flex-start;
        }
        .form-row input, .form-row select, .form-row textarea {
            flex: 1;
            min-width: 200px;
            height: 35px;
            padding: 5px;
            box-sizing: border-box;
        }
        .form-row textarea {
            height: 60px;
            resize: vertical;
        }
        .compact-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .compact-table th {
            background-color: #0288d1;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .compact-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .action-buttons button {
            margin: 2px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .edit-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 450px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .edit-modal label {
            display: block;
            margin: 5px 0;
            font-weight: bold;
        }
        .edit-modal input, .edit-modal select, .edit-modal textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .drop-zone {
            border: 2px dashed #ccc;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
            background: #f9f9f9;
            min-width: 250px;
            height: 50px;
            cursor: pointer;
        }
        .drop-zone.dragover {
            border-color: #FF69B4;
            background: #fff5f7;
        }
        .preview-images {
            display: flex;
            gap: 5px;
            margin-top: 5px;
            flex-wrap: wrap;
        }
        .preview-images img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
            position: relative;
        }
        .preview-images .remove-btn {
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px"><path fill="%23000000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>') no-repeat center;
            background-size: contain;
            border: none;
            cursor: pointer;
            filter: drop-shadow(0 0 2px white);
        }
        .preview-images .remove-btn:hover {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px"><path fill="%23FF0000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>') no-repeat center;
            background-size: contain;
        }
        .compact-table td img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
        }
        .special-checkbox {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .special-checkbox input[type="checkbox"] {
            transform: scale(1);
            margin: 0;
        }
        @media (max-width: 600px) {
            .form-row .first-line, .form-row .second-line {
                flex-direction: column;
            }
            .form-row input, .form-row select, .form-row textarea, .form-row .drop-zone {
                width: 100% !important;
                min-width: 0;
            }
            .compact-table {
                font-size: 0.8rem;
            }
            .compact-table td, .compact-table th {
                padding: 5px;
            }
            .add-product-btn {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <h2>Manage Products</h2>
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="admin.php" style="color: #ff69b4; text-decoration: none; font-weight: bold;">Back to Dashboard</a>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['add_product']) && isset($_FILES['images'])) {
                $name = $conn->real_escape_string($_POST['name']);
                $price = $conn->real_escape_string($_POST['price']);
                $category = $conn->real_escape_string($_POST['category']);
                $subcategory = $conn->real_escape_string($_POST['subcategory']);
                $description = $conn->real_escape_string($_POST['description']);
                $special = isset($_POST['special']) ? 1 : 0;
                $images = [];

                $uploadDir = "Uploads/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $fileName = basename($_FILES['images']['name'][$key]);
                    $targetFile = $uploadDir . uniqid() . '_' . $fileName;
                    if (move_uploaded_file($tmp_name, $targetFile)) {
                        $images[] = $targetFile;
                    }
                }
                $imagesStr = implode(',', $images);

                $sql = "INSERT INTO menu (name, price, category, subcategory, description, special, images) VALUES ('$name', '$price', '$category', '$subcategory', '$description', '$special', '$imagesStr')";
                if ($conn->query($sql) === TRUE) {
                    ob_end_clean();
                    header("Location: admin_products.php?success=1");
                    exit();
                } else {
                    echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
                }
            } elseif (isset($_POST['delete_product'])) {
                $id = $conn->real_escape_string($_POST['id']);
                $sql = "DELETE FROM menu WHERE id='$id'";
                if ($conn->query($sql) === TRUE) {
                    ob_end_clean();
                    header("Location: admin_products.php?success=2");
                    exit();
                } else {
                    echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
                }
            } elseif (isset($_POST['edit_product']) && isset($_FILES['images'])) {
                $id = $conn->real_escape_string($_POST['id']);
                $name = $conn->real_escape_string($_POST['name']);
                $price = $conn->real_escape_string($_POST['price']);
                $category = $conn->real_escape_string($_POST['category']);
                $subcategory = $conn->real_escape_string($_POST['subcategory']);
                $description = $conn->real_escape_string($_POST['description']);
                $special = isset($_POST['special']) ? 1 : 0;
                $existingImages = explode(',', $_POST['existing_images']);
                $newImages = [];

                $uploadDir = "Uploads/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if ($tmp_name) {
                        $fileName = basename($_FILES['images']['name'][$key]);
                        $targetFile = $uploadDir . uniqid() . '_' . $fileName;
                        if (move_uploaded_file($tmp_name, $targetFile)) {
                            $newImages[] = $targetFile;
                        }
                    }
                }
                $allImages = array_merge($existingImages, $newImages);
                $imagesStr = implode(',', array_filter($allImages));

                $sql = "UPDATE menu SET name='$name', price='$price', category='$category', subcategory='$subcategory', description='$description', special='$special', images='$imagesStr' WHERE id='$id'";
                if ($conn->query($sql) === TRUE) {
                    ob_end_clean();
                    header("Location: admin_products.php?success=3");
                    exit();
                } else {
                    echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
                }
            }
        }

        $successMessage = "";
        if (isset($_GET['success'])) {
            switch ($_GET['success']) {
                case '1':
                    $successMessage = "<p style='color:green;'>Product added successfully!</p>";
                    break;
                case '2':
                    $successMessage = "<p style='color:green;'>Product deleted successfully!</p>";
                    break;
                case '3':
                    $successMessage = "<p style='color:green;'>Product updated successfully!</p>";
                    break;
            }
        }
        echo $successMessage;

        $sql = "SELECT * FROM menu";
        $result = $conn->query($sql);
        ?>

        <h3>Add New Product</h3>
        <form method="POST" class="form-row" enctype="multipart/form-data">
            <div class="first-line">
                <input type="text" name="name" placeholder="Name" required>
                <input type="number" name="price" step="0.01" placeholder="Price" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="text" name="subcategory" placeholder="Subcategory" required>
                <label class="special-checkbox">Special: <input type="checkbox" name="special" value="1"></label>
            </div>
            <div class="second-line">
                <textarea name="description" placeholder="Description" rows="2"></textarea>
                <div class="drop-zone" id="dropZone">Drag & Drop Images Here or Click to Upload</div>
                <input type="file" name="images[]" id="fileInput" multiple style="display:none;">
                <div class="preview-images" id="preview"></div>
                <button type="submit" name="add_product" class="add-product-btn">Add Product</button>
            </div>
        </form>

        <h3>Existing Products</h3>
        <table border="1" class="compact-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Special</th>
                <th>Description</th>
                <th>Images</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>$" . number_format($row['price'], 2) . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['subcategory'] . "</td>";
                    echo "<td>" . ($row['special'] ? 'Yes' : 'No') . "</td>";
                    echo "<td><button class='description-view-btn' onclick='showDescription(\"" . htmlspecialchars($row['description']) . "\")'>View</button></td>";
                    echo "<td>";
                    $imageArray = explode(',', $row['images']);
                    foreach ($imageArray as $image) {
                        if (!empty(trim($image))) {
                            echo "<img src='" . htmlspecialchars(trim($image)) . "' alt='Thumbnail' style='margin: 2px;'>";
                        }
                    }
                    echo "</td>";
                    echo "<td class='action-buttons'>
                        <button onclick='openEditModal(" . $row['id'] . ", \"" . htmlspecialchars($row['name']) . "\", " . $row['price'] . ", \"" . htmlspecialchars($row['category']) . "\", \"" . htmlspecialchars($row['subcategory']) . "\", " . $row['special'] . ", \"" . htmlspecialchars($row['images']) . "\", \"" . htmlspecialchars($row['description']) . "\")'>Edit</button>
                        <form method='POST' style='display:inline;' onsubmit='return confirmDelete(this);'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_product'>Delete</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No products found</td></tr>";
            }
            ?>
        </table>

        <!-- Edit Modal -->
        <div id="modal-overlay" class="modal-overlay" onclick="closeEditModal()"></div>
        <div id="edit-modal" class="edit-modal">
            <h3>Edit Product</h3>
            <form method="POST" id="edit-form" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit-id">
                <input type="hidden" name="existing_images" id="edit-existing-images">
                <label>Name:</label>
                <input type="text" name="name" id="edit-name" required>
                <label>Price:</label>
                <input type="number" name="price" id="edit-price" step="0.01" required>
                <label>Category:</label>
                <input type="text" name="category" id="edit-category" required>
                <label>Subcategory:</label>
                <input type="text" name="subcategory" id="edit-subcategory" required>
                <label>Description:</label>
                <textarea name="description" id="edit-description" rows="2"></textarea>
                <label>Special:</label>
                <input type="checkbox" name="special" id="edit-special" value="1">
                <label>Upload New Images:</label>
                <div class="drop-zone" id="editDropZone">Drag & Drop Images Here or Click to Upload</div>
                <input type="file" name="images[]" id="editFileInput" multiple style="display:none;">
                <div class="preview-images" id="editPreview"></div>
                <button type="submit" name="edit_product">Confirm</button>
                <button type="button" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>

        <!-- Description Modal -->
        <div id="description-modal" class="edit-modal" style="display:none; width: 300px;">
            <h3>Product Description</h3>
            <p id="description-content"></p>
            <button type="button" onclick="closeDescriptionModal()">Close</button>
        </div>
    </div>
    <?php
    ob_end_flush();
    $conn->close();
    ?>
    <script>
        let dropZone = document.getElementById('dropZone');
        let fileInput = document.getElementById('fileInput');
        let preview = document.getElementById('preview');
        let editDropZone = document.getElementById('editDropZone');
        let editFileInput = document.getElementById('editFileInput');
        let editPreview = document.getElementById('editPreview');

        // Handle dragover for visual feedback only
        document.body.addEventListener('dragover', (e) => {
            e.preventDefault();
            if (!e.target.classList.contains('drop-zone')) {
                dropZone.classList.add('dragover');
                if (document.getElementById('edit-modal').style.display === 'block') {
                    editDropZone.classList.add('dragover');
                }
            }
        });

        document.body.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            editDropZone.classList.remove('dragover');
        });

        // Handle page-level drop only if not in drop zone
        document.body.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            editDropZone.classList.remove('dragover');
            // Only process if drop is outside specific drop zones
            if (!e.target.closest('.drop-zone')) {
                if (document.getElementById('edit-modal').style.display === 'block') {
                    editFileInput.files = e.dataTransfer.files;
                    previewImages(e.dataTransfer.files, editPreview);
                } else {
                    fileInput.files = e.dataTransfer.files;
                    previewImages(e.dataTransfer.files, preview);
                }
            }
        });

        // Add New Product Drop Zone
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent event from bubbling to document.body
            dropZone.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            previewImages(e.dataTransfer.files, preview);
        });

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => previewImages(fileInput.files, preview));

        // Edit Product Drop Zone
        editDropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            editDropZone.classList.add('dragover');
        });

        editDropZone.addEventListener('dragleave', () => {
            editDropZone.classList.remove('dragover');
        });

        editDropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent event from bubbling to document.body
            editDropZone.classList.remove('dragover');
            editFileInput.files = e.dataTransfer.files;
            previewImages(e.dataTransfer.files, editPreview);
        });

        editDropZone.addEventListener('click', () => editFileInput.click());

        editFileInput.addEventListener('change', () => previewImages(editFileInput.files, editPreview));

        function previewImages(files, previewDiv) {
            previewDiv.innerHTML = '';
            for (let file of files) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let imgContainer = document.createElement('div');
                    imgContainer.style.position = 'relative';
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    let removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '';
                    removeBtn.onclick = function() {
                        imgContainer.remove();
                        updateFileInput(previewDiv === preview ? fileInput : editFileInput, files);
                    };
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    previewDiv.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            }
        }

        function updateFileInput(input, files) {
            let dataTransfer = new DataTransfer();
            let remainingFiles = Array.from(input.files).filter(f => {
                let imgContainers = input === fileInput ? preview.getElementsByTagName('img') : editPreview.getElementsByTagName('img');
                return Array.from(imgContainers).some(img => img.src.includes(f.name));
            });
            remainingFiles.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        function openEditModal(id, name, price, category, subcategory, special, images, description) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-price').value = price;
            document.getElementById('edit-category').value = category;
            document.getElementById('edit-subcategory').value = subcategory;
            document.getElementById('edit-special').checked = special;
            document.getElementById('edit-existing-images').value = images;
            document.getElementById('edit-description').value = description;
            document.getElementById('editPreview').innerHTML = '';
            if (images) {
                images.split(',').forEach(img => {
                    if (img) {
                        let imgContainer = document.createElement('div');
                        imgContainer.style.position = 'relative';
                        let imgElement = document.createElement('img');
                        imgElement.src = img;
                        imgElement.style.maxWidth = '50px';
                        imgElement.style.maxHeight = '50px';
                        let removeBtn = document.createElement('button');
                        removeBtn.className = 'remove-btn';
                        removeBtn.innerHTML = '';
                        removeBtn.onclick = function() {
                            imgContainer.remove();
                            updateFileInput(editFileInput, images.split(',').map(() => ({name: img.split('/').pop()})));
                        };
                        imgContainer.appendChild(imgElement);
                        imgContainer.appendChild(removeBtn);
                        document.getElementById('editPreview').appendChild(imgContainer);
                    }
                });
            }
            document.getElementById('edit-modal').style.display = 'block';
            document.getElementById('modal-overlay').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
            document.getElementById('modal-overlay').style.display = 'none';
        }

        function confirmDelete(form) {
            return confirm('Are you sure you want to delete this product?');
        }

        function showDescription(description) {
            document.getElementById('description-content').textContent = description;
            document.getElementById('description-modal').style.display = 'block';
        }

        function closeDescriptionModal() {
            document.getElementById('description-modal').style.display = 'none';
        }
    </script>
</body>
</html>