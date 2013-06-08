$('#icons span').after(function() {
  return " ." + $(this).attr('class') + "<br/>";
});
/* ==========================================================================
 Source code styling
 ========================================================================== */
$('.code').html(function() {
  var code = $(this).html().trim();
  var lines = code.split('\n');
  var codeWrapped = '';
  for (i = 0; i < lines.length; i++) {
    if (lines[i] === "") {
      lines[i] = '&nbsp;';
    }
    codeWrapped += '<div class="code-line">' + lines[i] + '</div>';
  }
  return codeWrapped;
});

var isMultiLineComment = false;
//code high-lighting
$('.code-line').html(function() {
  //string between quotes
  var stringHL = /("|')((?:[^"\\]|\\.)*)("|')/gi;
  var code = $(this).html().replace(stringHL,
          "<span class=\"code-highlight-string\">$1$2$1</span>");
  //string between /* */
  var beginComment = /\/\*(.*)/g;
  var endComment = /(.*)\*\//g;
  var commentHL = /\/\*(.*)\*\//g;
  if (code.match(commentHL)) {
    code = code.replace(commentHL,
            "<span class=\"code-highlight-comment\">/*$1*/</span>");
  } else if (code.match(beginComment)) {
    code = code.replace(beginComment,
            "<span class=\"code-highlight-comment\">/*$1</span>");
    isMultiLineComment = true;
  } else if (isMultiLineComment) {
    if (code.match(endComment)) {
      code = code.replace(endComment,
              "<span class=\"code-highlight-comment\">$1*/</span>");
      isMultiLineComment = false;
    } else {
      code = "<span class=\"code-highlight-comment\">" + code + "</span>";
    }
  }
  return code;
});
//code line numbering
$('.code-line-numbered').wrapInner('<ol>');
$('.code-line-numbered > ol > .code-line').wrap('<li>');
/* ==========================================================================
 Dropdown menu
 ========================================================================== */
$('body').on('click', function() {
  $('.dropdown-menu').slideUp('fast');
});
$('.dropdown').on('click', function(e) {
  e.stopPropagation();
  $(this).next('.dropdown-menu').slideToggle('fast');
});