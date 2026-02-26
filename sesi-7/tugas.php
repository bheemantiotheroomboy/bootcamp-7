<DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <h1 class="text-center mt-5">Form Input Product</h1>
                    <!-- Form Input product -->
                    <form id="productForm" action="proses.php" method="post" onsubmit="return validateForm()">
                        <div class="mb-3">
                            <label for="NAME" class="form-label">NAME</label>
                            <input type="text" class="form-control" id="NAME" name="NAME" placeholder="Enter product name" required>
                            <small class="text-danger" id="NAMEError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="CATEGORY" class="form-label">CATEGORY</label>
                            <select class="form-control" id="CATEGORY" name="CATEGORY" required>
                                <option value="">--PILIH--</option>
                                <option value="ELECTRONIC">ELECTRONIC</option>
                                <option value="FASHION">FASHION</option>
                                <option value="GADGET">GADGET</option>
                            </select>
                            <small class="text-danger" id="CATEGORYError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">STOCK</label> 
                            <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter stock quantity" required>
                            <small class="text-danger" id="stockError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="nama">Nama:</label>
                            <input type="text" id="nama" name="nama" class="form-control" required>
                            <small class="text-danger" id="namaError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                            <small class="text-danger" id="emailError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="pesan">Pesan:</label>
                            <textarea id="pesan" name="pesan" class="form-control" required></textarea>
                            <small class="text-danger" id="pesanError"></small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        
        <script>
            function validateForm() {
                // Clear all error messages
                document.getElementById('NAMEError').innerHTML = '';
                document.getElementById('CATEGORYError').innerHTML = '';
                document.getElementById('stockError').innerHTML = '';
                document.getElementById('namaError').innerHTML = '';
                document.getElementById('emailError').innerHTML = '';
                document.getElementById('pesanError').innerHTML = '';
                
                let isValid = true;
                
                // Validate NAME
                const NAME = document.getElementById('NAME').value.trim();
                if (NAME === '') {
                    document.getElementById('NAMEError').innerHTML = 'Product name is required';
                    isValid = false;
                } else if (NAME.length < 3) {
                    document.getElementById('NAMEError').innerHTML = 'Product name must be at least 3 characters';
                    isValid = false;
                }
                
                // Validate CATEGORY
                const CATEGORY = document.getElementById('CATEGORY').value;
                if (CATEGORY === '') {
                    document.getElementById('CATEGORYError').innerHTML = 'Please select a category';
                    isValid = false;
                }
                
                // Validate STOCK
                const stock = document.getElementById('stock').value;
                if (stock === '') {
                    document.getElementById('stockError').innerHTML = 'Stock is required';
                    isValid = false;
                } else if (isNaN(stock) || stock <= 0) {
                    document.getElementById('stockError').innerHTML = 'Stock must be a positive number';
                    isValid = false;
                }
                
                // Validate nama
                const nama = document.getElementById('nama').value.trim();
                if (nama === '') {
                    document.getElementById('namaError').innerHTML = 'Nama is required';
                    isValid = false;
                } else if (nama.length < 3) {
                    document.getElementById('namaError').innerHTML = 'Nama must be at least 3 characters';
                    isValid = false;
                }
                
                // Validate email
                const email = document.getElementById('email').value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === '') {
                    document.getElementById('emailError').innerHTML = 'Email is required';
                    isValid = false;
                } else if (!emailRegex.test(email)) {
                    document.getElementById('emailError').innerHTML = 'Please enter a valid email address';
                    isValid = false;
                }
                
                // Validate pesan
                const pesan = document.getElementById('pesan').value.trim();
                if (pesan === '') {
                    document.getElementById('pesanError').innerHTML = 'Message is required';
                    isValid = false;
                } else if (pesan.length < 5) {
                    document.getElementById('pesanError').innerHTML = 'Message must be at least 5 characters';
                    isValid = false;
                }
                
                return isValid;
            }
            
            // Real-time validation
            document.getElementById('NAME').addEventListener('blur', function() {
                const NAME = this.value.trim();
                if (NAME === '') {
                    document.getElementById('NAMEError').innerHTML = 'Product name is required';
                } else if (NAME.length < 3) {
                    document.getElementById('NAMEError').innerHTML = 'Product name must be at least 3 characters';
                } else {
                    document.getElementById('NAMEError').innerHTML = '';
                }
            });
            
            document.getElementById('CATEGORY').addEventListener('change', function() {
                if (this.value === '') {
                    document.getElementById('CATEGORYError').innerHTML = 'Please select a category';
                } else {
                    document.getElementById('CATEGORYError').innerHTML = '';
                }
            });
            
            document.getElementById('stock').addEventListener('blur', function() {
                if (this.value === '') {
                    document.getElementById('stockError').innerHTML = 'Stock is required';
                } else if (isNaN(this.value) || this.value <= 0) {
                    document.getElementById('stockError').innerHTML = 'Stock must be a positive number';
                } else {
                    document.getElementById('stockError').innerHTML = '';
                }
            });
            
            document.getElementById('nama').addEventListener('blur', function() {
                const nama = this.value.trim();
                if (nama === '') {
                    document.getElementById('namaError').innerHTML = 'Nama is required';
                } else if (nama.length < 3) {
                    document.getElementById('namaError').innerHTML = 'Nama must be at least 3 characters';
                } else {
                    document.getElementById('namaError').innerHTML = '';
                }
            });
            
            document.getElementById('email').addEventListener('blur', function() {
                const email = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === '') {
                    document.getElementById('emailError').innerHTML = 'Email is required';
                } else if (!emailRegex.test(email)) {
                    document.getElementById('emailError').innerHTML = 'Please enter a valid email address';
                } else {
                    document.getElementById('emailError').innerHTML = '';
                }
            });
            
            document.getElementById('pesan').addEventListener('blur', function() {
                const pesan = this.value.trim();
                if (pesan === '') {
                    document.getElementById('pesanError').innerHTML = 'Message is required';
                } else if (pesan.length < 5) {
                    document.getElementById('pesanError').innerHTML = 'Message must be at least 5 characters';
                } else {
                    document.getElementById('pesanError').innerHTML = '';
                }
            });
        </script>
    </body>
</head>
</html>
    
