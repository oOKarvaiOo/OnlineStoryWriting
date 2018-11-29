<?php
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Online Story Writing</title>
    <!-- Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- jQuery CDN -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <!-- Socket.IO -->
    <script src="/socket.io/socket.io.js"></script>
    <style>
      #messageArea{
        display: none;
      }
    </style>
  </head>
  <body>
    <div class="container">

      <div id="userFormArea" class="row">
          <div class="col-md-12">
            <form id="userForm">
              <div class="form-group">
                <label>Enter Username</label>
                <input class="form-control" id="username"><br/>
                <input type="submit" class="btn btn-primary" value="Login">

              </div>
            </form>
          </div>
      </div>

      <div class="row" id="messageArea">
        <div class="col-lg-4">
          <div class="well">
            <h3>Online Users</h3>
            <ul class="list-group" id="users"></ul>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="chat" id="chat"></div>
          <form id="messageForm">
            <div class="form-group">
              <label>Enter Message</label>
              <textarea class="form-control" id="message"></textarea>
              <input type="submit" class="btn btn-primary" value="Send Message">

            </div>
          </form>
        </div>
      </div>

    </div>

    <script>
      $(function(){
        var socket = io.connect();
        var $messageForm= $('#messageForm');
        var $message= $('#message');
        var $chat = $('#chat');
        var $messageArea = $('#messageArea');
        var $userForm = $('#userForm');
        var $userFormArea = $('#userFormArea');
        var $users = $('#users');
        var $username = $('#username');

        $messageForm.submit(function(e){
          e.preventDefault();
          socket.emit('send message', $message.val());
          $message.val('');
        });

        socket.on('new message', function(data){
          $chat.append('<div class="well"><strong>'+data.user+':</strong>'+data.msg+'</div>');
        });

        $userForm.submit(function(e){
          e.preventDefault();
          socket.emit('new user', $username.val(), function(data){
            if (data) {
              $userFormArea.hide();
              $messageArea.show();
            }
          });
          $username.val('');
        });

        socket.on('get users',function(data){
          var html = '';
          for (var i = 0; i < data.length; i++) {
            html+= '<li class="list-group-item">'+data[i]+'</li>';
          }
          $users.html(html);
        });
      });
    </script>
  </body>
</html>
