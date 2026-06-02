const {
  useReducer,
  useEffect 
} = wp.element
import { apifetch } from 'uiutils/api'
import {
  reducer,
  initState
} from './state'
import Header from './header'
import Pays from './pays'
import AddPay from './addpay'
import Modal from './modal'
import Messages from './messages'
import {
  pagesTree,
  showMessage
} from './utils'

export default () => {

  const accessType = poeticsoft_content_payment_admin_accesstype_origin
  const canEdit = [
    'mailrelay'
  ].includes(accessType)

  const [state, dispatch ] = useReducer(
    reducer,
    initState
  )

  const refreshPayments = () => {

    dispatch({
      pays: [],
      groupedPays: {}
    }) 

    apifetch('campus/payments/get')
    .then(response => response.json())
    .then(data => {

      if(data.result == 'error') {                      

        showMessage(
          dispatch,
          data.reason,
          'Error'
        )
        
      } else {

        const groupedPays = Object.groupBy(
          data.data,
          ({ user_mail }) => user_mail
        )
      
        dispatch({
          pays: data.data,
          groupedPays: groupedPays
        })                      

        showMessage(
          dispatch,
          'Accesos cargados',
          'info'
        )
      }
    })
  }

  const refreshPages = (refreshPaymentsAfter) => {

    apifetch('campus/pages')
    .then(response => response.json())
    .then(data => {

      if(data.result == 'error') {                      

        showMessage(
          dispatch,
          data.reason,
          'Error'
        )

      } else { 
      
        dispatch({
          campusPages: data.data,
          campusPagesById: data.data
          .reduce((pagesById, page) => {
            pagesById[page.id] = page
            return pagesById
          }, {}),
          campusPagesTree: [{
            value: 0,
            label: 'Selecciona página'
          }]
          .concat(pagesTree(data.data))
        })                             

        showMessage(
          dispatch,
          'Páginas cargadas',
          'info'
        )

        refreshPaymentsAfter && refreshPayments()
      }
    })
  }

  const refreshAll = () => {

    refreshPages(true)    
  }

  useEffect(() => {

    refreshAll()
    
  }, [])

  return <div className="Payments">
    <div className="List">
      <Header
        state={ state }
        dispatch={ dispatch }
        refreshAll={ refreshAll }
      />
      <Pays
        state={ state }
        dispatch={ dispatch }
        refreshAll={ refreshAll }
        canEdit={ canEdit }
      />    
    </div>
    {
      canEdit &&
      <AddPay
        state={ state }
        dispatch={ dispatch }
        refreshAll={ refreshAll }
      />
    }
    <Modal
      size="small"
      state={ state }
      dispatch={ dispatch }
    />
    <Messages
      state={ state }
      dispatch={ dispatch }
    />
  </div>
};