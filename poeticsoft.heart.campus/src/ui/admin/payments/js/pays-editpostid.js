const {
  __experimentalInputControl: InputControl,
  Button,
  SelectControl
} = wp.components
const {
  useState,
  useEffect
} = wp.element
import { 
  apiFetch
} from 'uiutils/api'

export default props => {

  const [editing, setEditing] = useState(false)
  const [postId, setPostId] = useState(false)
  const [postIdChanged, setPostIdChanged] = useState(false)

  const edit = () => {

    setEditing(true)
  }

  const close = () => {
    
    setEditing(false)
  }
  
  const save = () => {

    if(!postId) {

      props.dispatch({
        modal: {
          open: true,
          title: 'Error',
          text: 'Selecciona una página',
          button: 'Ok, lo hago',
          confirm: () => {

            props.dispatch({
              modal: {
                open: false
              }
            })
          }
        }
      })

      return
    }    

    props.dispatch({
      modal: {
        open: true,
        title: 'Cambiar página',
        text: `Seguro que quieres cambiar la página a la que se accede?`,
        button: `Si`,
        confirm: () => {  
      
          apiFetch(
            'campus/payments/update',
            {
              method: 'POST',
              body: {
                id: props.pay.id,
                post_id: postId
              }
            }
          )
          .then(response => response.json())
          .then(data => {

            props.dispatch({
              modal: {
                open: false 
              }
            })

            setEditing(false)

            props.refreshAll()
          })
        }
      }
    })
  }

  useEffect(() => {

    setPostIdChanged(props.field.value != postId)

  }, [postId])


  useEffect(() => {

    setPostId(props.field.value)

  }, [])

  return <div className="Edit Post">{
      props.canedit ?
      editing ?
      <>      
        {
          postIdChanged ?
          <Button
            icon="saved"
            onClick={ save }
          />
          :
          <Button
            icon="no"
            onClick={ close }
          />
        }
        <SelectControl 
          value={ postId }
          options={ props.state.campusPagesTree }
          onChange={
            value => setPostId(value)
          }
        />
      </>
      :
      <>
        <Button
          icon="edit"
          onClick={ () => setEditing(true) }
        />
        { 
          postId 
          && 
          <span className="PostTitle">{ 
            props.state.campusPagesById[postId] ?
            `[${ postId }] ${ props.state.campusPagesById[postId].title }`
            :
            'selecciona página'
          }</span> 
        }
      </>
      :
      <>
        { 
          postId 
          && 
          <span className="PostTitle">{ 
            props.state.campusPagesById[postId] ?
            `[${ postId }] ${ props.state.campusPagesById[postId].title }`
            :
            'sin asignar'
          }</span> 
        }
      </>
    }
  </div>
}
