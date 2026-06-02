import {
  rowForm 
} from './form' 

export const editPageStatus = $ =>  {

  let $pageStatusWrapper = $('#phc_page_assign_status .inside .statuswrapper')
  if($pageStatusWrapper.length) {

    $pageStatusWrapper = $pageStatusWrapper.eq(0)
    const postId = $pageStatusWrapper.data('id')
    $pageStatusWrapper.html(rowForm($, postId))
    const $pageRows = $pageStatusWrapper.find('.PHCPrice');

    return $pageRows
  }
}

export const normalPagesStatus = $ =>  {

  let $pagesList = $('#the-list')
  if($pagesList.length) {

    const $pagesRow = $pagesList
    .find('> tr')
    .filter(
      function() {

        const $pageRow = $(this)
        const postIdRaw = $pageRow.attr('id')
        const id = postIdRaw.replace('post-', '')

        return poeticsoft_heart_campus_admin_campus_ids.includes(postIdRaw)
      }
    )

    return $pagesRow
    .map(
      function() {

        const $pageRow = $(this)
        const postId = $pageRow.attr('id')
        const $columnStatus = $pageRow.find('> .status.column-status')

        $columnStatus.append(rowForm($, postId))

        return $columnStatus.find('.PHCPrice').eq(0)
      }
    )
  }

  return null
} 
 