<!DOCTYPE html>
<html>
<head>
    <title>AEZ Inventory</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('icon.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
<h1>Ahsan Electro Zone</h1>
<h2>Inventory</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if($errors->any())
    <ul style="color: red;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
    <form method="POST" action="/products">
        @csrf
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" step="10" name="buying_price" placeholder="Buying Price" required>
        <input type="number" step="10" name="selling_price" placeholder="Selling Price" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <button type="submit">Add Product</button>
    </form>

    <div class="total-products">
        Total Products: {{ count($products) }}
    </div>
</div>

<input type="text" id="search" placeholder="Search..." onkeyup="filterTable()">

<table id="productTable">
    <tr>
        <th>Name</th><th>Buying Price</th><th>Selling Price</th><th>Qty</th><th>Actions</th>
    </tr>
    @foreach($products as $product)
    <tr>
        <td>{{ e($product->name) }}</td>
        <td>{{ $product->buying_price }}</td>
        <td>{{ $product->selling_price }}</td>
        <td>{{ $product->quantity }}</td>
        <td>
            <button onclick='openEditModal(@json($product))'>Edit</button>
            <form method="POST" action="/products/{{ $product->id }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<div id="editModal">
    <h3>Edit Product</h3>
    <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" id="editName" required><br>
        <input type="number" step="0.01" name="buying_price" id="editBuyingPrice" required><br>
        <input type="number" step="0.01" name="selling_price" id="editSellingPrice" required><br>
        <input type="number" name="quantity" id="editQuantity" required><br>
        <button type="submit">Update</button>
        <button type="button" onclick="closeEditModal()">Cancel</button>
    </form>
</div>

<script>
function filterTable() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("#productTable tr:not(:first-child)");
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
    });
}

function openEditModal(product) {
    document.getElementById("editModal").style.display = "block";
    document.getElementById("editName").value = product.name;
    document.getElementById("editBuyingPrice").value = product.buying_price;
    document.getElementById("editSellingPrice").value = product.selling_price;
    document.getElementById("editQuantity").value = product.quantity;
    document.getElementById("editForm").action = `/products/${product.id}`;
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>
</body>
</html>
