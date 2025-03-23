<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta NAME="viewport" content="width=device-width , initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

</head>

<body>
    @vite('resources/js/app.js')
</body>
<script>
    setTimeout(()=>{
        window.Echo.channel('chat')
            .listen('NewMessage',(e)=>{
             console.log(e)

            } ,5 )

    })
</script>

</html>
