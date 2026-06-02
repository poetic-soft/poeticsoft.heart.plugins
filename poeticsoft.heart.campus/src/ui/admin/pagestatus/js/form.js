export const rowForm = (
  $, 
  postId, 
  elm = 'div',
  data = {}
) => {

  return `<${ elm } id="${ postId }" class="PHCPrice">
    <div class="PriceTools">
      <div class="PostId">${ postId.replace('post-', '') }</div>
      <div class="Access">
        <input   
          type="checkbox"
          id="isfree_${ postId }"
          name="isfree_${ postId }"
          class="IsFree"
          ${ data.isfree ? 'checked' : '' }
        />
        <label 
          for="isfree_${ postId }"
          class="${ data.isfree ? 'Free' : '' }"
        >
          Abierta
        </label>
      </div>
    </div>
  </${ elm }>`
}