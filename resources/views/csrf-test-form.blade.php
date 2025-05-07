<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Test Form</title>
    @csrfMeta
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .box {
            background: #f5f5f5;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Display current CSRF token
            document.getElementById('csrf-token').textContent = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Display all cookies
            document.getElementById('cookies').textContent = document.cookie;
            
            // Handle form submission
            document.getElementById('test-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                let result = document.getElementById('result');
                result.style.display = 'block';
                result.textContent = 'Submitting form...';
                
                fetch('{{ route("csrf.test.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        test: 'data'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    result.textContent = JSON.stringify(data, null, 2);
                    result.style.color = 'green';
                })
                .catch(error => {
                    result.textContent = 'Error: ' + error;
                    result.style.color = 'red';
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>CSRF Token Test Page</h1>
        
        <div class="box">
            <h3>Current CSRF Token:</h3>
            <code id="csrf-token">Loading...</code>
        </div>
        
        <div class="box">
            <h3>Current Cookies:</h3>
            <code id="cookies">Loading...</code>
        </div>
        
        <form id="test-form">
            <h3>Test Form Submission</h3>
            <p>Click the button below to test a form submission with CSRF protection:</p>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit">Submit Test Form</button>
        </form>
        
        <pre id="result" class="result">Results will appear here...</pre>
    </div>
</body>
</html>