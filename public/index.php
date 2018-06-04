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
            <h3>Drawdon</h3>
          </div>
      </main>

    <div id="start_modal" class="modal">
      <div class="modal-content">
        <h4 class="center">Drawdonへようこそ！</h4>
        <p class="center">
          ログインしてお絵かきを始めましょう！<br>
          (DrawdonはPC向けのサービスです。スマートフォンでは<a href="https://knzkapp.nzws.me" target="_blank">KnzkApp</a>のDoodle機能をご利用ください。)
        </p>
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
        <p>
          <small>* Drawdonはアルファ版です。今後機能を追加予定です。</small><br>
          <a href="https://github.com/yuzulabo/Drawdon" target="_blank">Source code</a> · Contact: <a href="https://knzk.me/@y" target="_blank">@y@knzk.me</a>
        </p>
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
    <?php echo "../include/footer.php"; ?>
    <script>
      var sketcher, color;
      $(document).ready(function() {
        $('.modal').modal();
      });
    </script>
  </body>
</html>