@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    <h2 class="my-2">Customer Data Form</h2>

                    <form id="dataForm">
                        <div class="form-group mt-2">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group mt-2">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group mt-2">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required><br><br>
                        </div>

                        <button type="submit" class="btn btn-dark btn-sm">Submit Data</button>
                    </form>

                    <h2 class="my-4">Stored Customer Data</h2>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="customerDataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize the customer DataTable
    $(document).ready(function() {
        $('#customerDataTable').DataTable();
    });

    // Initialize IndexedDB
    let indexedDatabase;
    const request = indexedDB.open('FormDB', 1);

    request.onerror = function(event) {
        console.log('Database error: ' + event.target.errorCode);
    };

    request.onsuccess = function(event) {
        indexedDatabase = event.target.result;
        displayData();
    };

    request.onupgradeneeded = function(event) {
        indexedDatabase = event.target.result;
        const objectStore = indexedDatabase.createObjectStore('formData', {
            keyPath: 'id',
            autoIncrement: true
        });
        objectStore.createIndex('name', 'name', {
            unique: false
        });
        objectStore.createIndex('email', 'email', {
            unique: false
        });
        objectStore.createIndex('address', 'address', {
            unique: false
        });
    };

    // Customer data form submission
    document.getElementById('dataForm').addEventListener('submit', function(event) {
        event.preventDefault();
        let name = document.getElementById('name').value;
        let email = document.getElementById('email').value;
        let address = document.getElementById('address').value;

        const transaction = indexedDatabase.transaction(['formData'], 'readwrite');
        const objectStore = transaction.objectStore('formData');
        const request = objectStore.add({
            name: name,
            email: email,
            address: address
        });

        request.onsuccess = function(event) {
            displayData();            
            toastr.success("Record added successfully !");
        };

        request.onerror = function(event) {
            console.log('Error adding data: ' + event.target.errorCode);
            toastr.error("Error occured while adding the record. code - "+ event.target.errorCode);

        };
    });
    // Display data on data table
    function displayData() {
        const transaction = indexedDatabase.transaction(['formData'], 'readonly');
        const objectStore = transaction.objectStore('formData');
        const request = objectStore.getAll();

        request.onsuccess = function(event) {
            const data = event.target.result;
            const table = $('#customerDataTable').DataTable();
            table.clear();
            data.forEach(item => {
                table.row.add([item.name, item.email, item.address]);
            });
            table.draw();
        };

        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('address').value = '';

        document.getElementById('name').focus();

    }
</script>
@endsection