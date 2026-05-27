import {
  rowform 
} from './form' 

export const editpageprice = $ =>  {

  let $pagepricewrapper = $('#pcp_page_assign_price .inside .pricewrapper')
  if($pagepricewrapper.length) {
    
    $pagepricewrapper = $pagepricewrapper.eq(0)
    const postid = $pagepricewrapper.data('id')
    $pagepricewrapper.html(rowform($, postid))
    const $pagerows = $pagepricewrapper.find('.PHCPrice');

    return $pagerows
  }
}

export const normalpagesprices = $ =>  {

  let $pageslist = $('#the-list')
  if($pageslist.length) {
    
    const $pagesrow = $pageslist
    .find('> tr')
    .filter(
      function() {

        const $pagerow = $(this)
        const postid = $pagerow.attr('id')
        const id = postid.replace('post-', '')

        return poeticsoft_heart_campus_admin_campus_ids.includes(postid)
      }
    )

    return $pagesrow
    .map(
      function() {

        const $pagerow = $(this)
        const postid = $pagerow.attr('id')
        const $columstatus = $pagerow.find('> .status.column-status')

        $columstatus.append(rowform($, postid))
        
        return $columstatus.find('.PHCPrice').eq(0)
      }
    )
  }

  return null
} 