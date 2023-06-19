var records = new Object();
var currentRegno = null;

import {getCommon, getDefaultConfig} from "./common";
import {renderGrid} from "./communicationApi";

let defaultConfig = getDefaultConfig();

const checkRegno = (data) => {
    $.ajax({
        url: defaultConfig.baseUrl + '/ajax/getRegDetails.php?_=' + defaultConfig.currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        data:JSON.stringify(data),
        success : function( data ){
            //console.log(data);
            if (data.success) {
                currentRegno = data.data.regno
                handleCheckRegno(data);
                Swal.fire("Congratulations !", "Your Certificate is generated.", "success"); 
            } else {
                let error_message = '';
                for(let error of data.errors) {
                    error_message += error + ' ';
                }
               // Swal.fire("Error", error_message, "fail"); 
               Swal.fire({
                title: "Error",
                text: error_message,
                type: "error",
                icon: 'error'
            }).then(()=>{
                location.reload();
            });
            }
        }
    });
}

const handleCheckRegno = (regData) => {
    let data = regData.data;
    $('.studName').text(data.name);
    $('.studRegno').text(data.regno);
    renderCertificateTable(data.course,data.issue_date,'.downloadLinkTab');
    $('.stuDetails').show('fast');
    $('.table-certificate').show('fast');
}

const renderCertificateTable = (courses, issueDate, elementSelector) => {
    let tBodyHtml = '';
    courses.forEach(function(data, key){
        let downloadForm = `<form method="POST" id="quotation-pdf" action="certificatePdf.php" target="_blank">
                        <input type="hidden" name="regno" value="${currentRegno}">
                        <input type="hidden" name="course_name" value="${data}">
                        <input type="hidden" name="issue_date" value="${issueDate}">
                        <button type="submit" class="btn btn-info btn-lg">Download Certificate<i class="fas fa-download m-l-10"></i></button>
                        </form>`;
        let tBody = `<tr>
                    <td>${key+1}</td>
                    <td class="p-t-25 text-center"><h4>${data}</h4></td>
                    <td class="text-center">${downloadForm}</td>
                </tr>`;
        tBodyHtml += tBody;
    });  
    $(elementSelector).html(tBodyHtml);
   // console.log(tBodyHtml);
}

$('#getRegno').click(function(){
    $('#student-details').modal('hide');
    currentRegno = $('.regno').val();
    checkRegno({regno:currentRegno,rollNo:$('.rollno').val(),phoneNo:$('.phoneno').val()});
});
/// Default Load ///
$('#student-details').modal('show');
if ($('#downloadCert').attr('data-studReg')!='') {
    $('#student-details').modal('hide');
    currentRegno = $('#downloadCert').attr('data-studReg');
    checkRegno({regno:currentRegno,rollNo:'',phoneNo:''});
}
/// Default Load ///