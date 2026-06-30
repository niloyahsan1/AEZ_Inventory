<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tonny Cloth Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

<div class="toast-container">
    @if(session('success'))
        <div class="toast success show" id="successToast">
            <div style="display: flex; align-items: center; gap: 10px;">
                <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button class="toast-close" onclick="closeToast('successToast')">&times;</button>
            <div class="toast-progress"><div class="toast-progress-bar"></div></div>
        </div>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $index => $error)
            <div class="toast error show" id="errorToast-{{ $index }}">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ $error }}</span>
                </div>
                <button class="toast-close" onclick="closeToast('errorToast-{{ $index }}')">&times;</button>
                <div class="toast-progress"><div class="toast-progress-bar"></div></div>
            </div>
        @endforeach
    @endif
</div>

<div class="app-container">
    <!-- Header -->
    <header class="app-header">
        <div class="header-title-group" style="display: flex; align-items: center; gap: 15px;">
            <img src="{{ asset('logo.jpg') }}" alt="Tonny Cloth Store Logo" style="height: 60px; border-radius: 8px; border: 1px solid #e5e0d4;">
            <div>
                <h1 style="margin: 0;">Tonny Cloth Store</h1>
                <p class="subtitle" style="margin: 4px 0 0 0;">Inventory Dashboard</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div class="total-products-badge">
                <span>Total Product Count</span>
                <span class="badge-count">{{ $products->sum('quantity') }}</span>
            </div>
            @auth
            <div style="display: flex; align-items: center; gap: 8px; background: #ffffff; padding: 8px 16px; border-radius: 20px; border: 1px solid #e5e0d4; font-size: 14px; font-weight: 600; color: #122b49; box-shadow: 0 1px 3px rgba(18, 43, 73, 0.02);">
                <svg style="width: 16px; height: 16px; color: #ad915a; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>{{ auth()->user()->name }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: inline;">
                @csrf
                <button type="submit" class="btn-delete" style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
            @endauth
        </div>
    </header>

    <!-- Side-by-side forms grid -->
    <div class="forms-grid">
        <!-- Card: Add Product -->
        <div class="card">
            <h3>Add New Product</h3>
            <form method="POST" action="/products" enctype="multipart/form-data" class="form-grid">
                @csrf
                <div class="form-group">
                    <input type="text" name="name" placeholder="Product Name" required>
                </div>
                <div class="form-group">
                    <select name="category_id" required>
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input type="number" step="any" name="buying_price" placeholder="Buying Price (BDT)" required>
                </div>
                <div class="form-group">
                    <input type="number" step="any" name="selling_price" placeholder="Selling Price (BDT)" required>
                </div>
                <div class="form-group">
                    <input type="number" name="quantity" placeholder="Quantity" required>
                </div>
                <div class="form-group file-group">
                    <label class="custom-file-upload">
                        <input type="file" name="image" accept="image/*" id="productImageInput" onchange="updateFileName(this)">
                        <span class="file-upload-text">
                            <svg style="width: 16px; height: 16px; margin-right: 4px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span id="file-upload-name">Add Photo</span>
                        </span>
                    </label>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Add Product</button>
            </form>
        </div>

        <!-- Card: Manage Categories -->
        <div class="card">
            <h3>Manage Categories</h3>
            <form method="POST" action="/categories" class="form-grid" style="margin-bottom: 16px;">
                @csrf
                <div class="form-group" style="flex: 2; min-width: 140px;">
                    <input type="text" name="name" placeholder="Category Name (e.g. Tshirt)" required>
                </div>
                <button type="submit" class="btn-primary" style="flex: 1;">Add</button>
            </form>
            
            <div style="max-height: 180px; overflow-y: auto; border: 1px solid #e5e0d4; border-radius: 8px; padding: 10px; background: #faf8f3;">
                @if($categories->isEmpty())
                    <span style="font-size: 14px; color: #5c503b; font-style: italic;">No categories created.</span>
                @else
                    @foreach($categories as $category)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid #e5e0d4; gap: 10px;">
                            <span style="font-size: 15px; font-weight: 600; color: #122b49;">{{ $category->name }}</span>
                            <div style="display: flex; gap: 4px;">
                                <button type="button" class="btn-edit" style="padding: 4px 8px; font-size: 12px; border-radius: 4px; width: auto;" onclick='openEditCategoryModal(@json($category))'>Edit</button>
                                <button type="button" class="btn-delete" style="padding: 4px 8px; font-size: 12px; border-radius: 4px; width: auto;" onclick='openDeleteCategoryModal(@json($category))'>Delete</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Search Box for Products -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
        <div class="search-box">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="search" placeholder="Search products..." onkeyup="filterTable()">
        </div>
    </div>

    <!-- Card: Inventory List Grouped by Category -->
    @foreach($categories as $category)
    <div class="card category-card" style="padding-bottom: 10px;">
        <div class="table-header-row">
            <h3 style="margin: 0; display: flex; align-items: center; gap: 12px; color: #122b49;">
                <span>{{ $category->name }}</span>
                <span style="font-size: 14px; font-weight: 500; color: #bfa36b; background: #f5f1e6; padding: 2px 10px; border-radius: 12px; border: 1px solid #e5e0d4;">
                    {{ $category->products->sum('quantity') }} pcs ({{ $category->products->count() }} items)
                </span>
            </h3>
        </div>

        <div class="table-responsive">
            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">SL</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Buying Price (BDT)</th>
                        <th>Selling Price (BDT)</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($category->products->isEmpty())
                        <tr>
                            <td colspan="7" style="text-align: center; color: #5c503b; font-style: italic; padding: 20px;">No products in this category.</td>
                        </tr>
                    @else
                        @foreach($category->products as $index => $product)
                        <tr>
                            <td style="font-weight: 600; color: #bfa36b;">{{ $index + 1 }}</td>
                            <td>
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e0d4; display: block;" class="product-thumbnail" onclick="openLightbox('{{ asset('storage/' . $product->image_path) }}')">
                                @else
                                    <div style="width: 52px; height: 52px; background: #f5f1e6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #bfa36b; font-size: 11px; font-weight: 500; border: 1px dashed #e5e0d4;">No Image</div>
                                @endif
                            </td>
                            <td style="font-weight: 600; color: #122b49;">{{ e($product->name) }}</td>
                            <td style="font-weight: 600; color: #122b49;">৳{{ number_format($product->buying_price, 2) }}</td>
                            <td style="font-weight: 600; color: #122b49;">৳{{ number_format($product->selling_price, 2) }}</td>
                            <td>
                                <span style="display: inline-block; padding: 2px 8px; background: #f5f1e6; border-radius: 6px; font-weight: 500; color: #5c503b;">{{ $product->quantity }} pcs</span>
                            </td>
                            <td style="text-align: right;">
                                <button class="btn-edit" onclick='openEditModal(@json($product))'>Edit</button>
                                <button class="btn-delete" onclick='openDeleteModal(@json($product))'>Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal Overlay & Modal Box -->
<div class="modal-overlay" id="modalOverlay" onclick="closeAllModals()"></div>

<div id="editModal">
    <h3>Edit Product Details</h3>
    <form id="editForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="text-align: center; margin-bottom: 20px;">
            <img id="editImagePreview" src="" alt="Preview" style="width: 90px; height: 90px; display: none; margin: 0 auto 10px; border-radius: 8px; border: 1px solid #e5e0d4; object-fit: cover;">
            <div id="editNoImageText" style="display: none; color: #bfa36b; margin-bottom: 10px; font-size: 13px; font-weight: 500; padding: 15px; border: 1px dashed #e5e0d4; border-radius: 8px;">No image uploaded</div>
        </div>

        <div class="form-group">
            <label for="editName">Product Name</label>
            <input type="text" name="name" id="editName" required>
        </div>

        <div class="form-group">
            <label for="editCategory">Category</label>
            <select name="category_id" id="editCategory" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="editBuyingPrice">Buying Price (BDT)</label>
            <input type="number" step="0.01" name="buying_price" id="editBuyingPrice" required>
        </div>

        <div class="form-group">
            <label for="editSellingPrice">Selling Price (BDT)</label>
            <input type="number" step="0.01" name="selling_price" id="editSellingPrice" required>
        </div>

        <div class="form-group">
            <label for="editQuantity">Quantity</label>
            <input type="number" name="quantity" id="editQuantity" required>
        </div>

        <div class="form-group">
            <label>Change Image (Optional)</label>
            <label class="custom-file-upload">
                <input type="file" name="image" id="editImage" accept="image/*" onchange="updateEditFileName(this)">
                <span class="file-upload-text">
                    <svg style="width: 16px; height: 16px; margin-right: 4px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span id="edit-file-upload-name">Change Photo</span>
                </span>
            </label>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-delete" style="background-color: #f5f1e6; color: #122b49;" onclick="closeEditModal()">Cancel</button>
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<!-- Custom Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(18, 43, 73, 0.1), 0 10px 10px -5px rgba(18, 43, 73, 0.04); z-index: 1000; width: 400px; max-width: 90%; border: 1px solid #e5e0d4;">
    <h3 style="margin-top: 0; margin-bottom: 16px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Confirm Delete</h3>
    <p style="text-align: center; color: #2e3e52; font-size: 14px; margin-bottom: 24px;">Are you sure you want to delete <strong id="deleteProductName" style="color: #122b49;"></strong>?</p>
    
    <form id="deleteForm" method="POST" style="margin: 0;">
        @csrf
        @method('DELETE')
        <div class="modal-actions" style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" class="btn-edit" style="background-color: #f5f1e6; color: #122b49; margin: 0;" onclick="closeDeleteModal()">Cancel</button>
            <button type="submit" class="btn-delete" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; margin: 0;">Delete Product</button>
        </div>
    </form>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(18, 43, 73, 0.1), 0 10px 10px -5px rgba(18, 43, 73, 0.04); z-index: 1000; width: 400px; max-width: 90%; border: 1px solid #e5e0d4;">
    <h3 style="margin-top: 0; margin-bottom: 20px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Edit Category</h3>
    <form id="editCategoryForm" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="editCategoryName">Category Name</label>
            <input type="text" name="name" id="editCategoryName" required>
        </div>
        <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn-delete" style="background-color: #f5f1e6; color: #122b49;" onclick="closeEditCategoryModal()">Cancel</button>
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<!-- Delete Category Modal -->
<div id="deleteCategoryModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(18, 43, 73, 0.1), 0 10px 10px -5px rgba(18, 43, 73, 0.04); z-index: 1000; width: 400px; max-width: 90%; border: 1px solid #e5e0d4;">
    <h3 style="margin-top: 0; margin-bottom: 16px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Confirm Delete Category</h3>
    <p style="text-align: center; color: #2e3e52; font-size: 15px; margin-bottom: 24px;">Are you sure you want to delete category <strong id="deleteCategoryName" style="color: #122b49;"></strong>?<br><span style="color: #ef4444; font-size: 13px; font-weight: 600;">Warning: All products in this category will also be deleted!</span></p>
    <form id="deleteCategoryForm" method="POST" style="margin: 0;">
        @csrf
        @method('DELETE')
        <div class="modal-actions" style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" class="btn-edit" style="background-color: #f5f1e6; color: #122b49; margin: 0;" onclick="closeDeleteCategoryModal()">Cancel</button>
            <button type="submit" class="btn-delete" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; margin: 0;">Delete Category</button>
        </div>
    </form>
</div>

<!-- Lightbox Modal -->
<div id="imageLightbox" class="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img class="lightbox-content" id="lightboxImage">
</div>

<script>
function filterTable() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll(".product-table tbody tr");
    rows.forEach(row => {
        if(row.cells.length > 1) {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        }
    });

    document.querySelectorAll(".category-card").forEach(card => {
        let rows = Array.from(card.querySelectorAll(".product-table tbody tr"));
        let hasProducts = rows.length > 0 && !rows[0].innerText.includes("No products");
        if (hasProducts) {
            let visibleRows = rows.filter(r => r.style.display !== 'none');
            card.style.display = (visibleRows.length === 0 && input !== '') ? 'none' : '';
        } else {
            card.style.display = (input !== '') ? 'none' : '';
        }
    });
}

function updateFileName(input) {
    const label = document.getElementById("file-upload-name");
    if (input.files && input.files.length > 0) {
        label.innerText = input.files[0].name;
    } else {
        label.innerText = "Add Photo";
    }
}

function updateEditFileName(input) {
    const label = document.getElementById("edit-file-upload-name");
    if (input.files && input.files.length > 0) {
        label.innerText = input.files[0].name;
    } else {
        label.innerText = "Change Photo";
    }
}

function openEditModal(product) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("editModal").style.display = "block";
    document.getElementById("editName").value = product.name;
    document.getElementById("editBuyingPrice").value = product.buying_price;
    document.getElementById("editSellingPrice").value = product.selling_price;
    document.getElementById("editQuantity").value = product.quantity;
    document.getElementById("editCategory").value = product.category_id;
    document.getElementById("editForm").action = `/products/${product.id}`;
    
    document.getElementById("editImage").value = "";
    document.getElementById("edit-file-upload-name").innerText = "Change Photo";
    const preview = document.getElementById("editImagePreview");
    const noImageText = document.getElementById("editNoImageText");
    if (product.image_path) {
        preview.src = `/storage/${product.image_path}`;
        preview.style.display = "block";
        noImageText.style.display = "none";
    } else {
        preview.src = "";
        preview.style.display = "none";
        noImageText.style.display = "block";
    }
}

function closeAllModals() {
    document.getElementById("modalOverlay").style.display = "none";
    document.getElementById("editModal").style.display = "none";
    
    const deleteModal = document.getElementById("deleteModal");
    if (deleteModal) deleteModal.style.display = "none";

    const editCatModal = document.getElementById("editCategoryModal");
    if (editCatModal) editCatModal.style.display = "none";

    const deleteCatModal = document.getElementById("deleteCategoryModal");
    if (deleteCatModal) deleteCatModal.style.display = "none";
}

// Ensure overlay click closes all modals
document.getElementById("modalOverlay").addEventListener("click", closeAllModals);

function closeEditModal() {
    closeAllModals();
}

function openDeleteModal(product) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("deleteModal").style.display = "block";
    document.getElementById("deleteProductName").innerText = product.name;
    document.getElementById("deleteForm").action = `/products/${product.id}`;
}

function closeDeleteModal() {
    closeAllModals();
}

function openEditCategoryModal(category) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("editCategoryModal").style.display = "block";
    document.getElementById("editCategoryName").value = category.name;
    document.getElementById("editCategoryForm").action = `/categories/${category.id}`;
}

function closeEditCategoryModal() {
    closeAllModals();
}

function openDeleteCategoryModal(category) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("deleteCategoryModal").style.display = "block";
    document.getElementById("deleteCategoryName").innerText = category.name;
    document.getElementById("deleteCategoryForm").action = `/categories/${category.id}`;
}

function closeDeleteCategoryModal() {
    closeAllModals();
}

function closeToast(id) {
    const toast = document.getElementById(id);
    if (toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => {
            toast.remove();
        }, 400);
    }
}

// Auto-close toasts after 8 seconds (8000ms)
document.addEventListener('DOMContentLoaded', () => {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            closeToast(toast.id);
        }, 8000);
    });
});

function openLightbox(src) {
    const lightbox = document.getElementById("imageLightbox");
    const img = document.getElementById("lightboxImage");
    img.src = src;
    lightbox.style.display = "flex";
    setTimeout(() => {
        lightbox.classList.add("show");
    }, 10);
}

function closeLightbox() {
    const lightbox = document.getElementById("imageLightbox");
    lightbox.classList.remove("show");
    setTimeout(() => {
        lightbox.style.display = "none";
    }, 300);
}
</script>
</body>
</html>
