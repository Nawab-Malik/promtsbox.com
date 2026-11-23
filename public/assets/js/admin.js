(function () {
  function bindCounters() {
    document.querySelectorAll('[data-counter]').forEach(function (el) {
      var max = parseInt(el.getAttribute('data-max') || '160', 10);
      var counter = el.parentElement.querySelector('.char-counter');
      if (!counter) {
        counter = document.createElement('span');
        counter.className = 'char-counter meta';
        counter.style.display = 'block';
        el.parentElement.appendChild(counter);
      }
      var update = function () {
        var len = (el.value || '').length;
        counter.textContent = len + ' / ' + max;
        counter.style.color = len > max ? '#f87171' : '#94a3b8';
        var titleEl = document.querySelector('input[name="meta_title"]');
        var descEl = document.querySelector('textarea[name="meta_description"]');
        var prevTitle = document.getElementById('serpTitle');
        var prevDesc = document.getElementById('serpDesc');
        if (prevTitle && titleEl) prevTitle.textContent = titleEl.value || document.querySelector('input[name="title"]')?.value || 'Page title';
        if (prevDesc && descEl) prevDesc.textContent = descEl.value || 'Meta description preview...';
      };
      el.addEventListener('input', update);
      update();
    });
  }

  var toast = document.getElementById('adminToast');
  if (toast) {
    setTimeout(function () { toast.classList.remove('show'); }, 4200);
  }

  bindCounters();

  window.openMediaPicker = function (targetInputId) {
    var url = document.body.getAttribute('data-media-json');
    fetch(url)
      .then(function (r) { return r.json(); })
      .then(function (data) {
        var items = data.items || [];
        var pick = prompt('Paste image path or URL from library:\n\n' + items.slice(0, 12).map(function (i) { return i.path + '  →  ' + i.url; }).join('\n'));
        if (pick) {
          var input = document.getElementById(targetInputId);
          if (input) input.value = pick.trim();
        }
      });
  };

  if (typeof tinymce !== 'undefined') {
    var uploadUrl = document.body.getAttribute('data-editor-upload');
    var tokenInput = document.querySelector('input[name="_token"]');
    tinymce.init({
      selector: 'textarea.rich-editor',
      height: 420,
      menubar: false,
      plugins: 'lists link image code table',
      toolbar: 'undo redo | styles | bold italic | bullist numlist | link image | code',
      content_style: 'body{font-family:Poppins,system-ui,sans-serif;font-size:16px;line-height:1.65}',
      automatic_uploads: true,
      images_upload_handler: function (blobInfo) {
        return new Promise(function (resolve, reject) {
          if (!uploadUrl || !tokenInput) {
            reject('Upload not configured');
            return;
          }
          var xhr = new XMLHttpRequest();
          xhr.open('POST', uploadUrl);
          var fd = new FormData();
          fd.append('file', blobInfo.blob(), blobInfo.filename());
          fd.append('_token', tokenInput.value);
          xhr.onload = function () {
            if (xhr.status < 200 || xhr.status >= 300) {
              reject('HTTP ' + xhr.status);
              return;
            }
            try {
              var json = JSON.parse(xhr.responseText);
              if (json.location) {
                resolve(json.location);
              } else {
                reject('Invalid response');
              }
            } catch (e) {
              reject('Parse error');
            }
          };
          xhr.onerror = function () { reject('Network error'); };
          xhr.send(fd);
        });
      }
    });
  }
})();