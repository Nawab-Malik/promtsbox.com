(function () {
  var root = document.getElementById('postContent');
  var toc = document.getElementById('postToc');
  if (!root || !toc) return;
  var headings = root.querySelectorAll('h2, h3');
  if (!headings.length) return;
  var html = '<strong>In this article</strong><ul style="margin:0.5rem 0 0;padding-left:1.2rem;">';
  headings.forEach(function (h, i) {
    var id = h.id || 'section-' + (i + 1);
    h.id = id;
    html += '<li style="margin:0.2rem 0;"><a href="#' + id + '">' + h.textContent + '</a></li>';
  });
  html += '</ul>';
  toc.innerHTML = html;
})();