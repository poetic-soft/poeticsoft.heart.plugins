const { apiFetch } = wp

export const updateOpen = ($, id, isChecked) => {

  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/access/update',
    method: "POST",
    data: {
      postid: id,
      isopen: isChecked
    }
  })
}

export const updateData = ($, $pageStatuses) => {

  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/access/get',
    method: "GET"
  })
  .then(response => {

    const pages = response.data.pages

    $pageStatuses.each(function() {

      const $this = $(this);
      const id = $this.attr('id').replace('post-', '');
      const $toggleOpen = $this.find('.AccessTools .Access input.IsOpen');
      const $toggleLabel = $this.find('.AccessTools .Access label');

      if (pages[id] === 'abierta') {
        $toggleOpen.prop("checked", true);
        $toggleLabel.html('Abierta');
        $toggleLabel.addClass('Open');
      } else {
        $toggleLabel.html('Restringida');
      }
    });
  })
  .catch(error => console.error('Heart Campus API Error:', error));
}