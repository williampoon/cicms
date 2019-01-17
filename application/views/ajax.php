<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
</head>
<body>
    <input id="send" type="button" value="Send"/>
    <script type="text/javascript" src="/public/js/jquery-2.1.4.min.js"></script>
    <script>
        $(function() {
            $('#send').click(function() {
                $.ajax('/api/user/index2', {
                    type: 'get',
                    dataType: 'json',
                    cache: true,
                    timeout: 1,
                    success: function(data, status, xhr) {
                        console.log('success', status, xhr);
                        if (xhr.status != 200) {
                            alert('Fail to get data');
                            return;
                        }
                    },
                    error: function(xhr, status) {
                        console.log('error', status, xhr);
                    }
                });
            });
        });
    </script>
</body>
</html>
