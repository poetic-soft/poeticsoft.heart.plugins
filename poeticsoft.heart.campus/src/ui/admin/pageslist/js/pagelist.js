const { apiFetch } = wp

const rowForm = ($, postId, access) => {

  return `<div id="${ postId }" class="PHCAccess">
    <div class="AccessTools">
      <div class="PostId">${ postId }</div>
      <div class="Access ${ access === 'abierta' ? 'IsOpen' : '' }">
        ${ access === 'abierta' ? 'Abierta' : 'Restringida' }
      </div>
    </div>
  </div>`
}

const refresh = ($, pages) => {
  
  const urlParams = new URLSearchParams(window.location.search);
  const postStatus = urlParams.get('post_status');
  if (
    postStatus != 'trash'
    &&
    postStatus != 'draft'
  ) {

    $('body').addClass('PoeticsoftHeartCampus')

  } else {

    return
  }

  const statusKey = 'PoeticsoftHeartCampusPageListState'
  
  const $thelist = $('body.wp-admin.post-type-page #the-list')
  const $trs = $thelist.find('tr')

  let state = {}

  const $trsbyparentid = {}
  $trs.each(function() {

    const $tr = $(this)
    const id = $tr.attr('id')
    const childIds = poeticsoft_heart_campus_admin_pageslist[id]
    $trsbyparentid[id] = childIds.map(cid => $thelist.find('tr#' + cid))
    if(poeticsoft_heart_campus_admin_campus_ids.includes(id)) {

      $tr.addClass('InCampus')
    }

    state[id] = false
  })

  const closebranch = id => {

    const $children = $trsbyparentid[id];
    if(!$children) { return }
    $children.forEach($c => {

      $c.removeClass('Visible Opened')
    })

    const childIds = poeticsoft_heart_campus_admin_pageslist[id]
    childIds.length && childIds.forEach(cid => closebranch(cid))
  }  

  const updateNav = () => {

    $trs.each(function() {

      const $tr = $(this)
      const id = $tr.attr('id')

      if(state[id]) {

        $tr.addClass('Opened')

        const $children = $trsbyparentid[id];
        $children.forEach($c => {

          $c.addClass('Visible')
        })

        state[id] = true

      } else {

        $tr.removeClass('Opened')

        closebranch(id)
      }
    })
  }

  const saveState = () => {

    localStorage.setItem(
      statusKey,
      JSON.stringify(state)
    )
  }

  $trs.each(function() {

    const $tr = $(this)
    const id = $tr.attr('id')
    const postId = id.replace('post-', '')
    const $title = $tr.find('td.column-title a.row-title')    
    const $titleContainer = $title.parent('strong')
    const childIds = poeticsoft_heart_campus_admin_pageslist[id]

    $title.html($title.html().split('— ').join(''))
    $titleContainer.addClass('TitleContainer')

    const hasControls = $titleContainer.find('.Control').length > 0; // In case of quick edit, controls are already there, so we don't want to add them again
    
    if(!hasControls) {
      
      if(childIds.length) {

        $tr.addClass('HasChildren')
        $titleContainer.prepend('<span class="Control OpenClose"></span>')
        
      } else {

        $titleContainer.prepend('<span class="Control Indent"></span>')
      }
    }

    if(poeticsoft_heart_campus_admin_campus_ids.includes(id)) {

      const $columnStatus = $tr.find('> .access.column-access')      
      $columnStatus.html(rowForm($, postId, pages[postId]))
    }

    const $openclose = $tr.find('.OpenClose')
    $openclose.on(
      'click',
      function() {

        if($tr.hasClass('Opened')) {

          $tr.removeClass('Opened')

          closebranch(id)

          state[id] = false

        } else {

          $tr.addClass('Opened')

          const $children = $trsbyparentid[id];
          $children.forEach($c => {

            $c.addClass('Visible')
          })

          state[id] = true
        }

        saveState()

        return false;
      }
    )
  })

  const checkState = () => {

    const actualState = JSON.parse(localStorage.getItem(statusKey)) || {}
    const stateKeysCount = Object.keys(actualState).length
    const trsLength = $trs.length

    if(stateKeysCount < trsLength) {

      $trs.each(function() {

        const $tr = $(this)
        const id = $tr.attr('id')

        if(actualState[id] === undefined) {
          
          actualState[id] = false
        }
      })
    }

    state = actualState

    updateNav()
  }
  
  checkState()
}

export default ($) => {

  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/access/get',
    method: "GET"
  })
  .then(response => {

    const pages = response.data.pages

    refresh($, pages)
  })
  .catch(error => console.error('Heart Campus API Error:', error));
}