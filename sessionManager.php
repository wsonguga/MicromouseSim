<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css" type="text/css">
    <title>CORE Sessions</title>
  </head>
  <body>
    <h1 class="text-center">CORE Sessions Management</h1>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <div class="container">
      <h2>Running Session List</h2>       
      <table class="table">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Session ID</th>
            <th>Status</th>
            <th>Operation</th>
          </tr>
        </thead>
        <tbody>
          <?php
            ini_set('display_errors', 1);

            include "dbcon.php";

            $conn = getDBConnection();
            $sql = "SELECT userId, sessionId, status from sessions;";
            $temp = $conn->query($sql);

            if ($temp->num_rows > 0) {
                // output data of each row
                while($row = $temp->fetch_assoc()) {
                    $result = "<tr>"."<td>".$row["userId"]."</td>"."<td>".$row["sessionId"]."</td>";
                    if (strcmp($row["status"], "active") == 0) {
                        $result = $result."<td id=\"status".$row["sessionId"]."\">".$row["status"]."</td>";
                        $result = $result."<td><button id=\"btn".$row["sessionId"]."\" onclick=\"stopSession(".$row["sessionId"].")\">Stop</button></td>"."</tr>";
                    } else {
                        $result = $result."<td>".$row["status"]."</td>";
                        $result = $result."<td><button disabled>Stop</button></td>"."</tr>";
                    }
                    echo $result;
                }
            }
            
            $conn->close();

          ?>
        </tbody>
      </table>
    </div>
  </body>
  <script type="text/javascript">
      function stopSession(sessionId) {
        $.ajax({
                url:"stopSession.php ",
                type:"POST",

                data:{
                    session: sessionId,
                },
                success:function(response) {
                    if (response == "Stopped") {
                        $("#btn"+sessionId).attr("disabled", true);
                        $("td#status"+sessionId).html("dead");
                    } else {
                        alert("Session failed to terminate.");
                    }
                },
                error:function() {
                    alert("Session failed to terminate.");
                }

            });
      }
  </script>
</html>