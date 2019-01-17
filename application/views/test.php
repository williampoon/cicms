<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
</head>
<body>
    <script type="text/javascript" src="/public/js/jquery-2.1.4.min.js"></script>
    <script>
        $(function() {
            $.get('/api/user/index1', function(data, status, xhr) {
                console.log(data);
            });
        });
    </script>
</body>
</html>
