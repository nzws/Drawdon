<!DOCTYPE html>
<html>
  <head>
    <?php
    $title = "新規お絵かき";
    include "../include/header.php"; ?>
  </head>
  <body>
    <div class="main_blur" id="mainBox">

<?php
$navbar_html = <<< EOF
<div class="row">
  <div class="col s6">
    <button class="waves-effect waves-light btn w-max blue darken-3" onclick="save_canvas()"><i class="material-icons left">save</i> 一時保存</button>
  </div>
  <div class="col s6">
    <button class="waves-effect waves-light btn w-max blue darken-3" onclick="uploadDraw('open')"><i class="material-icons left">cloud_upload</i> 投稿</button>
  </div>
</div>

<div class="row" id="restore_button" style="display: none">
  <div class="col s12">
    <button class="waves-effect waves-light btn w-max blue darken-1" onclick="restoreDraw('confirm')">保存データから復元する</button>
  </div>
</div>

<div class="input-field">
  <select onchange="changeDrawMode(event.target.value)">
    <option value="draw">Draw</option>
    <option value="fill">Fill</option>
    <option value="erase">Erase</option>
  </select>
</div>

<div class="range-field" style="z-index: 10">
  <label>太さ</label>
  <input type="range" id="weight" min="1" max="50" value="2" onchange="sketcher.weight = parseInt(this.value)"/>
</div>
<div class="row">
  <div class="col s8">
    <label>Smoothing</label>
  </div>
  <div class="col s4">
    <div class="switch">
      <label>
        <input type="checkbox" onchange="sketcher.smoothing = this.checked">
        <span class="lever"></span>
      </label>
    </div>
  </div>
</div>

<div class="row">
  <div class="col s8">
    <label>Adaptive</label>
  </div>
  <div class="col s4">
    <div class="switch">
      <label>
        <input type="checkbox" onchange="sketcher.adaptiveStroke = this.checked">
        <span class="lever"></span>
      </label>
    </div>
  </div>
</div>

<div class="row">
  <div class="col s8">
    <label>Color</label>
  </div>
  <div class="col s4" style="padding-left: 23px">
    <input type="color" onchange="sketcher.color = event.target.value;" value="#000000">
  </div>
</div>
EOF;
include "../include/side_navbar.php";
?>

      <div class="container" id="sketchBox">
        <canvas id="mySketcher"></canvas>
      </div>
    </div>

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

    <div id="restore_modal" class="modal">
      <div class="modal-content">
        <h5 class="center">保存データから復元</h5>
        <p>
          キャンバスがこのデータに上書きされます。よろしいですか？
        </p>
        <div style="text-align: center">
          <img id="restore_canvas_preview" src="" style="max-width: 50%">
        </div>
      </div>
      <div class="modal-footer">
        <button class="modal-close waves-effect btn blue darken-1" onclick="restoreDraw('start')">復元</button>
      </div>
    </div>

    <div id="upload_modal" class="modal">
      <div class="modal-content">
        <h5 class="center">Mastodonに投稿</h5>
        <div class="row">
          <div class="input-field col s6">
            <input placeholder="必要であれば入力" id="cw" type="text" class="validate">
            <label for="cw">CW (警告文)</label>
          </div>
          <div class="col s4">
            <div class="input-field">
              <select id="privacy_mode">
                <option value="public">公開</option>
                <option value="unlisted">未収載</option>
                <option value="private">非公開</option>
                <option value="direct">ダイレクト</option>
              </select>
            </div>
          </div>
          <div class="col s2">
            <label>NSFW</label>
            <div class="switch">
              <label>
                <input type="checkbox" id="nsfw">
                <span class="lever"></span>
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <textarea id="mainText" class="materialize-textarea" data-length="490"></textarea>
            <label for="mainText">本文</label>
          </div>
          <div class="col s12">
            <small>
              * 本文の最後に「#Drawdon」が自動的に挿入されます。<br>
              * CWが有効化されている場合、自動的にNSFWが有効化されます。
            </small>
          </div>
        </div>

        <div style="text-align: center">
          <img id="upload_preview" src="" style="max-width: 50%">
        </div>
      </div>
      <div class="modal-footer">
        <button class="waves-effect btn blue darken-1" onclick="uploadDraw('start')">投稿</button>
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
    <script src="js/lib/atrament.min.js"></script>
    <script src="js/mastodon_apis.js"></script>
    <script>
      var sketcher, color;
      $(document).ready(function() {
        $('.modal').modal();
        $('select').formSelect();
        $('textarea').characterCounter();

        if (localStorage.getItem('drawdon_token')) {
          start_drawing()
        } else {
          M.Modal.getInstance(elemid("start_modal")).open();
        }
      });
    </script>
  </body>
</html>
