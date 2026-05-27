const { apiFetch } = wp

export const updatefree = ($, id, ischecked) => {

  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/free-update',
    method: "POST",
    data: {
      postid: id,
      isfree: ischecked
    }
  })
}

export const updatedata = ($, $pagesprices) => {

  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/free-get',
    method: "GET"
  })
  .then(response => {

    const pages = response.data.pages

    $pagesprices.each(function() {

      const $this = $(this);
      const id = $this.attr('id').replace('post-', '');
      const $tooglefree = $this.find('.PriceTools .Access input.IsFree');
      const $tooglelabel = $this.find('.PriceTools .Access label');

      if (pages[id] === 'free') {
        $tooglefree.prop("checked", true);
        $tooglelabel.html('Abierta');
        $tooglelabel.addClass('Free');
      } else {
        $tooglelabel.html('Restringida');
      }
    });
  })
  .catch(error => console.error('Heart Campus API Error:', error));
}