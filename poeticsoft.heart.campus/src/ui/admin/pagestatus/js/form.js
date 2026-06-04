export const rowForm = (
  $, 
  postId, 
  elm = 'div',
  data = {}
) => {

  return `<${ elm } id="${ postId }" class="PHCAccess">
    <div class="AccessTools">
      <div class="PostId">${ postId.replace('post-', '') }</div>
      <div class="Access">
        <input   
          type="checkbox"
          id="isopen_${ postId }"
          name="isopen_${ postId }"
          class="IsOpen"
          ${ data.isopen ? 'checked' : '' }
        />
        <label 
          for="isopen_${ postId }"
          class="${ data.isopen ? 'Open' : '' }"
        >
          Abierta
        </label>
      </div>
    </div>
  </${ elm }>`
}