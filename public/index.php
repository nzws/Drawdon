<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
    $title = "ホーム";
    include "../include/header.php"; ?>
  </head>
  <body>

<?php
$navbar_html = <<< EOF
<div class="row">
  <div class="col s12">
    <button class="waves-effect waves-light btn w-max blue darken-3" onclick="save_canvas()"><i class="fas fa-sign-in-alt"></i> ログインして始める</button>
  </div>
  <div class="col s12 center">
    <p>
        <small>または <a href="">ゲストとして始める</a></small>
    </p>
  </div>
</div>
EOF;
include "../include/side_navbar.php";
?>

      <main>
          <div class="container">
            <h3><i class="fas fa-paint-brush"></i> Drawdon</h3>
            Easily upload drawing to Mastodon
            <hr>

          </div>
      </main>

    <div id="login_modal" class="modal">
      <div class="modal-content">
        <h4 class="center">Drawdonにログイン</h4>
        <div class="row">
          <div class="input-field col m10 s12">
            <input placeholder="mastodon.social" id="mstdn_domain" type="text" class="validate w-max">
            <label for="mstdn_domain">Mastodonでログイン</label>
          </div>
          <div class="col m2 s12" align="center">
            <button class="waves-effect waves-light btn-large blue darken-1" onclick="newlogin(elemid('mstdn_domain').value)">Login</button>
          </div>
        </div>
        <small>このログインを行ったことで、ユーザーに無断で投稿される事はありません。</small>
      </div>
    </div>

    <div id="error" class="modal">
      <div class="modal-content">
        <h5 class="center">申し訳ありません。問題が発生しました。 :(</h5>
        <p>
          何度も発生する場合は、この画面のスクリーンショットを撮って<a href="https://knzk.me/@y" target="_blank">開発者</a>にお知らせ頂きますようお願いします。
        </p>
        <pre id="error_text"></pre>
      </div>
    </div>
<?php include "../include/footer.php"; ?>
    <script>
      var sketcher, color;
      $(document).ready(function() {
        $('.modal').modal();
      });
    </script>
  </body>
</html>
