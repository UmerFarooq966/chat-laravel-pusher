<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="chat">

<div class="top">
<img src="https://assets.edlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">
    <div>
        <p>Ross Edlin</p>
        <small>Online</small>
    </div>
</div>

<div class="messages">
@include('receive', ['message' => "Hey! What's up!  👋"])
    @include('receive', ['message' => "Ask a friend to open this link and you can chat with them!"])
  </div>
<div class="bottom">
    <form action="">
    <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
    </form>
</div>

</div>
<script>
    const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", { 
        cluster: 'ap2' // Match the cluster in your .env file
    });
    const channel = pusher.subscribe('public');

    // Receive messages
    channel.bind('chat', function(data) {
        $.post("/receive", {
            _token: '{{ csrf_token() }}',  // Fixed the CSRF token syntax
            message: data.message,
        })
        .done(function(res) {
            $(".messages > .message").last().after(res);
            $(document).scrollTop($(document).height()); // Scroll to the bottom
        });
    });

    // Broadcast messages
    $("form").submit(function (event) {
        event.preventDefault();

        $.ajax({
            url: "/broadcast",
            method: 'POST',
            headers: {
                'X-Socket-Id': pusher.connection.socket_id  // Prevents message from being echoed to sender
            },
            data: {
                _token: '{{ csrf_token() }}',  // Fixed the CSRF token syntax
                message: $("form #message").val(),
            }
        }).done(function(res) {
            $(".messages > .message").last().after(res);
            $("form #message").val(''); // Clear the input after sending
            $(document).scrollTop($(document).height()); // Scroll to the bottom
        });
    });
</script>

    
</body>
</html>