<html>
<head>
</head>
<body>
<script>

    window.location = "{{$primaryRedirection}}"; // will result in error message if app not installed
    setTimeout(function() {
        // Link to the App Store should go here -- only fires if deep link fails
        window.location = "{{$secndaryRedirection}}";
    }, 500);

</script>
</body>
</html>