const {
  Button,
  SelectControl,
  __experimentalInputControl: InputControl,
} = wp.components
import {
  validateMail
} from './utils'
import { 
  apiFetch
} from 'uiutils/api'

export default props => {

  const addPay = () => {

    if(!validateMail(props.state.newPay.email)) {

      props.dispatch({
        modal: {
          open: true,
          title: 'Error',
          text: 'Escribe un mail válido',
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

    if(!props.state.newPay.postId) {

      props.dispatch({
        modal: {
          open: true,
          title: 'Error',
          text: 'Selecciona página',
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
        title: 'Añadir pago',
        text: `Seguro que quieres dar acceso a esta página al mail <strong>${ props.state.newPay.email }</strong>`,
        button: `Si, añadir pago`,
        confirm: () => {  
      
          apiFetch(
            'campus/payments/create',
            {
              method: 'POST',
              body: {
                user_mail: props.state.newPay.email,
                post_id: props.state.newPay.postId
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

            props.refreshAll()
          })
        }
      }
    })
  }

  return <div className="AddPay">
    {
      props.state.pays.length ?
      Object.keys(props.state.pays[0])
      .reduce((fieldTitles, key) => {

        if(props.state.tableFields.includes(key)) {

          fieldTitles.push({
            key: key,
            title: props.state.tableFieldTitles[key]
          })
        }

        return fieldTitles
      }, [])
      .map(fieldTitle => <div className={`
        Column Field
        ${ fieldTitle.key }
      `}>
        { 
          fieldTitle.key == 'post_id' ?
          <SelectControl 
            value={ props.state.newPay.postId }
            options={ props.state.campusPagesTree }
            onChange={
              value => props.dispatch({
                newPay: {
                  postId: value
                }
              })
            }
          />
          :
          fieldTitle.key == 'user_mail' ?
          <InputControl
            value={ props.state.newPay.email }
            onChange={ 
              value => props.dispatch({
                newPay: {
                  email: value
                }
              })
            }
          />
          :
          <></>
        }
      </div>)
      .concat([
        <div className="Column Tools">
          <Button
            variant="primary"
            onClick={ addPay }
          >
            Añadir
          </Button>
        </div>
      ])
      :
      <></>
    }
  </div>
}
