import immutableUpdate from 'immutable-update';

export const reducer = (state, action) => {

  return immutableUpdate(
    state,
    action
  )
}

export const initState = {
  pays: [],
  groupedPays: {},
  campusPages: [],
  campusPagesById: {},
  campusPagesTree: [],
  tableFields: [
    'user_mail',
    'post_id'
  ],
  tableFieldTitles: {
    user_mail: 'Email',
    post_id: 'Page Id'
  },
  modal: {
    open: false,
    title: 'Modal',
    text: 'Texto',
    button: 'Confirm',
    confirm: () => {

      console.log('confirm')
    }
  },
  newPay: {
    email: 'email',
    postId: null
  },
  messages: {
    open: false,
    text: 'Prueba de mensaje'
  }
}