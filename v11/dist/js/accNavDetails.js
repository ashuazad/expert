/* Search Code */
var baseUrl = 'https://www.advanceinstitute.co.in';
var currentTime = new Date().getTime();

function getLoginNav()
{
    $.ajax({
        url: baseUrl + '/ajax/getAccNavDetails.php?_=' + currentTime,
        type : 'POST',
        contentType : 'application/json',
        dataType:'json',
        success : function(data){
            console.log(data);
            renderLoginUser(data,'.login-user');
            navHtml(data.nav, '#sidebarnav');
        }
    });
}

function navHtml(data, element)
{
    var navHtml = '<li class="nav-small-cap main-nav">--- MAIN MENU</li>';
    for (var i=0;i<data.length;i++) {
    navHtml+='<li><a class="waves-effect waves-dark" href="'+baseUrl+data[i].link+'" aria-expanded="false"><i class="'+data[i].icon+'"></i><span class="hide-menu">'+data[i].name+'</span></a></li>';
    }
    navHtml+='<li class="nav-small-cap main-support-nav">--- SUPPORTS</li>';
    navHtml+='<li>\n' +
        '                            <a class="waves-effect waves-dark" href="'+baseUrl+'/index.php?id=logout" aria-expanded="false">\n' +
        '                                <i class="icon-logout"></i>\n' +
        '                                <span class="hide-menu">Log Out</span>\n' +
        '                            </a>\n' +
        '                        </li>';
    $(element).html(navHtml);
  //  return navHtml;
}

function renderLoginUser(data, element)
{
    $(element).text(data.login_user);
}

getLoginNav();