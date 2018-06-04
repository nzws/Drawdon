function start_drawing() {
  if (localStorage.getItem('drawdon_save_canvas')) {
    elemid('restore_button').style.display = "block";
  }
  M.Modal.getInstance(elemid("start_modal")).close();
  elemid("mainBox").className = "";

  sketcher = atrament('#mySketcher', 500, 500);
  sketcher.smoothing = false;
  sketcher.adaptiveStroke = false;
  var canvas = elemid('mySketcher');
  var ctx = canvas.getContext('2d');
  ctx.fillStyle = 'rgb(255,255,255)';
  ctx.fillRect(0, 0, 500, 500);
}

function changeDrawMode(mode) {
  if (mode === "erase") {
    color = sketcher.color;
    sketcher.mode = "draw";
    sketcher.color = "#ffffff";
  } else {
    sketcher.mode = mode;
    if (color) {
      sketcher.color = color;
      color = null;
    }
  }
}

function restoreDraw(mode) {
  if (mode === "start") {
    var image = new Image(),
      ctx = elemid('mySketcher').getContext("2d");
    image.onload = function () {
      ctx.drawImage(image, 0, 0);
      M.toast({ html: '復元しました。' });
      localStorage.removeItem('drawdon_save_canvas');
      elemid('restore_button').style.display = "none";
    };
    image.src = localStorage.getItem('drawdon_save_canvas');
  } else {
    M.Modal.getInstance(elemid('restore_modal')).open();
    elemid('restore_canvas_preview').src = localStorage.getItem('drawdon_save_canvas');
  }
}

function uploadDraw(mode) {
  if (mode === "start") {
    M.toast({ html: '投稿: アップロードしています...<br>そのまましばらくお待ち下さい。' });
    var binary = atob(sketcher.toImage().split(',')[1]), array = [];
    for (var i = 0; i < binary.length; i++) array.push(binary.charCodeAt(i));
    var blob = new Blob([new Uint8Array(array)], { type: 'image/png' });

    var formData = new FormData();
    formData.append('file', blob);

    fetch("https://" + localStorage.getItem('drawdon_domain') + "/api/v1/media", {
      headers: { 'Authorization': 'Bearer ' + localStorage.getItem('drawdon_token') },
      method: 'POST',
      body: formData
    }).then(function (response) {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error();
      }
    }).then(function (json) {
      if (json) {
        if (json["id"] && json["type"] !== "unknown") {
          var cw = elemid("cw").value, data = {
            status: elemid("mainText").value + " #Drawdon",
            visibility: elemid("privacy_mode").value,
            media_ids: [json["id"]],
            sensitive: elemid("nsfw").checked
          };
          if (cw) data.spoiler_text = cw;

          fetch("https://" + localStorage.getItem('drawdon_domain') + "/api/v1/statuses", {
            headers: {
              'content-type': 'application/json',
              'Authorization': 'Bearer ' + localStorage.getItem('drawdon_token')
            },
            method: 'POST',
            body: JSON.stringify(data)
          }).then(function (response) {
            if (response.ok) {
              return response.json();
            } else {
              throw new Error();
            }
          }).then(function (json) {
            if (json["id"]) {
              M.Modal.getInstance(elemid('upload_modal')).close();
              M.toast({ html: '完了: 投稿しました！<br><a href="' + json["url"] + '" target="_blank">トゥートを見る</a>' });
            } else {
              M.toast({ html: 'エラー: トゥートに失敗しました。:成功していないようです' });
            }
          }).catch(function (error) {
            M.toast({ html: 'エラー: トゥートに失敗しました。:原因不明のエラー' });
          });
        } else {
          M.toast({ html: 'エラー: 画像の投稿に失敗しました。:unknownが返されました' });
        }
      }
    }).catch(function (error) {
      M.toast({ html: 'エラー: 画像の投稿に失敗しました。:原因不明のエラー' });
      console.log(error);
    });
  } else {
    M.Modal.getInstance(elemid('upload_modal')).open();
    elemid('upload_preview').src = elemid('mySketcher').toDataURL("image/png");
  }
}

function save_canvas() {
  var canvas = elemid('mySketcher').toDataURL("image/png");
  localStorage.setItem('drawdon_save_canvas', canvas);
  M.toast({ html: '一時保存しました。<br>復元するには「保存データから復元する」を押してください。' });
  elemid('restore_button').style.display = "block";
}
