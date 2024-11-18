<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>The Chat</title>
        <link rel="stylesheet" href="./style.css">
        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    </head>
    <body>
        <div class="chat">
            <div class="top">
                <div>
                    <img width="100" height="100" src="./img.webp" alt="">
                    <p>Lorem Ipsum</p>
                    <small>Online</small>
                </div>
            </div>
            <div class="messages">
                @include('receive', ['message' => "whats up???"])
            </div>

            <div class="bottom">
                <form>
                    <input type="text" id="message" name="message" placeholder="Enter Message" autocomplete="off">
                    <button type="submit"></button>
                </form>
            </div>
        </div>

        <script>
            const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster:'us2'});
            const channel = pusher.subscribe('public');

            channel.bind('chat', function (data) {
                $.post("/receive", {
                    _token: '{{csrf_token()}}',
                    message: data.message,
                })
                    .done(function(res) {
                        $(".messages  > .message").last().after(res);
                        $(document).scrollTop($(document).height());
                    });
            });

            $("form").submit(function (event) {
                event.preventDefault();
                
                $.ajax({
                    url:    "/broadcast",
                    method: "POST",
                    headers: {
                        'X-Socket-Id': pusher.connection.socket_id,
                    },
                    data: {
                        _token:   '{{csrf_token()}}',
                        message:  $("form #message").val(),
                    }
                }).done(function (res){
                    $(".messages > .message").last().after(res);
                    $("form #message").val('');
                    $(document).scrollTop($(document)[0].scrollHeight);
                }).fail(function(err) {
                    console.error("Failed to broadcast:", err);
                });                
            });
        </script>
    </body>
</html>