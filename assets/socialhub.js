(function(){$(function(){

  $('.btn-highlight,.btn-highlighted').click(function(e) {
    var state = this.getAttribute('data-state');
    e.preventDefault();

    $.ajax({
      url: this.href,
      data: {state: state}
    });

    if(state == 'highlight') {
      this.className = 'btn btn-highlighted';
      this.setAttribute('data-state','highlighted');
      this.getElementsByTagName('i')[0].className = 'rex-icon rex-icon-highlighted';
    } else {
      this.className = 'btn btn-highlight';
      this.setAttribute('data-state','highlight');
      this.getElementsByTagName('i')[0].className = 'rex-icon rex-icon-highlight';
    }
  });
});})(jQuery);