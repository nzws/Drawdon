function show_error(text) {
  document.getElementById("error_text").innerText = text;
  M.Modal.getInstance(document.getElementById("error")).open();
}

function elemid(id) {
  return document.getElementById(id);
}