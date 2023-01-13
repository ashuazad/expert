const getAlertBox = (type, message) => {    
    let dataHtml = '';
    dataHtml = '<div class="alert alert-'+type+' alert-dismissible fade show exp-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button><h4 class="text-'+type+'"><i class="fa fa-check-circle"></i> '+message.title+'</h4>'+message.text+'</div>';
    return dataHtml;
}

export default getAlertBox;