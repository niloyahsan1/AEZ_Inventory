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
                <span class="badge-count">{{ $allProductsSum }}</span>
            </div>
            <div class="total-products-badge total-buying-price-badge">
                <span>Total Buying Price</span>
                <span class="badge-count">৳{{ number_format($totalBuyingPriceSum, 2) }}</span>
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

    <!-- Global Search Input (Searches across all folders) -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
        <div class="search-box" style="width: 320px;">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="global-search" placeholder="Search product folder or size..." onkeyup="performGlobalSearch(this)">
        </div>
    </div>

    <!-- Breadcrumb Explorer Trail -->
    <div id="breadcrumb-navigation" style="margin-bottom: 20px; font-size: 16px; font-weight: 500; color: #5c503b; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        <a href="/" style="color: #122b49; text-decoration: none; display: flex; align-items: center; gap: 4px; font-weight: 600;">
            <svg style="width: 18px; height: 18px; color: #ad915a;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Home
        </a>
        @foreach($breadcrumbs as $crumb)
            <span style="color: #dcd7ca;">/</span>
            <a href="/?category_id={{ $crumb->id }}" style="color: #122b49; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                <svg style="width: 16px; height: 16px; color: #bfa36b;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                {{ $crumb->name }}
            </a>
        @endforeach
    </div>

    <!-- Folder Grid Header -->
    <div id="folder-title" style="margin-bottom: 12px;">
        <h2 style="margin: 0; color: #122b49; font-size: 20px; font-weight: 700;">Folders (Categories)</h2>
    </div>
    
    <!-- Folder Grid -->
    <div class="folder-grid">
        @foreach($categories as $category)
            <div class="folder-card" id="folder-card-{{ $category->id }}" onclick="window.location.href='/?category_id={{ $category->id }}'">
                <svg class="folder-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" clip-rule="evenodd"></path></svg>
                <div class="folder-name">{{ $category->name }}</div>
                <div class="folder-meta">{{ $category->totalProductsQuantity() }} pcs</div>
                
                <div class="folder-actions">
                    <button type="button" class="btn-edit" style="padding: 4px 6px; font-size: 11px; border-radius: 4px; width: auto;" onclick="event.stopPropagation(); openEditCategoryModal({{ $category->id }}, '{{ addslashes($category->name) }}')">Rename</button>
                    <button type="button" class="btn-delete" style="padding: 4px 6px; font-size: 11px; border-radius: 4px; width: auto;" onclick="event.stopPropagation(); openDeleteCategoryModal({{ $category->id }}, '{{ addslashes($category->name) }}')">Delete</button>
                </div>
            </div>
        @endforeach

        <!-- Add Category Card -->
        <div class="folder-card add-folder-btn" onclick="openAddCategoryModal()" style="border: 2px dashed #bfa36b; background: transparent; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 140px; box-sizing: border-box; padding: 20px;">
            <svg style="width: 44px; height: 44px; color: #bfa36b; margin-bottom: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span style="font-weight: 600; color: #bfa36b; font-size: 15px;">New Category</span>
        </div>
    </div>

    <!-- Active Category Products Explorer -->
    @if($currentCategory)
        <div class="card active-category-container" id="category-container-{{ $currentCategory->id }}">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f5f1e6;">
                <h3 style="margin: 0; color: #122b49; display: flex; align-items: center; gap: 10px;">
                    <svg style="width: 24px; height: 24px; color: #bfa36b;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" clip-rule="evenodd"></path></svg>
                    <span>Folder: {{ $currentCategory->name }}</span>
                    <span id="active-category-badge" style="font-size: 14px; font-weight: 600; color: #bfa36b; background: #f5f1e6; padding: 2px 10px; border-radius: 12px; border: 1px solid #e5e0d4;">
                        <span id="active-category-direct-pcs">{{ $currentCategory->products->sum('quantity') }}</span> pcs ({{ $currentCategory->products->count() }} items)
                    </span>
                </h3>
                
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <button class="btn-primary" style="display: flex; align-items: center; gap: 6px; padding: 8px 16px;" onclick="openAddProductModal({{ $currentCategory->id }}, '{{ addslashes($currentCategory->name) }}')">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Product
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="product-table" id="product-table-{{ $currentCategory->id }}">
                    <thead>
                        <tr>
                            <th style="width: 60px;">SL</th>
                            <th>Image</th>
                            <th>Size</th>
                            <th>Buying Price (BDT)</th>
                            <th>Selling Price (BDT)</th>
                            <th style="width: 180px;">Qty</th>
                            <th>Rack No</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->isEmpty())
                            <tr>
                                <td colspan="8" style="text-align: center; color: #5c503b; font-style: italic; padding: 30px;">No products in this category folder. Click 'Add Product' to add one.</td>
                            </tr>
                        @else
                            @foreach($products as $index => $product)
                            <tr>
                                <td style="font-weight: 600; color: #000000;">{{ $index + 1 }}</td>
                                <td>
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $currentCategory->name }}" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e0d4; display: block; margin: 0 auto;" class="product-thumbnail" onclick="openLightbox('{{ asset('storage/' . $product->image_path) }}')">
                                    @else
                                        <div style="width: 52px; height: 52px; background: #f5f1e6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ad915a; font-size: 11px; font-weight: 500; border: 1px dashed #e5e0d4; margin: 0 auto;">No Image</div>
                                    @endif
                                </td>
                                <td style="font-weight: 600; color: #000000;">{{ $product->size ?: 'N/A' }}</td>
                                <td style="font-weight: 600; color: #000000;">৳{{ number_format($product->buying_price, 2) }}</td>
                                <td style="font-weight: 600; color: #000000;">৳{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    <div class="table-qty-stepper" style="display: inline-flex; align-items: center; border: 1px solid #dcd7ca; border-radius: 6px; overflow: hidden; background: #faf8f3; vertical-align: middle;">
                                        <button type="button" class="table-stepper-btn minus" onclick="adjustTableQuantity({{ $product->id }}, -1)" style="width: 32px; height: 32px; border: none; background: #f5f1e6; color: #000000; font-size: 16px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; outline: none; transition: background 0.2s; padding: 0;">&minus;</button>
                                        <span class="qty-val-{{ $product->id }}" style="min-width: 44px; text-align: center; font-weight: 600; color: #000000; font-size: 15px; padding: 0 4px;">{{ $product->quantity }}</span>
                                        <button type="button" class="table-stepper-btn plus" onclick="adjustTableQuantity({{ $product->id }}, 1)" style="width: 32px; height: 32px; border: none; background: #f5f1e6; color: #000000; font-size: 16px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; outline: none; transition: background 0.2s; padding: 0;">&plus;</button>
                                    </div>
                                </td>
                                <td style="font-weight: 600; color: #000000;">{{ $product->rack_no ?: 'N/A' }}</td>
                                <td>
                                    <button class="btn-edit" onclick="openEditModal({{ $product->id }}, {{ $product->buying_price }}, {{ $product->selling_price }}, {{ $product->quantity }}, {{ $product->category_id }}, '{{ $product->image_path }}', '{{ addslashes($product->size) }}', '{{ addslashes($product->rack_no) }}')">Edit</button>
                                    <button class="btn-delete" onclick="openDeleteModal({{ $product->id }})">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($categories->isEmpty())
        <!-- Nothing at Root Explorer -->
        <div id="welcome-prompt" class="card" style="text-align: center; padding: 40px; border: 1px dashed #e5e0d4; border-radius: 12px; background: white;">
            <svg style="width: 64px; height: 64px; color: #bfa36b; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 style="margin: 0 0 8px 0; color: #122b49; font-size: 18px;">Welcome to Tonny Cloth Store Explorer</h3>
            <p style="margin: 0 0 20px 0; color: #5c503b; font-size: 15px;">Create a folder category (like "থ্রি পিছ") to begin adding items.</p>
            <button class="btn-primary" onclick="openAddCategoryModal()">+ Add Category Folder</button>
        </div>
    @else
        <!-- Welcome Prompt to open a folder -->
        <div id="welcome-prompt" style="text-align: center; padding: 40px; border: 1px dashed #e5e0d4; border-radius: 12px; background: #faf8f3;">
            <svg style="width: 48px; height: 48px; color: #ad915a; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5M5 19v-4m14 4v-5m0 0l-5-5m5 5H9"></path></svg>
            <h4 style="margin: 0; color: #122b49; font-size: 16px; font-weight: 600;">Double-click or Tap a folder category above to open its contents and manage products.</h4>
        </div>
    @endif

    <!-- Global Search Results Container (Hidden by default) -->
    <div class="card" id="global-search-results" style="display: none;">
        <h3 style="margin-top: 0; margin-bottom: 20px; color: #122b49; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 20px; height: 20px; color: #bfa36b;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <span>Search Results</span>
        </h3>
        <div class="table-responsive">
            <table class="product-table" id="global-search-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">SL</th>
                        <th>Folder (Category)</th>
                        <th>Size</th>
                        <th>Buying Price (BDT)</th>
                        <th>Selling Price (BDT)</th>
                        <th style="width: 180px;">Qty</th>
                        <th>Rack No</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allProducts as $index => $product)
                    <tr data-category-name="{{ strtolower($product->category->name) }}" data-size="{{ strtolower($product->size) }}" data-rack-no="{{ strtolower($product->rack_no) }}">
                        <td style="font-weight: 600; color: #000000;">{{ $index + 1 }}</td>
                        <td style="font-weight: 600; color: #000000;">
                            <a href="/?category_id={{ $product->category_id }}" style="color: #000000; text-decoration: underline; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <svg style="width: 14px; height: 14px; color: #bfa36b;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" clip-rule="evenodd"></path></svg>
                                {{ $product->category->name }}
                            </a>
                        </td>
                        <td style="font-weight: 600; color: #000000;">{{ $product->size ?: 'N/A' }}</td>
                        <td style="font-weight: 600; color: #000000;">৳{{ number_format($product->buying_price, 2) }}</td>
                        <td style="font-weight: 600; color: #000000;">৳{{ number_format($product->selling_price, 2) }}</td>
                        <td>
                            <div class="table-qty-stepper" style="display: inline-flex; align-items: center; border: 1px solid #dcd7ca; border-radius: 6px; overflow: hidden; background: #faf8f3; vertical-align: middle;">
                                <button type="button" class="table-stepper-btn minus" onclick="adjustTableQuantity({{ $product->id }}, -1)" style="width: 32px; height: 32px; border: none; background: #f5f1e6; color: #000000; font-size: 16px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; outline: none; transition: background 0.2s; padding: 0;">&minus;</button>
                                <span class="qty-val-{{ $product->id }}" style="min-width: 44px; text-align: center; font-weight: 600; color: #000000; font-size: 15px; padding: 0 4px;">{{ $product->quantity }}</span>
                                <button type="button" class="table-stepper-btn plus" onclick="adjustTableQuantity({{ $product->id }}, 1)" style="width: 32px; height: 32px; border: none; background: #f5f1e6; color: #000000; font-size: 16px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; outline: none; transition: background 0.2s; padding: 0;">&plus;</button>
                            </div>
                        </td>
                        <td style="font-weight: 600; color: #000000;">{{ $product->rack_no ?: 'N/A' }}</td>
                        <td>
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->category->name }}" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e0d4; display: block; margin: 0 auto;" class="product-thumbnail" onclick="openLightbox('{{ asset('storage/' . $product->image_path) }}')">
                            @else
                                <div style="width: 52px; height: 52px; background: #f5f1e6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ad915a; font-size: 11px; font-weight: 500; border: 1px dashed #e5e0d4; margin: 0 auto;">No Image</div>
                            @endif
                        </td>
                        <td>
                            <button class="btn-edit" onclick="openEditModal({{ $product->id }}, {{ $product->buying_price }}, {{ $product->selling_price }}, {{ $product->quantity }}, {{ $product->category_id }}, '{{ $product->image_path }}', '{{ addslashes($product->size) }}', '{{ addslashes($product->rack_no) }}')">Edit</button>
                            <button class="btn-delete" onclick="openDeleteModal({{ $product->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Overlay & Modal Box -->
<div class="modal-overlay" id="modalOverlay"></div>

<!-- Add Category Modal -->
<div id="addCategoryModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(18, 43, 73, 0.1), 0 10px 10px -5px rgba(18, 43, 73, 0.04); z-index: 1000; width: 400px; max-width: 90%; border: 1px solid #e5e0d4;">
    <h3 style="margin-top: 0; margin-bottom: 20px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Add New Category (Folder)</h3>
    <form method="POST" action="/categories">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $currentCategory ? $currentCategory->id : '' }}">
        
        <div class="form-group">
            <label for="newCategoryName">Category Name</label>
            <input type="text" name="name" id="newCategoryName" placeholder="e.g. Tshirt" required>
        </div>
        <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn-delete" style="background-color: #f5f1e6; color: #122b49;" onclick="closeAddCategoryModal()">Cancel</button>
            <button type="submit" class="btn-primary">Add Category</button>
        </div>
    </form>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(18, 43, 73, 0.1), 0 10px 10px -5px rgba(18, 43, 73, 0.04); z-index: 1000; width: 400px; max-width: 90%; border: 1px solid #e5e0d4;">
    <h3 style="margin-top: 0; margin-bottom: 20px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Rename Category Folder</h3>
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
    <h3 style="margin-top: 0; margin-bottom: 16px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Confirm Delete Folder</h3>
    <p style="text-align: center; color: #2e3e52; font-size: 15px; margin-bottom: 24px;">Are you sure you want to delete category folder <strong id="deleteCategoryName" style="color: #122b49;"></strong>?<br><span style="color: #ef4444; font-size: 13px; font-weight: 600;">Warning: All subfolders and products inside will also be deleted!</span></p>
    <form id="deleteCategoryForm" method="POST" style="margin: 0;">
        @csrf
        @method('DELETE')
        <div class="modal-actions" style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" class="btn-edit" style="background-color: #f5f1e6; color: #122b49; margin: 0;" onclick="closeDeleteCategoryModal()">Cancel</button>
            <button type="submit" class="btn-delete" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; margin: 0;">Delete Category</button>
        </div>
    </form>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(18, 43, 73, 0.1), 0 10px 10px -5px rgba(18, 43, 73, 0.04); z-index: 1000; width: 450px; max-width: 90%; border: 1px solid #e5e0d4;">
    <h3 style="margin-top: 0; margin-bottom: 20px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Add Product to Folder</h3>
    <form method="POST" action="/products" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="category_id" id="addProductCategoryId">
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label style="display: block; font-size: 15px; font-weight: 500; color: #5c503b; margin-bottom: 6px;">Folder Name</label>
            <input type="text" id="addProductCategoryNameDisplay" disabled style="width: 100%; box-sizing: border-box; padding: 10px 14px; background: #e5e0d4; border-radius: 8px; font-weight: 600; color: #122b49; border: 1px solid #dcd7ca; font-size: 16px;">
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label for="addSize" style="display: block; font-size: 15px; font-weight: 500; color: #5c503b; margin-bottom: 6px;">Size (Optional)</label>
            <input type="text" name="size" placeholder="e.g. XL, L, 38, 40">
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label for="addRackNo" style="display: block; font-size: 15px; font-weight: 500; color: #5c503b; margin-bottom: 6px;">Rack No (Optional)</label>
            <input type="text" name="rack_no" id="addRackNo" placeholder="e.g. A-1, B-2">
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label for="addBuyingPrice" style="display: block; font-size: 15px; font-weight: 500; color: #5c503b; margin-bottom: 6px;">Buying Price (BDT)</label>
            <input type="number" step="any" name="buying_price" placeholder="Buying Price (BDT)" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label for="addSellingPrice" style="display: block; font-size: 15px; font-weight: 500; color: #5c503b; margin-bottom: 6px;">Selling Price (BDT)</label>
            <input type="number" step="any" name="selling_price" placeholder="Selling Price (BDT)" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label for="addQuantity" style="display: block; font-size: 15px; font-weight: 500; color: #5c503b; margin-bottom: 6px;">Quantity</label>
            <input type="number" name="quantity" placeholder="Quantity" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label style="display: block; font-size: 15px; font-weight: 500; color: #5c503b; margin-bottom: 6px;">Add Photo (Optional)</label>
            <label class="custom-file-upload">
                <input type="file" name="image" accept="image/*" onchange="updateAddFileName(this)">
                <span class="file-upload-text">
                    <svg style="width: 16px; height: 16px; margin-right: 4px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span id="add-file-upload-name">Add Photo</span>
                </span>
            </label>
        </div>
        <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn-delete" style="background-color: #f5f1e6; color: #122b49;" onclick="closeAddProductModal()">Cancel</button>
            <button type="submit" class="btn-primary">Add Product</button>
        </div>
    </form>
</div>

<!-- Edit Product Modal (Allows moving to other folder) -->
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
            <label for="editCategory">Move to Category Folder</label>
            <select name="category_id" id="editCategory" required>
                @foreach($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="editSize">Size (Optional)</label>
            <input type="text" name="size" id="editSize" placeholder="e.g. XL, L, 38, 40">
        </div>

        <div class="form-group">
            <label for="editRackNo">Rack No (Optional)</label>
            <input type="text" name="rack_no" id="editRackNo" placeholder="e.g. A-1, B-2">
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
            <div class="quantity-stepper" style="display: flex; align-items: center; max-width: 180px; border: 1px solid #dcd7ca; border-radius: 8px; overflow: hidden; background: #faf8f3;">
                <button type="button" class="stepper-btn minus" onclick="decrementQuantity()" style="width: 46px; height: 46px; border: none; background: #f5f1e6; color: #122b49; font-size: 20px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; outline: none;">&minus;</button>
                <input type="number" name="quantity" id="editQuantity" required style="flex: 1; border: none; text-align: center; font-size: 16px; font-weight: 600; color: #122b49; background: transparent; height: 46px; width: 100%; margin: 0; padding: 0; -moz-appearance: textfield; outline: none;" min="0">
                <button type="button" class="stepper-btn plus" onclick="incrementQuantity()" style="width: 46px; height: 46px; border: none; background: #f5f1e6; color: #122b49; font-size: 20px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; outline: none;">&plus;</button>
            </div>
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
    <h3 style="margin-top: 0; margin-bottom: 16px; color: #122b49; font-size: 20px; font-weight: 600; text-align: center;">Confirm Delete Product</h3>
    <p style="text-align: center; color: #2e3e52; font-size: 14px; margin-bottom: 24px;">Are you sure you want to delete this product?</p>
    
    <form id="deleteForm" method="POST" style="margin: 0;">
        @csrf
        @method('DELETE')
        <div class="modal-actions" style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" class="btn-edit" style="background-color: #f5f1e6; color: #122b49; margin: 0;" onclick="closeDeleteModal()">Cancel</button>
            <button type="submit" class="btn-delete" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; margin: 0;">Delete Product</button>
        </div>
    </form>
</div>

<!-- Lightbox Modal -->
<div id="imageLightbox" class="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img class="lightbox-content" id="lightboxImage">
</div>

<script>
// AJAX dynamic quantity updates inside tables
function adjustTableQuantity(productId, delta) {
    const qtyElements = document.querySelectorAll(`.qty-val-${productId}`);
    if (qtyElements.length === 0) return;
    
    let currentQty = parseInt(qtyElements[0].innerText) || 0;
    let newQty = currentQty + delta;
    if (newQty < 0) return;

    fetch(`/products/${productId}/quantity`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: newQty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update quantity display for all instances of this product on the screen
            document.querySelectorAll(`.qty-val-${productId}`).forEach(elem => {
                elem.innerText = data.quantity;
            });
            
            // Update global header total count badge
            const totalBadge = document.querySelector(".total-products-badge .badge-count");
            if (totalBadge) totalBadge.innerText = data.total_quantity;
 
            // Update global header total buying price badge
            const totalBuyingPriceBadgeCount = document.querySelector(".total-buying-price-badge .badge-count");
            if (totalBuyingPriceBadgeCount) {
                totalBuyingPriceBadgeCount.innerText = '৳' + parseFloat(data.total_buying_price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }

            // Update active category folder header direct pcs count badge
            const activeDirectBadge = document.getElementById("active-category-direct-pcs");
            if (activeDirectBadge) {
                activeDirectBadge.innerText = data.active_category_direct_sum;
            }

            // Update all folder card meta values recursively
            for (const [catId, qty] of Object.entries(data.categories)) {
                const metaElem = document.querySelector(`#folder-card-${catId} .folder-meta`);
                if (metaElem) {
                    metaElem.innerText = qty + " pcs";
                }
            }
        }
    })
    .catch(error => {
        console.error("Error updating quantity:", error);
    });
}

// Global Search handler
function performGlobalSearch(inputElement) {
    let query = inputElement.value.toLowerCase().trim();
    
    const folderGrid = document.querySelector(".folder-grid");
    const folderTitle = document.getElementById("folder-title");
    const activeContainer = document.querySelector(".active-category-container");
    const welcomePrompt = document.getElementById("welcome-prompt");
    const breadcrumbNav = document.getElementById("breadcrumb-navigation");
    const searchResults = document.getElementById("global-search-results");
    
    if (query === "") {
        // Restore standard view
        if (folderGrid) folderGrid.style.display = "grid";
        if (folderTitle) folderTitle.style.display = "block";
        if (activeContainer) activeContainer.style.display = "block";
        if (welcomePrompt) welcomePrompt.style.display = "block";
        if (breadcrumbNav) breadcrumbNav.style.display = "flex";
        if (searchResults) searchResults.style.display = "none";
    } else {
        // Hide standard folders/breadcrumbs
        if (folderGrid) folderGrid.style.display = "none";
        if (folderTitle) folderTitle.style.display = "none";
        if (activeContainer) activeContainer.style.display = "none";
        if (welcomePrompt) welcomePrompt.style.display = "none";
        if (breadcrumbNav) breadcrumbNav.style.display = "none";
        if (searchResults) searchResults.style.display = "block";
        
        // Filter rows
        let rows = document.querySelectorAll("#global-search-table tbody tr:not(#global-search-empty)");
        let matchCount = 0;
        rows.forEach(row => {
            let catName = row.getAttribute("data-category-name");
            let size = row.getAttribute("data-size");
            let rackNo = row.getAttribute("data-rack-no") || '';
            if (catName.includes(query) || size.includes(query) || rackNo.includes(query)) {
                row.style.display = "";
                matchCount++;
            } else {
                row.style.display = "none";
            }
        });
        
        // Manage empty message
        let emptyRow = document.getElementById("global-search-empty");
        if (!emptyRow) {
            emptyRow = document.createElement("tr");
            emptyRow.id = "global-search-empty";
            emptyRow.innerHTML = `<td colspan="8" style="text-align: center; color: #5c503b; font-style: italic; padding: 30px;">No matching products found.</td>`;
            document.querySelector("#global-search-table tbody").appendChild(emptyRow);
        }
        emptyRow.style.display = (matchCount === 0) ? "" : "none";
    }
}

function updateAddFileName(input) {
    const label = document.getElementById("add-file-upload-name");
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

function openAddCategoryModal() {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("addCategoryModal").style.display = "block";
}

function closeAddCategoryModal() {
    document.getElementById("modalOverlay").style.display = "none";
    document.getElementById("addCategoryModal").style.display = "none";
}

function openEditCategoryModal(id, name) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("editCategoryModal").style.display = "block";
    document.getElementById("editCategoryName").value = name;
    document.getElementById("editCategoryForm").action = `/categories/${id}`;
}

function closeEditCategoryModal() {
    document.getElementById("modalOverlay").style.display = "none";
    document.getElementById("editCategoryModal").style.display = "none";
}

function openDeleteCategoryModal(id, name) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("deleteCategoryModal").style.display = "block";
    document.getElementById("deleteCategoryName").innerText = name;
    document.getElementById("deleteCategoryForm").action = `/categories/${id}`;
}

function closeDeleteCategoryModal() {
    document.getElementById("modalOverlay").style.display = "none";
    document.getElementById("deleteCategoryModal").style.display = "none";
}

function openAddProductModal(categoryId, categoryName) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("addProductModal").style.display = "block";
    document.getElementById("addProductCategoryId").value = categoryId;
    document.getElementById("addProductCategoryNameDisplay").value = categoryName;
    document.getElementById("add-file-upload-name").innerText = "Add Photo";
}

function closeAddProductModal() {
    document.getElementById("modalOverlay").style.display = "none";
    document.getElementById("addProductModal").style.display = "none";
}

function openEditModal(id, buyingPrice, sellingPrice, quantity, categoryId, imagePath, size, rackNo) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("editModal").style.display = "block";
    document.getElementById("editBuyingPrice").value = buyingPrice;
    document.getElementById("editSellingPrice").value = sellingPrice;
    document.getElementById("editQuantity").value = quantity;
    document.getElementById("editCategory").value = categoryId;
    document.getElementById("editSize").value = size || '';
    document.getElementById("editRackNo").value = rackNo || '';
    document.getElementById("editForm").action = `/products/${id}`;
    
    document.getElementById("editImage").value = "";
    document.getElementById("edit-file-upload-name").innerText = "Change Photo";
    const preview = document.getElementById("editImagePreview");
    const noImageText = document.getElementById("editNoImageText");
    if (imagePath) {
        preview.src = `/storage/${imagePath}`;
        preview.style.display = "block";
        noImageText.style.display = "none";
    } else {
        preview.src = "";
        preview.style.display = "none";
        noImageText.style.display = "block";
    }
}

function closeEditModal() {
    closeAllModals();
}

// Ensure active folder remains visually highlighted on load
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const activeId = params.get('category_id');
    if (activeId) {
        const card = document.getElementById(`folder-card-${activeId}`);
        if (card) {
            card.classList.add('active');
        }
    }
});

function openDeleteModal(id) {
    document.getElementById("modalOverlay").style.display = "block";
    document.getElementById("deleteModal").style.display = "block";
    document.getElementById("deleteForm").action = `/products/${id}`;
}

// Function to filter products inside local active tables (fallback/local filtering)
function filterTable(inputElement) {
    let input = inputElement.value.toLowerCase();
    let categoryId = inputElement.getAttribute("data-category-id");
    let rows = document.querySelectorAll(`#product-table-${categoryId} tbody tr`);
    
    rows.forEach(row => {
        if(row.cells.length > 1) {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        }
    });
}

function closeDeleteModal() {
    closeAllModals();
}

function closeAllModals() {
    document.getElementById("modalOverlay").style.display = "none";
    document.getElementById("editModal").style.display = "none";
    
    const addCatModal = document.getElementById("addCategoryModal");
    if (addCatModal) addCatModal.style.display = "none";

    const editCatModal = document.getElementById("editCategoryModal");
    if (editCatModal) editCatModal.style.display = "none";

    const deleteCatModal = document.getElementById("deleteCategoryModal");
    if (deleteCatModal) deleteCatModal.style.display = "none";

    const addProdModal = document.getElementById("addProductModal");
    if (addProdModal) addProdModal.style.display = "none";

    const deleteProdModal = document.getElementById("deleteModal");
    if (deleteProdModal) deleteProdModal.style.display = "none";
}

// Overlay click closes modals
document.getElementById("modalOverlay").addEventListener("click", closeAllModals);

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

function decrementQuantity() {
    const input = document.getElementById("editQuantity");
    let val = parseInt(input.value) || 0;
    if (val > 0) {
        input.value = val - 1;
    }
}

function incrementQuantity() {
    const input = document.getElementById("editQuantity");
    let val = parseInt(input.value) || 0;
    input.value = val + 1;
}
</script>
</body>
</html>
