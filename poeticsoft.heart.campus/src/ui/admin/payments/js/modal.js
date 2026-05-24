const {
  Modal,
  Button
} = wp.components

export default props => {

  return <>
    {
      props.state.modal.open &&
      <Modal 
        title={ props.state.modal.title }
        className="PaymentsAPPModal"
        onRequestClose={ 
          () => props.dispatch({
            modal: {
              open: false
            }
          }) 
        }
      >
        {
          props.state.modal.text ? 
          <div 
            className="Text"
            dangerouslySetInnerHTML={{
              __html: props.state.modal.text
            }}
          />
          :
          <></>
        }
        <Button 
          variant="secondary" 
          onClick={ props.state.modal.confirm }
        >
          { props.state.modal.button }
        </Button>
      </Modal>
    }
  </>
};