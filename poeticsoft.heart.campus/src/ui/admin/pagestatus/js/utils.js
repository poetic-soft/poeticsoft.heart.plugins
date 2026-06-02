const { apiFetch } = wp

export const updateFree = ($, id, isChecked) => {

  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/free-update',
    method: "POST",
    data: {
      postid: id,
      isfree: isChecked
    }
  })
}

export const updateData = ($, $pageStatuses) => {

  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/free-get',
    method: "GET"
  })
  .then(response => {

    const pages = response.data.pages

    $pageStatuses.each(function() {

      const $this = $(this);
      const id = $this.attr('id').replace('post-', '');
      const $toggleFree = $this.find('.PriceTools .Access input.IsFree');
      const $toggleLabel = $this.find('.PriceTools .Access label');

      if (pages[id] === 'free') {
        $toggleFree.prop("checked", true);
        $toggleLabel.html('Abierta');
        $toggleLabel.addClass('Free');
      } else {
        $toggleLabel.html('Restringida');
      }
    });
  })
  .catch(error => console.error('Heart Campus API Error:', error));
}